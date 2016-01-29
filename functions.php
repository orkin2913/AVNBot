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

		$clients['record'] = str_replace(array("\t", "\n"), "", file_get_contents('tmp/userecord.txt'));
		$clientchannel = array();
		$logContent = "";
		$config['module']['logi']['datazero'] = '1970-01-01 00:00:00';
		$config['module']['logi']['interval'] = array('days' => 0, 'hours' => 0, 'minutes' => 30, 'seconds' => 0);




function logi() {

		global $tsAdmin;
		global $config;
		global $logContent;
		global $datapetli;
		
		#Sprawdzanie czy wystąpiły błędy
		if(count($tsAdmin->getDebugLog()) > 0) {
				$logContent = '';
				#Zapisywanie błędów do łańcucha
				foreach($tsAdmin->getDebugLog() as $logEntry) {
						if(strpos($logEntry, 'Error in channelGroupClientList()') === false) {
								echo $logEntry."\n";
								$logContent .= $logEntry."\n";
						}
				}
				
				if(juzmozna($datapetli, $config['module']['logi']['datazero'], intervaltosec($config['module']['logi']['interval'])) == true) {
						if(!empty($logContent)) {
								#Tworzenie i zapisywanie do pliku logów z bota
								$logfile = dirname(__FILE__).'/log/avnbot_'.date('Y-m-d_H_i_s').'.'.rand(2048, 65535).'.log';
								file_put_contents($logfile, $logContent);
								$logContent = '';
						}
						$config['module']['logi']['datazero'] = $datapetli;
						
						#Zatrzymywanie pracy bota
						#echo("\e[0;31mError ^^\e[0m\n\e[0;33mLogfile ".$logfile."\e[0m\n");
				}
				$tsAdmin->clearDebugLog();

		}
}

#data - client id
#Zwraca true albo false w zależności czy jest adminem

function isadmin($data) {
		
		global $tsAdmin;
		global $config;

		
		#$thisclientinfo = clientInfo($data);
		$thisclientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($data));
		if(!empty($thisclientinfo)) {

				#Sprawdzanie grup serwerowych każdego clienta
				$thisclientinfoo['groups'] = explode(',', $thisclientinfo['client_servergroups']);
				$thisclientinfoo['isadmin'] = false;
				foreach($thisclientinfoo['groups'] as $group) {
						#Jeżeli client ma grupę administracyją, to dostaje status administratora
						if(in_array($group, $config['bot']['admingroups'])) {
								$thisclientinfoo['isadmin'] = true;
						}
				}

				

				return $thisclientinfoo['isadmin'];
		
		} else {
				return false;
		}
}

function clientInfo($clid) {
		
		global $tsAdmin;

		return $tsAdmin->getElement('0', $tsAdmin->getElement('data', $tsAdmin->execOwnCommand(2, 'clientinfo clid='.$clid)));
}

function istechnik($data) {
		
		global $tsAdmin;
		global $config;
		
		#$thisclientinfo = clientInfo($data);
		$thisclientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($data));
		
		if(!empty($thisclientinfo)) {
		
				#Sprawdzanie grup serwerowych każdego clienta
				
				$thisclientinfo['groups'] = explode(',', $thisclientinfo['client_servergroups']);
				$thisclientinfo['istechnik'] = false;
				
				
				foreach($thisclientinfo['groups'] as $group) {
						if(in_array($group, $config['bot']['technician'])) {
								$$thisclientinfo['istechnik'] = true;
						}
				}
				

				return $thisclientinfo['istechnik'];
		
		} else {
				return false;
		}
}

function commands() {
		
		global $tsAdmin;
		global $commandsrecived;
		
		/*
		echo $commandsrecived;
		$commandsrecived = explode("\n", $commandsrecived);
		$count = count($commandsrecived);
		for($i = 1; $i <= 2; $i++) {
				unset($commandsrecived[$count - $i]);
		}
		
		if(count($commandsrecived)>0) {
				echo 'jest';
				foreach($commandsrecived as $linijka) {
						$linijka = explode(' ', $linijka);
						
						$clid = explode('invokerid=', $linijka[4]);
						$clid = $clid[1];
						
						if(istechnik($clid)) {
								$msg = explode('msg=', $linijka[2]);
								$msg = str_replace("\s", " ", $msg[1]);
								if($msg[0] == '!') {
										$msg = explode('!', $msg);
										$msg = explode(' ', $msg[1]);
										$tsAdmin->sendMessage(1, $clid, 'Przyjąłem');
								}
						}
				}
		}
		$commandsrecived = $tsAdmin->getElement('data', $tsAdmin->execOwnCommand(3, 'servernotifyregister event=textprivate'));
		*/
		echo $tsAdmin->checkCommands();
}


