<?php
require_once __DIR__ . '/../config.php';

class Rcon
{
    private string $host;
    private int $port;
    private string $password;
    private $socket;

    public function __construct()
    {
        global $RCON_HOST, $RCON_PORT, $RCON_PASSWORD;
        if (!isset($RCON_HOST, $RCON_PORT, $RCON_PASSWORD)) {
            throw new RuntimeException('RCON config not loaded');
        }
        $this->host = $RCON_HOST;
        $this->port = $RCON_PORT;
        $this->password = $RCON_PASSWORD;
    }

    public function connect(): bool
    {
        $this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, 3);
        if (!$this->socket) return false;

        stream_set_timeout($this->socket, 3);
        return $this->login();
    }

    private function login(): bool
    {
        $this->sendPacket(3, $this->password);
        $response = $this->readPacket();
        return $response['id'] !== -1;
    }

    public function command(string $cmd): string
    {
        $this->sendPacket(2, $cmd);
        $response = $this->readPacket();
        return $response['body'] ?? '';
    }

    private function sendPacket(int $type, string $body): void
    {
        $id = random_int(1, 999999);
        $packet = pack("V", strlen($body) + 10)
            . pack("V", $id)
            . pack("V", $type)
            . $body
            . "\x00\x00";

        fwrite($this->socket, $packet);
    }

    private function readPacket(): array
    {
        $sizeData = fread($this->socket, 4);
        if (!$sizeData) return [];

        $size = unpack("V", $sizeData)[1];
        $packet = fread($this->socket, $size);

        return [
            "id" => unpack("V", substr($packet, 0, 4))[1],
            "type" => unpack("V", substr($packet, 4, 4))[1],
            "body" => rtrim(substr($packet, 8), "\x00")
        ];
    }

    public function disconnect(): void
    {
        if ($this->socket) fclose($this->socket);
    }
}
