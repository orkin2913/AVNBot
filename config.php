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

#######
# 
# Konfiguracja logowania
#
#######

$config['query']['login'] = 'serveradmin';
$config['query']['password'] = '2m4U4ptu'; #ServerQuery password

$config['server']['ip'] = '127.0.0.1';
$config['server']['port'] = 9987; #Server port
$config['server']['queryport'] = 10011; #Server query port

#######
# 
# Konfiguracja bota
#
#######

$config['bot']['path'] = '/home/bottest/avnbot/'; #Absolute path to bot directory
$config['bot']['nickname'] = 'AVNBot'; #Bots name
$config['bot']['channel'] = 2; #Bots channel
$config['bot']['admingroups'] = '2,6'; #ID grup administracyjnych
$config['bot']['version'] = '1.0.0'; #Wersja bota

#######
# 
# Konfiguracja modułów
#
#######

$config['module']['pokebot']['enable'] = true; #Pokebot
$config['module']['nickprotect']['enable'] = true; #Ochrona nicków
$config['module']['nickabuse']['enable'] = true; #Wyszukiwanie nadużyć w nickach
$config['module']['channelcheck']['enable'] = true; #Sprawdzanie kanałów
$config['module']['autoregister']['enable'] = true; #Automatyczna rejestracja
$config['module']['channelgroups']['enable'] = true; #Ranga po wejściu na dany kanał
$config['module']['commands']['enable'] = true; #Komendy bota


#######
# 
# Konfiguracja wiadomości powitalnej
#
#######

$config['module']['welcomemsg'] = array(
											'enable' => true, #Włączyć? true / false
											'mode' => 0, #0 - do wszystkich // 1 - pomiń adminów // 2 - tylko nowi użytkownicy
											'message' => 'Witaj %CLIENT_NICKNAME%! Ostatni raz byłeś u nas %CLIENT_LASTCONNECTED%. Połączyłeś się z serwerem %CLIENT_TOTALCONNECTIONS% razy. Twoje IP: %CLIENT_IP%, Twoje unikalne ID: %CLIENT_UNIQUE_ID%' #Treść wiadomości powitalnej
);


#######
# 
# Konfiguracja rekordu użytkowników
#
#######

$config['module']['userecord'] = array(
											'enable' => true, #Włączyć? true / false
											'channel' => 5, #ID kanału na którym będzie aktualny rekord użytkowników
											'spacer' => '[cspacer69]', #Spacer jakiego bot ma użyć w nazwie kanału
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał sprawdzania rekordu. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 1
											)
);



#######
# 
# Konfiguracja sprawdzania adminów online
#
#######

$config['module']['adminsonline'] = array( #Admini online
											'enable' => true, #Włączyć? true / false
											'channel' => 6, #ID kanału na którym będzie status adminów online
											'groupsize' => 16, #Wielkość nazw grup
											'nicksize' => 12, #Wielkość nicków
											'statusize' => 8, #Wielkość statusu online/offline
											'groupcolor' => '#aa0000', #Kolor nazw grup w RGB
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał sprawdzania adminów online. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 5
											)	
);



#######
# 
# Konfiguracja auto wiadomości
#
#######

$config['module']['automsg'] = array( #Automatyczna wiadomość na czacie głównym
											'enable' => true, #Włączyć? true / false
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał wysyłania waidomości na czacie głównym. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 1
											)	
);



#######
# 
# Konfiguracja AFK Bota
#
#######

$config['module']['movetoafk'] = array( #Przenoszenie na kanał AFK
											'enable' => true, #Włączyć? true / false
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał przenoszenia z i do kanału AFK. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 1
											)
);



#######
# 
# Konfiguracja controlbota
#
#######

$config['module']['controlbot'] = array( #Sprawdzanie stanu bota
											'enable' => true, #Włączyć? true / false
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał sprawdzania działania bota. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 1
											)		
);

?>