function accessgroup() {
		
		global $tsAdmin;
		global $config;

		
		$listaclientow = $tsAdmin->getElement('data', $tsAdmin->clientList("-groups"));
		foreach($listaclientow as $client) {
				if($client['client_type'] == 0) {
						if(!isadmin($client['clid'])) {
								$jegorangi = explode(',', $client['client_servergroups']);
								#sprawdzić czy ma rangę z listy i jak tak to zdjąć mu range kanałową z blokadą
								#jak nie ma rangi z listy to daj mu blokadę
								foreach($config['module']['accessgroup']['channels'] as $kanal => $ranga) {
										if(!in_array($ranga, $jegorangi)) {
												$ranganakanale = $tsAdmin->getElement('cgid', $tsAdmin->getElement('0', $tsAdmin->getElement('data', $tsAdmin->channelGroupClientList($kanal, $client['client_database_id']))));
												if($ranganakanale != $config['module']['accessgroup']['blockgroup']) {
														$tsAdmin->setClientChannelGroup($config['module']['accessgroup']['blockgroup'], $kanal, $client['client_database_id']);
														if($client['cid'] == $kanal) {
																$tsAdmin->clientPoke($client['clid'], 'Brak dostępu do kanału');
																$tsAdmin->clientKick($client['clid'], "channel", 'Brak dostępu do kanału');
														}
												}
										} else {
												$ranganakanale = $tsAdmin->getElement('cgid', $tsAdmin->getElement('0', $tsAdmin->getElement('data', $tsAdmin->channelGroupClientList($kanal, $client['client_database_id']))));
												if($ranganakanale == $config['module']['accessgroup']['blockgroup']) {
														$kanalinfo = $tsAdmin->getElement('data', $tsAdmin->channelInfo($kanal));
														$tsAdmin->setClientChannelGroup($config['module']['accessgroup']['guestgroup'], $kanal, $client['client_database_id']);
														$tsAdmin->clientPoke($client['clid'], 'Otrzymałeś dostęp do kanału "'.$kanalinfo['channel_name'].'"!');
												}
										}
								}
						}
				}
		}
		
}

function accessgroupisnew() {
		
		global $tsAdmin;
		global $config;
		global $clients;

		
		$clients['new2'] = listaclientow();
		
		#Porównywanie czy doszedł ktoś nowy
		$clients['diff2'] = array_diff($clients['new2'], $clients['aktualnie2']);
		
		#Sprawdzanie nowych użytkowników
		if(!count($clients['diff2']) == 0) {
				foreach($clients['diff2'] as $clientID) {
						$isnew = clientisnew($clientID);
						if($isnew == true) {
								$clientinfoo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($clientID));
								foreach($config['module']['accessgroup']['channels'] as $kanal => $ranga) {
										$tsAdmin->setClientChannelGroup($config['module']['accessgroup']['blockgroup'], $kanal, $clientinfoo['client_database_id']);
										if($clientinfoo['cid'] == $kanal) {
												$tsAdmin->clientPoke($clientID, 'Niedobry Ty! Nie wolno tak cwaniaku ;) Jak chcesz tam wejść, to poproś o rangę.');
												$tsAdmin->clientKick($clientID, "channel", 'Próba oszukania systemu ;)');
										}
								}
						}
				}
		}
		
		#Przygotowywanie do następnej pętli
		$clients['aktualnie2'] = $clients['new2'];
		

}

function accessgroupinstall() {
		
		global $tsAdmin;
		global $config;
		$start = 0;
		$duration = -1;
		$count = 0;
		$stop = '';
		$dblista = $tsAdmin->getElement('data', $tsAdmin->clientDbList($start, $duration));
		do{
				$count = $count + count($dblista);
				foreach($dblista as $user) {
						$rangi = $tsAdmin->getElement('data', $tsAdmin->serverGroupsByClientID($user['cldbid']));
						$jegorangi = array();
						foreach($rangi as $ranga) {
								array_push($jegorangi, $ranga['sgid']);
						}
						foreach($config['module']['accessgroup']['channels'] as $kanal => $ranga) {
								if(!in_array($ranga, $jegorangi)) {
										$ranganakanale = $tsAdmin->getElement('cgid', $tsAdmin->getElement('0', $tsAdmin->getElement('data', $tsAdmin->channelGroupClientList($kanal, $user['cldbid']))));
										if($ranganakanale != $config['module']['accessgroup']['blockgroup']) {
												$tsAdmin->setClientChannelGroup($config['module']['accessgroup']['blockgroup'], $kanal, $user['cldbid']);
										}
										echo "CHANNELID: ".$kanal." CLIENTDBID: ".$user['cldbid']." ACCESS: FALSE\n";
								} else {
										echo "CHANNELID: ".$kanal." CLIENTDBID: ".$user['cldbid']." ACCESS: TRUE\n";
								}
						}
						
				}
				$start = $count;
				$dblista = $tsAdmin->getElement('data', $tsAdmin->clientDbList($start, $duration));
		}while(!empty($dblista));
}


