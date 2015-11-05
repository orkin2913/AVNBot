<?php

#Brak limitów czasu wykonania kodu
set_time_limit(0);

#Podłączanie ts3admin.class.php
require('class/ts3admin.class.php');

#Podłączanie config.php
require('config.php');

#Podłączanie functions.php
require('functions.php');

#Nazwa modułu bota
$module = 'UserRecord';

#Budowanie nowego obiektu
$tsAdmin = new ts3admin($config['server']['ip'], $config['server']['queryport']);

#Sprawdzanie połączenia z serwerem
if($tsAdmin->getElement('success', $tsAdmin->connect())) {
	
		#Logowanie się na użytkownika Query
		$tsAdmin->login($config['query']['login'], $config['query']['password']);
		
		#Wybieranie serwera
		$tsAdmin->selectServer($config['server']['port']);
		
		#Ustawianie nazwy bota
		$tsAdmin->setName('['.$config['bot']['nickname'].']'.$module);
		
		#Przenoszenie bota do wybranego kanału
		$whoami = $tsAdmin->getElement('data', $tsAdmin->whoAmI());
		$tsAdmin->clientMove($whoami['client_id'] , $config['bot']['channel']);
		
		echo "Connection established!\n";
		
		$clients['record'] = file_get_contents('tmp/userecord.txt');
		
		#Pętla z funkcjami bota
		$i['petla'] = 0; $i['animacja'] = 0; $i['pingpong'] = 0; $i['channel'] = 0;
		while($i['petla'] != 1)
		{
				#Pętla wykonuje się co sekundę
				sleep(1);
				
				#Co 5 min bot wykonuje prostą operację
				#aby nie wyrzucało go z serwera za bezczynność
				if($i['pingpong'] == 300) {
						$tsAdmin->bindingList();
						$i['pingpong'] = 0;
				}
				
				
				$clients['count'] = $tsAdmin->getElement('data', $tsAdmin->serverInfo());
				$clients['recent'] = $clients['count']['virtualserver_clientsonline'] - $clients['count']['virtualserver_queryclientsonline'];
				
				$channel['info'] = $tsAdmin->getElement('data', $tsAdmin->channelInfo($config['module']['userecord']['channel']));
				$channel['check'] = explode(']', $channel['info']['channel_name']);
				
				if($clients['recent'] > $clients['record']) {
					
						$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $config['module']['userecord']['spacer'] . $clients['recent']));
						file_put_contents('tmp/userecord.txt', $clients['recent']);
						$clients['record'] = $clients['recent'];
						
				} elseif($clients['record'] > $channel['check'][1] || $clients['record'] < $channel['check'][1]) {
					
						$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $config['module']['userecord']['spacer'] . $clients['record']));
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
						$logfile = $config['bot']['path'].'log/avnbot_'.$module.'_'.date('Y-m-d_H_i_s').'.'.rand(2048, 65535).'.log';
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
				$i['animacja']++; $i['pingpong']++; $i['channel']++;
		}
		
		
	
} else {

		echo "Connection could not be established.\n";
}

?>
