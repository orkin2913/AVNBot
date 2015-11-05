<?php

#mode 0 - unikalne id // mode 1 - client id // data - unikalne id lub client id
#Zwraca true albo false w zależności czy jest adminem

function isadmin($mode, $data){
		
		global $tsAdmin;
		global $config;
		
		if($mode == 1) {
				#Sprawdzanie grup serwerowych każdego clienta
				$thisclientinfo['data'] = $tsAdmin->getElement('data', $tsAdmin->clientInfo($data));
				$thisclientinfo['groups'] = explode(',', $thisclientinfo['data']['client_servergroups']);
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

function clientlist(){
		
		global $tsAdmin;
		global $config;
		
		#Zbieranie listy userów
		$clients['all'] = $tsAdmin->getElement('data', $tsAdmin->clientList());
		
		#Zbieranie danych o użytkownikach
		$clients['recent'] = array();
		foreach($clients['all'] as $client) {
				array_push($clients['recent'], $client['clid']);
		}
		
		return $clients['recent'];
}



#clid - id clienta na serwerze
#Zwraca true albo false w zależności czy client połączył się po raz pierwszy

function clientisnew($clid){
		
		global $tsAdmin;
		global $config;
		
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

function codeinmsg($msg, $clid){
		
		global $tsAdmin;
		global $config;
		
		$clientinfo = $tsAdmin->getElement('data', $tsAdmin->clientInfo($clid));
		
		$tags = array(
				
				0 => array(0 => '%ip%', 1 => $clientinfo['connection_client_ip']),
				
				1 => array(0 => '%uid%', 1 => $clientinfo['client_unique_identifier']),
				
				2 => array(0 => '%nick%', 1 => $clientinfo['client_nickname']),
				
				3 => array(0 => '%lastconnected%', 1 => date("d-m-Y", $clientinfo['client_lastconnected'])),
				
				4 => array(0 => '%totalconnections%', 1 => $clientinfo['client_totalconnections'])
				
		);
		
		foreach($tags as $tag) {
				$msg = str_replace($tag[0], $tag[1], $msg);
		}

		return $msg;
}

?>