#Zwraca tablicę z listą clid wszystkich clientów na serwerze

function listaclientow() {
		
		global $tsAdmin;

		
		#Zbieranie listy userów
		$clients['all'] = $tsAdmin->getElement('data', $tsAdmin->clientList());
		
		#Zbieranie danych o użytkownikach
		$clients['recent'] = array();
		foreach($clients['all'] as $client) {
				
				if($client['client_type'] == 0) {
						array_push($clients['recent'], $client['clid']);
				}
		}
		

		return $clients['recent'];
}

function logo() {
		
echo " 
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

";
}

#Funkcja sprawdzająca wersję bota

function checkversion() {
		
		global $config;
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);  
		$version = file_get_contents('https://raw.githubusercontent.com/orkin2913/AVNBot/master/version', false, stream_context_create($arrContextOptions));
		
		return $version;
}

#clid - id clienta na serwerze
#Zwraca true albo false w zależności czy client połączył się po raz pierwszy

function clientisnew($clid) {
		
		global $tsAdmin;

		
		#$thisclientinfo = clientInfo($clid);
		$thisclientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($clid));
		
		if(!empty($thisclientinfo)) {
				if($thisclientinfo['client_totalconnections'] == 1)  {
						$isnew = true;
				} else {
						$isnew = false;
				}
				

				return $isnew;
				
		} else {
				return false;
			
		}
}

#msg - wiadomość do przerobienia // clid - id clienta do którego będzie wysłana wiadomość
#Zwraca wiadomość z zamienionymi znacznikami %foobar% na dane z funkcji

function codeinmsg($msg, $clid) {
		
		global $tsAdmin;
		
		#$clientinfo = clientInfo($clid);
		$clientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($clid));
		
		$tags = array(
				
				0 => array(0 => '%CLIENT_IP%', 1 => $clientinfo['connection_client_ip']),
				
				1 => array(0 => '%CLIENT_UNIQUE_ID%', 1 => $clientinfo['client_unique_identifier']),
				
				2 => array(0 => '%CLIENT_DATABASE_ID%', 1 => $clientinfo['client_database_id']),
				
				3 => array(0 => '%CLIENT_ID%', 1 => $clid),
				
				4 => array(0 => '%CLIENT_COUNTRY%', 1 => $clientinfo['client_country']),
				
				5 => array(0 => '%CLIENT_VERSION%', 1 => $clientinfo['client_version']),
				
				6 => array(0 => '%CLIENT_PLATFORM%', 1 => $clientinfo['client_platform']),
				
				7 => array(0 => '%CLIENT_CREATED%', 1 => date("d-m-Y", $clientinfo['client_created'])),
				
				8 => array(0 => '%CLIENT_NICKNAME%', 1 => $clientinfo['client_nickname']),
				
				9 => array(0 => '%CLIENT_LASTCONNECTED%', 1 => date("d-m-Y", $clientinfo['client_lastconnected'])),
				
				10 => array(0 => '%CLIENT_TOTALCONNECTIONS%', 1 => $clientinfo['client_totalconnections']),
				
				11 => array(0 => ' ', 1 => '\s'),
				
				12 => array(0 => "\n", 1 => '\n')
				
		);
		
		foreach($tags as $tag) {
				$msg = str_replace($tag[0], $tag[1], $msg);
		}


		return $msg;
}


#Funkcja zwracająca nazwę rangi
#search - id szukanej grupy

function showgroupname($search) {
		
		global $tsAdmin;

		
		$groups = $tsAdmin->getElement('data', $tsAdmin->serverGroupList());
		$groupname = '';
		foreach($groups as $group) {
				if($group['sgid'] == $search) {
						$groupname = $group['name'];
				}
		}
		

		return $groupname;
}


