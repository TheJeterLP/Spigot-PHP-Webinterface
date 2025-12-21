#!/bin/bash
# Stoppt den Minecraft-Server sauber über Screen
SESSION="mcserver"

if screen -list | grep -q "$SESSION"; then
    # "stop" an Minecraft senden
    screen -S "$SESSION" -p 0 -X stuff "stop\n"
    echo "Stop-Befehl an Minecraft gesendet."
else
    echo "Keine laufende Server-Session gefunden."
fi
