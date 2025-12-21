<?php
// Spigot Web Interface Configuration Example File
// Copy this file to config.php and modify the values as needed.
// Make sure to secure this file properly.

//Initial Admin User:
//Username: admin
//Password: admin123

// RCON Configuration
$RCON_HOST = "127.0.0.1";
$RCON_PORT = 25575;
$RCON_PASSWORD = "YourSecurePasswordHere!";
// Spigot Server Control Commands
$SPIGOT_SERVER_PATH = "/path/to/your/spigot/server";
$SPIGOT_START_COMMAND = "sudo /bin/systemctl start minecraft 2>&1";
$SPIGOT_STOP_COMMAND = "sudo /bin/systemctl stop minecraft 2>&1";
$SPIGOT_RESTART_COMMAND = "sudo /bin/systemctl restart minecraft 2>&1";