#Funkcja zwracająca ilość sekund z interwału
#interval - tablica z interwałem

function intervaltosec($interval) {
		
		$interval['hours'] = $interval['hours'] + $interval['days']*24;
		$interval['minutes'] = $interval['minutes'] + $interval['hours']*60;
		$interval['seconds'] = $interval['seconds'] + $interval['minutes']*60;
		
		return $interval['seconds'];
}


#Funkcja zwracająca true jeżeli między data1 a data2 jest większa różnica niż interval
#data1 - data teraźniejsza
#data2 - data 'kiedyś'
#interval - liczba sekund z funkcji intervaltosec()

function juzmozna($data1, $data2, $interval) {
		
		$sekundy2 = strtotime($data2);
		$sekundy1 = strtotime($data1);
		$roznica = $sekundy1 - $sekundy2;
		
		if($roznica >= $interval) {
				$juzmozna = true;
		} else {
				$juzmozna  = false;
		}
		
		return $juzmozna;
}

#Funkcja wiadomości powitalnej

function welcomemsg() {
		
		global $tsAdmin;
		global $config;
		global $clients;

		
		$config['module']['welcomemsg']['message'] = file_get_contents('msg.txt');
		$clients['new'] = listaclientow();
		
		#Porównywanie czy doszedł ktoś nowy
		$clients['diff'] = array_diff($clients['new'], $clients['aktualnie']);
		
		#Sprawdzanie nowych użytkowników
		if(!count($clients['diff']) == 0) {
				foreach($clients['diff'] as $clientID) {
						$msgtosend = codeinmsg($config['module']['welcomemsg']['message'], $clientID);
						
						#Sprawdzanie czy bot ma wysyłać tylko do zwykłych clientów
						if($config['module']['welcomemsg']['mode'] == 1) {
								
								$isadmin = isadmin($clientID);
								
								#Jeżeli koleś nie jest adminem, to wyślij wiadomość
								if(!$isadmin == true) {
										#$tsAdmin->execOwnCommand(0, 'sendtextmessage targetmode=1 target='.$clientID.' msg='.$msgtosend);
										$tsAdmin->sendMessage(1, $clientID, $msgtosend);

								}
						} elseif($config['module']['welcomemsg']['mode'] == 0) {
							
								#Jeżeli ma wysyłać do wszystkich, to wyślij bez względu na rangę
								#$tsAdmin->execOwnCommand(0, 'sendtextmessage targetmode=1 target='.$clientID.' msg='.$msgtosend);
								$tsAdmin->sendMessage(1, $clientID, $msgtosend);
								
						} elseif($config['module']['welcomemsg']['mode'] == 2) {
								
								$isnew = clientisnew($clientID);
								
								if($isnew == true) {
										#Jeżeli nowy, to wyślij
										#$tsAdmin->execOwnCommand(0, 'sendtextmessage targetmode=1 target='.$clientID.' msg='.$msgtosend);
										$tsAdmin->sendMessage(1, $clientID, $msgtosend);
								}
						}
				}
		}
		
		#Przygotowywanie do następnej pętli
		$clients['aktualnie'] = $clients['new'];
		

}


#Funkcja userów online

function useronline() {
		
		global $tsAdmin;
		global $config;
		global $clients;

		
		$clients['count'] = $tsAdmin->getElement('data', $tsAdmin->serverInfo());
		$clients['recent'] = $clients['count']['virtualserver_clientsonline'] - $clients['count']['virtualserver_queryclientsonline'];
		
		$channel['info'] = $tsAdmin->getElement('data', $tsAdmin->channelInfo($config['module']['useronline']['channel']));
		$channel['name'] = $config['module']['useronline']['name'].$clients['recent'];

		if($channel['name'] != $channel['info']['channel_name']) {
				$tsAdmin->channelEdit($config['module']['useronline']['channel'], array('channel_name' => $channel['name']));
		}
		

		
}

#Funkcja rekordu userów

function userecord() {
		
		global $tsAdmin;
		global $config;
		global $clients;

		
		$clients['count'] = $tsAdmin->getElement('data', $tsAdmin->serverInfo());
		$clients['recent'] = $clients['count']['virtualserver_clientsonline'] - $clients['count']['virtualserver_queryclientsonline'];
		
		$channel['info'] = $tsAdmin->getElement('data', $tsAdmin->channelInfo($config['module']['userecord']['channel']));
		$channel['name'] = $config['module']['userecord']['name'].$clients['record'];
		
		if($clients['recent'] > $clients['record']) {

				if($channel['info']['channel_name'] != $channel['name']) {
						$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $channel['name']));
				}
				file_put_contents('tmp/userecord.txt', $clients['recent']);
				$clients['record'] = $clients['recent'];
		} elseif($channel['info']['channel_name'] != $channel['name']) {
				$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $channel['name']));
		}
		

		
}


