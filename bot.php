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
		$clients['record'] = file_get_contents('tmp/userecord.txt');
		
		#Pętla z funkcjami bota
		$i['petla'] = 0; $i['animacja'] = 0; $i['pingpong'] = 0;
		while($i['petla'] != 1)
		{
				#Pętla wykonuje się co sekundę
				sleep(1);
				
				#Data wykonania pętli
				$datapetli = date('Y-m-d G:i:s');
				
				#Co 5 min bot wykonuje prostą operację
				#aby nie wyrzucało go z serwera za bezczynność
				if($i['pingpong'] == 300) {
						$tsAdmin->bindingList();
						$i['pingpong'] = 0;
				}
				
				
				#Wiadomość powitalna
				if($config['module']['welcomemsg']['enable'] == true) {
							welcomemsg();
				}

				#Rekord userów
				if($config['module']['userecord']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['userecord']['datazero'], intervaltosec($config['module']['userecord']['interval'])) == true) {
								userecord();
								$config['module']['userecord']['datazero'] = $datapetli;
						}
				}
				
				
				#Admini online
				if($config['module']['adminsonline']['enable'] == true) {
						if(juzmozna($datapetli, $config['module']['adminsonline']['datazero'], intervaltosec($config['module']['adminsonline']['interval'])) == true) {
							adminsonline();
							$config['module']['adminsonline']['datazero'] = $datapetli;
						}
				}
				

				
				#Sprawdzanie czy wystąpiły błędy
				if(count($tsAdmin->getDebugLog()) > 0) {
					
						$logContent = '';
						
						#Zapisywanie błędów do łańcucha
						foreach($tsAdmin->getDebugLog() as $logEntry) {
								echo $logEntry."\n";
								$logContent .= $logEntry;
						}
						
						#Tworzenie i zapisywanie do pliku logów z bota
						$logfile = $config['bot']['path'].'log/avnbot_'.date('Y-m-d_H_i_s').'.'.rand(2048, 65535).'.log';
						file_put_contents($logfile, $logContent);
						
						#Zatrzymywanie pracy bota
						die("Error ^^\nLogfile $logfile\n");
				}
				
				#Malutka animacja wyświetlająca status bota
				if($i['animacja'] == 4) $i['animacja'] = 0;
				if($i['animacja'] == 0) echo "Running! | \r";
				else if($i['animacja'] == 1) echo "Running! / \r";
				else if($i['animacja'] == 2) echo "Running! — \r";
				else if($i['animacja'] == 3) echo "Running! \ \r";
				$i['animacja']++; $i['pingpong']++;
		}
		
		
	
} else {

		echo "Connection could not be established.\n";
}

?>
