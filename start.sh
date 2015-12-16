#!/bin/sh
#Starter AVNBota

pgrep php >/dev/null 2>&1 || screen -AdmS AVNBot php bot.php