#Funkcja admini online

function adminsonline() {
		
		global $tsAdmin;
		global $config;
		global $clients;

		
		$channeldesc = '[CENTER]';
		
		foreach($config['bot']['admingroups'] as $group) {
				$name = showgroupname($group);
				$groupsclientlist =  $tsAdmin->getElement('data', $tsAdmin->serverGroupClientList($group, $names = true));
				
				#Sprawdzanie czy online
				$clientsinfo = $tsAdmin->getElement('data', $tsAdmin->clientList("-uid"));
				
				$channeldesc .= '[SIZE='.$config['module']['adminsonline']['groupsize'].'][B][COLOR='.$config['module']['adminsonline']['groupcolor'].']'.$name.'[/COLOR][/B][/SIZE]\n';
				if(array_key_exists('cldbid', $groupsclientlist[0])) {
						foreach($groupsclientlist as $groupclients) {
								if($groupclients['client_unique_identifier'] != 'serveradmin') {

										foreach($clientsinfo as $clientinfoo) {
												
												if($clientinfoo['client_unique_identifier'] == $groupclients['client_unique_identifier']) {
														$jestnaserwie = true;
														break;
												} else {
														$jestnaserwie = false;
												}
										}
										
										if($jestnaserwie == true) {
												$channeldesc .= '[SIZE='.$config['module']['adminsonline']['nicksize'].'][B][url=client://'.$clientinfoo['clid'].'/'.$groupclients['client_unique_identifier'].']'.$groupclients['client_nickname'].'[/url][/B][/SIZE]\n[SIZE='.$config['module']['adminsonline']['statusize'].'][B][color=#347C17]Online[/color][/B][/SIZE]\n';
										} else {
												$channeldesc .= '[SIZE='.$config['module']['adminsonline']['nicksize'].'][B][url=client://0/'.$groupclients['client_unique_identifier'].']'.$groupclients['client_nickname'].'[/url][/B][/SIZE]\n[SIZE='.$config['module']['adminsonline']['statusize'].'][B][color=#F62217]Offline[/color][/B][/SIZE]\n';
										}
								}
						}
						$channeldesc .= '\n\n';
				}
				
		}
		$channeldesc .= '[/CENTER]';
		
		#Sprawdza czy status zmienił się od ostatniego sprawdzania
		$adminStatusChannelInfo = $tsAdmin->channelInfo($config['module']['adminsonline']['channel']);
		if(!strcmp($adminStatusChannelInfo['data']['channel_description'], $channeldesc) == 0) {
				$tsAdmin->channelEdit($config['module']['adminsonline']['channel'], array('channel_description' => $channeldesc));
		}
		

		
}


#Funkcja wysyłająca wiadomość na czat główny

function automsg() {
	
		global $tsAdmin;
		global $config;
		
		$config['module']['automsg']['message'] = file_get_contents('servermsg.txt');
		$tsAdmin->sendMessage(3, 1, $config['module']['automsg']['message']);
		

}


#Funkcja AFKBota

