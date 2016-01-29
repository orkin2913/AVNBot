#!/bin/bash
#Starter AVNBota

function start
{

	if ! screen -list | grep -q "AVNBot"; then
		screen -AdmS AVNBot php bot.php
		echo -e '\e[30;48;5;82mPomyslnie uruchomiono bota!\e[0m'
	else
		echo -e '\e[30;48;5;1mBot jest juz uruchomiony!\e[0m'
	fi

}

function stop
{

	echo -e '\e[30;48;5;82mPomyslnie zatrzymano bota!\e[0m'
	screen -X -S AVNBot stuff "^C"

}

echo -e ' 
       ;;;;;;;   .;;;      ,;;;  ;;;;;;    ;;;     
       ;;;;;;;    ;;;      ;;;;  ;;;;;;    ;;;      
       ;;;;;;;    ;;;`     ;;;   ;;;;;;`   ;;;      
      `;;; ;;;`   ;;;:     ;;;   ;;; ;;;   ;;;       
      :;;: ,;;;   :;;;    `;;;   ;;; ;;;   ;;;   \e[0;36m:\e[0m
      ;;;   ;;;    ;;;    :;;;   ;;; ,;;   ;;;   \e[0;36m:                 `   \e[0m
      ;;;   ;;;    ;;;    ;;;.   ;;;  ;;:  ;;;   \e[0;36m:                 `     \e[0m
      ;;;   ;;;    ;;;`   ;;;    ;;;  ;;;  ;;;   \e[0;36m:                 `   \e[0m
     ,;;;   :;;:   :;;;   ;;;    ;;;  ;;;  ;;;   \e[0;36m::::::   ......  ````` \e[0m
     ;;;.   .;;;   `;;;  .;;;    ;;;   ;;. ;;;   \e[0;36m:    :,  .    .   `   \e[0m
     ;;;:::::;;;    ;;;  ;;;,    ;;;   ;;; ;;;  \e[0;36m :    ,: `.    ..  `   \e[0m
     ;;;;;;;;;;;    ;;;  ;;;     ;;;   ;;; ;;;  \e[0;36m :    `: `.    `.  `  \e[0m
    .;;;;;;;;;;;`   :;;, ;;;     ;;;   `;; ;;; \e[0;36m  :    .: `.    ..  `   \e[0m
    :;;:     :;;;   `;;;;;;;     ;;;    ;;;;;;  \e[0;36m :    ::  .    ..  `    \e[0m
    ;;;`     `;;;    ;;;;;;:     ;;;    ;;;;;;  \e[0;36m :    :.  .    .   ``   ``\e[0m
    ;;;       ;;;    ;;;;;;      ;;;    ,;;;;;  \e[0;36m ::::::   `.....    ``` ``\e[0m
'

case "$1" in
	"start")
		start
	;;
	
	"stop")
		stop
	;;

	*)
		echo -e 'Uzyj start | stop'
	;;
esac
