<?php
// Spigot Web Interface Configuration Example File
// Copy this file to config.php and modify the values as needed.
// Make sure to secure this file properly.

//Initial Admin User:
//Username: admin
//Password: admin123

return [
    'spigot' => [
        'server-path' => '/path/to/your/spigot/server',
        'start-command' => 'sudo /bin/systemctl start minecraft 2>&1',
        'stop-command' => 'sudo /bin/systemctl stop minecraft 2>&1',
        'restart-command' => 'sudo /bin/systemctl restart minecraft 2>&1',
        'plugin-backend-url' => 'http://127.0.0.1:8765'
    ]
];