function movetoafk() {
	
		global $tsAdmin;
		global $config;
		global $clientchannel;

		
		$clients = $tsAdmin->getElement('data', $tsAdmin->clientList("-uid -away -times -groups"));
		
		foreach($clients as $client) {
				if($config['module']['movetoafk']['ignoreme'] == true) {
						$rangainfo = explode(',', $client['client_servergroups']);
						if($client['client_type'] == 0 && in_array($config['module']['movetoafk']['ignore'], $rangainfo)) {
								$nieruszaj = true;
						} else {
								$nieruszaj = false;
						}
				} else {
						$nieruszaj = false;
				}
				
				if($nieruszaj == false) {
						if($client['client_type'] == 0 && $client['cid'] != $config['module']['movetoafk']['channel']) {
								if($config['module']['movetoafk']['moveadmins'] == false) {
										$isadmin = isadmin($client['clid']);
										if($isadmin == false) {
												if(($client['client_idle_time'] >= $config['module']['movetoafk']['idletime']*1000) || ($client['client_away'] == 1)) {
														$move = true;
												} else {
														$move = false;
												}
										} else {
												$move = false;
										}
								} else {
										if(($client['client_idle_time'] >= $config['module']['movetoafk']['idletime']*1000) || ($client['client_away'] == 1)) {
												$move = true;
										} else {
												$move = false;
										}
								}
								
								if($move == true) {
										$clientchannel[$client['client_unique_identifier']] = $client['cid'];
										#$tsAdmin->execOwnCommand(0, 'clientmove clid='.$client['clid'].' cid='.$config['module']['movetoafk']['channel']);
										$tsAdmin->clientMove($client['clid'], $config['module']['movetoafk']['channel']);
								}
						} elseif($client['client_type'] == 0 && $client['cid'] == $config['module']['movetoafk']['channel']) {
								if($config['module']['movetoafk']['moveadmins'] == false) {
										$isadmin = isadmin($client['clid']);
										if($isadmin == false) {
												if(($client['client_idle_time'] < $config['module']['movetoafk']['idletime']*1000) && ($client['client_away'] != 1)) {
														$move = true;
												} else {
														$move = false;
												}
												if($client['client_idle_time'] >= $config['module']['movetoafk']['kicktime']*1000) {
														#$tsAdmin->execOwnCommand(0, 'clientpoke clid='.$client['clid'].' msg=Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
														#$tsAdmin->execOwnCommand(0, 'clientkick clid='.$client['clid'].' reasonid=5 msg=Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
														$tsAdmin->clientPoke($client['clid'], 'Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
														$tsAdmin->clientKick($client['clid'], "server", 'Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
												}
										} else {
												$move = false;
										}
								} else {
										if(($client['client_idle_time'] < $config['module']['movetoafk']['idletime']*1000) && ($client['client_away'] != 1)) {
												$move = true;
										} else {
												$move = false;
										}
										if($client['client_idle_time'] >= $config['module']['movetoafk']['kicktime']*1000) {
												#$tsAdmin->execOwnCommand(0, 'clientpoke clid='.$client['clid'].' msg=Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
												#$tsAdmin->execOwnCommand(0, 'clientkick clid='.$client['clid'].' reasonid=5 msg=Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
												$tsAdmin->clientPoke($client['clid'], 'Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
												$tsAdmin->clientKick($client['clid'], "server", 'Nieaktywność\sponad\s'.$config['module']['movetoafk']['kicktime'].'\ssekund.');
										}
								}
								
								if($move == true) {
										if(array_key_exists($client['client_unique_identifier'], $clientchannel)) {
												#$tsAdmin->execOwnCommand(0, 'clientmove clid='.$client['clid'].' cid='.$clientchannel[$client['client_unique_identifier']]);
												$tsAdmin->clientMove($client['clid'], $clientchannel[$client['client_unique_identifier']]);
												unset($clientchannel[$client['client_unique_identifier']]);
										} #Tutaj na else kickować w razie jakby na kanale mieli być tylko ludzie przerzuceni przez bota
								}
						}
				}
		}
		

}

#Funkcja autorejestracji

function autogroups() {
	
		global $tsAdmin;
		global $config;

		
		$clients = $tsAdmin->getElement('data', $tsAdmin->clientList("-groups"));
		foreach($clients as $client) {
				if($config['module']['autogroups']['autotimeregister'] == true) {
					
						$rangainfo = explode(',', $client['client_servergroups']);
						
						if($client['client_type'] == 0 && in_array($config['module']['autogroups']['guest'], $rangainfo)) {
							
								#$clientinfo = clientInfo($client['clid']);
								$clientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($client['clid']));
								if(!empty($clientinfo)) {
										if($clientinfo['connection_connected_time']/1000 >= $config['module']['autogroups']['time']) {
												$tsAdmin->serverGroupAddClient($config['module']['autogroups']['group'], $clientinfo['client_database_id']);
										}
								}
						}
				}
				if($config['module']['autogroups']['autochannelgroups'] == true) {
						if($client['client_type'] == 0) {
								foreach($config['module']['autogroups']['channels'] as $channel => $group) {
										if($client['cid'] == $channel) {
												$rangainfo = explode(',', $client['client_servergroups']);
												if(!in_array($group, $rangainfo)) {
														#$clientinfo = clientInfo($client['clid']);
														$clientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($client['clid']));
														if(!empty($clientinfo)) {
																$tsAdmin->serverGroupAddClient($group, $clientinfo['client_database_id']);
														}
												}
										}
								}
						}
				}
		}
		

}

?>
