#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")

session1=$(qdbus org.kde.yakuake /yakuake/sessions activeSessionId)
qdbus org.kde.yakuake /yakuake/tabs org.kde.yakuake.setTabTitle "$session1" "Console"
sleep 0.1
qdbus org.kde.yakuake /yakuake/sessions runCommandInTerminal "$session1" "cd $SCRIPTPATH"


session2=$(qdbus org.kde.yakuake /yakuake/sessions org.kde.yakuake.addSession)
sleep 0.1
qdbus org.kde.yakuake /yakuake/tabs org.kde.yakuake.setTabTitle "$session2" "Symfony server"
qdbus org.kde.yakuake /yakuake/sessions runCommandInTerminal "$session2" "cd $SCRIPTPATH"
sleep 0.1
qdbus org.kde.yakuake /yakuake/sessions runCommandInTerminal "$session2" 'symfony serve'

session3=$(qdbus org.kde.yakuake /yakuake/sessions org.kde.yakuake.addSession)
sleep 0.1
qdbus org.kde.yakuake /yakuake/tabs org.kde.yakuake.setTabTitle "$session3" "Yarn"
qdbus org.kde.yakuake /yakuake/sessions runCommandInTerminal "$session3" "cd $SCRIPTPATH"
sleep 0.1
qdbus org.kde.yakuake /yakuake/sessions runCommandInTerminal "$session3" 'yarn'
