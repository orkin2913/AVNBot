<?php
/*
    AVNBot
    Copyright (C) 2015  Orkin (AVNTeam.net)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
*/

#Brak limitów czasu wykonania kodu
set_time_limit(0);

#Podłączanie ts3admin.class.php
require('class/ts3admin.class.php');

#Podłączanie config.php
require('config.php');

#Podłączanie functions.php
require('functions.php');

echo "\n";

logo();

echo "Checking version...\n";
$version = str_replace(array("\t", "\n"), "", checkversion());

if($version != $config['bot']['version']) {
	echo "\e[0;33mWarning!\e[0m\nLatest version: \e[0;32m".$version."\e[0m\nYour version: \e[0;31m".$config['bot']['version']."\e[0m\nWe recommend update\n\n";
	
} else {
	echo "\e[0;32mOK!\e[0m - Version up to date\n\n";
}





#Budowanie nowego obiektu
$tsAdmin = new ts3admin($config['server']['ip'], $config['server']['queryport']);

#Sprawdzanie połączenia z serwerem
if($tsAdmin->getElement('success', $tsAdmin->connect())) {
	
		#Logowanie się na użytkownika Query
		$tsAdmin->login($config['query']['login'], $config['query']['password']);
		
		#Wybieranie serwera
		$tsAdmin->selectServer($config['server']['port']);
		
		#Ustawianie nazwy bota
		$tsAdmin->setName($config['bot']['nickname']);
		
		#Przenoszenie bota do wybranego kanału
		$whoami = $tsAdmin->getElement('data', $tsAdmin->whoAmI());
		$tsAdmin->clientMove($whoami['client_id'] , $config['bot']['channel']);
		
		echo "Connection established!\n";

		$clients['aktualnie'] = listaclientow();
		if($config['module']['accessgroup']['enable'] == true) {
				$clients['aktualnie2'] = listaclientow();
		}
		#$commandsrecived = $tsAdmin->getElement('data', $tsAdmin->execOwnCommand(3, 'servernotifyregister event=textprivate'));
		

		#Pętla z funkcjami bota
		$i['pingpong'] = 0; $i['animacja'] = 0;
		while(true)
		{

				#Pętla wykonuje się co czas ustawiony w configu
				time_nanosleep(floor($config['bot']['speed']/10), $config['bot']['speed']%10*100000000);
				
				#Data wykonania pętli
				$datapetli = date('Y-m-d G:i:s');
				
				#Co 5 min bot wykonuje prostą operację
				#aby nie wyrzucało go z serwera za bezczynność
				if($i['pingpong'] == 600) {
						#$tsAdmin->bindingList();
						$tsAdmin->whoAmI();
						$i['pingpong'] = 0;
				}
				

				#Komendy // nie działa
				/*
				if($config['module']['commands']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['commands']['datazero'], intervaltosec($config['module']['commands']['interval'])) == true) {
								commands();
								$config['module']['commands']['datazero'] = $datapetli;
						}
				}
				*/
				
				#Kanałowe rangi dostępu
				if($config['module']['accessgroup']['enable'] == true) {
						accessgroupisnew();
						if(juzmozna($datapetli, $config['module']['accessgroup']['datazero'], intervaltosec($config['module']['accessgroup']['interval'])) == true) {
								accessgroup();
								$config['module']['accessgroup']['datazero'] = $datapetli;
						}
				}
				
				#Wiadomość powitalna
				if($config['module']['welcomemsg']['enable'] == true) {
							welcomemsg();
				}
				
				#Autorejestracja
				if($config['module']['autogroups']['enable'] == true) {
							autogroups();
				}

				#Rekord userów
				if($config['module']['userecord']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['userecord']['datazero'], intervaltosec($config['module']['userecord']['interval'])) == true) {
								userecord();
								$config['module']['userecord']['datazero'] = $datapetli;
						}
				}
				
				#Rekord userów
				if($config['module']['useronline']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['useronline']['datazero'], intervaltosec($config['module']['useronline']['interval'])) == true) {
								useronline();
								$config['module']['useronline']['datazero'] = $datapetli;
						}
				}
				
				
				#Admini online
				if($config['module']['adminsonline']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['adminsonline']['datazero'], intervaltosec($config['module']['adminsonline']['interval'])) == true) {
							adminsonline();
							$config['module']['adminsonline']['datazero'] = $datapetli;
						}
				}
				
				
				#Wiadomość na czacie głównym
				if($config['module']['automsg']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['automsg']['datazero'], intervaltosec($config['module']['automsg']['interval'])) == true) {
							automsg();
							$config['module']['automsg']['datazero'] = $datapetli;
						}
				}
				
				
				#AFKBot
				if($config['module']['movetoafk']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['movetoafk']['datazero'], intervaltosec($config['module']['movetoafk']['interval'])) == true) {
							movetoafk();
							$config['module']['movetoafk']['datazero'] = $datapetli;
						}
				}
				
				logi();

				
				#Malutka animacja wyświetlająca status bota
				if($i['animacja'] == 4) $i['animacja'] = 0;
				if($i['animacja'] == 0) echo "Running! | \r";
				else if($i['animacja'] == 1) echo "Running! / \r";
				else if($i['animacja'] == 2) echo "Running! — \r";
				else if($i['animacja'] == 3) echo "Running! \ \r";
				$i['animacja']++; $i['pingpong']++;

		}
		
		
	
} else {

		echo "\e[0;31mConnection could not be established.\e[0m\n";
}

?>
