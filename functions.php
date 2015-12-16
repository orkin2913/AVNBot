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


#mode 0 - unikalne id // mode 1 - client id // data - unikalne id lub client id
#Zwraca true albo false w zależności czy jest adminem

function isadmin($mode, $data) {
		
		global $tsAdmin;
		global $config;
		
		if($mode == 1) {
				#Sprawdzanie grup serwerowych każdego clienta
				$thisclientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($data));
				$thisclientinfo['groups'] = explode(',', $thisclientinfo['client_servergroups']);
				$thisclientinfo['isadmin'] = false;
				
				$admingroups = explode(',', $config['bot']['admingroups']);
				
				foreach($thisclientinfo['groups'] as $group) {
						foreach($admingroups as $admingroup) {
								#Jeżeli client ma grupę administracyją, to dostaje status administratora
								if($group == $admingroup) {
										$thisclientinfo['isadmin'] = true;
								}
						}
				}
		}
		
		return $thisclientinfo['isadmin'];
}



#Zwraca tablicę z listą clid wszystkich clientów na serwerze

function listaclientow() {
		
		global $tsAdmin;
		
		#Zbieranie listy userów
		$clients['all'] = $tsAdmin->getElement('data', $tsAdmin->clientList());
		
		#Zbieranie danych o użytkownikach
		$clients['recent'] = array();
		foreach($clients['all'] as $client) {
				array_push($clients['recent'], $client['clid']);
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
		
		$version = file_get_contents('https://raw.githubusercontent.com/orkin2913/AVNBot/master/version');
		
		return $version;
}

#clid - id clienta na serwerze
#Zwraca true albo false w zależności czy client połączył się po raz pierwszy

function clientisnew($clid) {
		
		global $tsAdmin;
		
		$thisclientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($clid));
		if($thisclientinfo['client_totalconnections'] == 1)  {
				$isnew = true;
		} else {
				$isnew = false;
		}
		
		return $isnew;
}

#msg - wiadomość do przerobienia // clid - id clienta do którego będzie wysłana wiadomość
#Zwraca wiadomość z zamienionymi znacznikami %foobar% na dane z funkcji

function codeinmsg($msg, $clid) {
		
		global $tsAdmin;
		
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
				
				10 => array(0 => '%CLIENT_TOTALCONNECTIONS%', 1 => $clientinfo['client_totalconnections'])
				
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
		
		global $tsAdmin;
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
		
		global $tsAdmin;
		global $config;
		
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
		
		$clients['new'] = listaclientow();

		#Porównywanie czy doszedł ktoś nowy
		$clients['diff'] = array_diff($clients['new'], $clients['aktualnie']);
		
		#Sprawdzanie nowych użytkowników
		if(!count($clients['diff']) == 0) {
				foreach($clients['diff'] as $clientID) {
					
						$msgtosend = codeinmsg($config['module']['welcomemsg']['message'], $clientID);
						
						#Sprawdzanie czy bot ma wysyłać tylko do zwykłych clientów
						if($config['module']['welcomemsg']['mode'] == 1) {
								
								$isadmin = isadmin(1, $clientID);
								
								#Jeżeli koleś nie jest adminem, to wyślij wiadomość
								if(!$isadmin == true) {
										$tsAdmin->sendMessage(1, $clientID, $msgtosend);
								}
						} elseif($config['module']['welcomemsg']['mode'] == 0) {
							
								#Jeżeli ma wysyłać do wszystkich, to wyślij bez względu na rangę
								$tsAdmin->sendMessage(1, $clientID, $msgtosend);
								
						} elseif($config['module']['welcomemsg']['mode'] == 2) {
								
								$isnew = clientisnew($clientID);
								
								if($isnew == true) {
									
										#Jeżeli nowy, to wyślij
										$tsAdmin->sendMessage(1, $clientID, $msgtosend);
								}
						}
				}
		}
		
		#Przygotowywanie do następnej pętli
		$clients['aktualnie'] = $clients['new'];
}



#Funkcja rekordu userów

function userecord() {
		
		global $tsAdmin;
		global $config;
		global $clients;
		
		$clients['count'] = $tsAdmin->getElement('data', $tsAdmin->serverInfo());
		$clients['recent'] = $clients['count']['virtualserver_clientsonline'] - $clients['count']['virtualserver_queryclientsonline'];
		
		$channel['info'] = $tsAdmin->getElement('data', $tsAdmin->channelInfo($config['module']['userecord']['channel']));
		$channel['check'] = explode(']', $channel['info']['channel_name']);
		
		if($clients['recent'] > $clients['record']) {

				if($channel['check'][1] != $clients['recent']) {
						$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $config['module']['userecord']['spacer'] . $clients['recent']));
				}
				file_put_contents('tmp/userecord.txt', $clients['recent']);
				$clients['record'] = $clients['recent'];
		} elseif($clients['record'] > $channel['check'][1] || $clients['record'] < $channel['check'][1]) {
			
				$tsAdmin->channelEdit($config['module']['userecord']['channel'], array('channel_name' => $config['module']['userecord']['spacer'] . $clients['record']));
		}
}


#Funkcja admini online

function adminsonline() {
		
		global $tsAdmin;
		global $config;
		global $clients;
		
		
		$admingroups = explode(',', $config['bot']['admingroups']);
		$channeldesc = '[CENTER]';
		
		foreach($admingroups as $group) {
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

?>
