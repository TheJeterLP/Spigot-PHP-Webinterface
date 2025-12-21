#!/bin/bash
cd /opt/spigot-server || exit 1
SESSION="mcserver"

# Alte Dead-Screens entfernen
screen -wipe > /dev/null 2>&1

# Prüfen, ob Session schon läuft
if screen -list | grep -q "$SESSION"; then
    echo "Server läuft bereits!"
    exit 0
fi

# Screen starten mit loginshell (-L) und sicherem Environment
screen -dmSL "$SESSION" bash -c "java -Xmx2G -DIReallyKnowWhatIAmDoingISwear -jar spigot.jar nogui"
echo "Minecraft-Server gestartet in Screen-Session '$SESSION'."
exit 0
