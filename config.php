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

$config['bot']['nickname'] = 'AVNBot'; #Bots name
$config['bot']['channel'] = 2; #Bots channel
$config['bot']['admingroups'] = array(2, 6); #ID grup administracyjnych (po przecinku)
$config['bot']['technician'] = array(2); #ID grup technicznych (po przecinku). Będą mogli oni zarządzać botem; będą oni również otrzymywać powiadomienia w razie problemów z botem. (wymagane permissie: i_client_private_textmessage_power == 100 oraz i_client_serverquery_view_power == 100)
$config['bot']['speed'] = 3; #Prędkość bota (od 1 do ∞). Im mniej tym szybsze działanie bota (10 - sekunda // 1 - 1/10 sekundy). Jeżeli maszyna laguje Ci przez bota, to zwiększ tę wartość (bot może działać z opóźnieniem)
$config['bot']['version'] = '1.1.0'; #Wersja bota

#######
# 
# Konfiguracja modułów
#
#######

$config['module']['pokebot']['enable'] = true; #Pokebot
$config['module']['nickprotect']['enable'] = true; #Ochrona nicków
$config['module']['nickabuse']['enable'] = true; #Wyszukiwanie nadużyć w nickach
$config['module']['channelcheck']['enable'] = true; #Sprawdzanie kanałów



#######
# 
# Konfiguracja komend
#
#######

$config['module']['commands'] = array(
											'enable' => false,
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
# Konfiguracja wiadomości powitalnej
#
#######

$config['module']['welcomemsg'] = array(
											'enable' => true, #Włączyć? true / false
											'mode' => 0, #0 - do wszystkich // 1 - pomiń adminów // 2 - tylko nowi użytkownicy
											'message' =>  file_get_contents('msg.txt') #Treść wiadomości powitalnej w pliku msg.txt. *Tego nie dotykać :)*
);


#######
# 
# Konfiguracja rekordu użytkowników
#
#######

$config['module']['userecord'] = array(
											'enable' => true, #Włączyć? true / false
											'channel' => 5, #ID kanału na którym będzie aktualny rekord użytkowników
											'name' => '[cspacer55]Rekord online: ', #Nazwa kanału, do której dopisywana będzie liczba rekordu
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
# Konfiguracja użytkowników online
#
#######

$config['module']['useronline'] = array(
											'enable' => true, #Włączyć? true / false
											'channel' => 8, #ID kanału na którym będzie aktualny status użytkowników
											'name' => '[cspacer55]Aktualnie online: ', #Nazwa kanału, do której dopisywana będzie liczba online
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał sprawdzania ilości userów. Co ile ma wykonywać akcję
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
											'message' => file_get_contents('servermsg.txt'), #Wiadomość wysyłana na czat główny w pliku servermsg.txt
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał wysyłania waidomości na czacie głównym. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 10,
															'seconds' => 0
											)	
);



#######
# 
# Konfiguracja auto rang
#
#######

$config['module']['autogroups'] = array( #Automatyczna rejestracja / przyznawanie reng
											'enable' => true, #Włączyć? true / false
											'autotimeregister' => true, #Włączyć automatyczną rejestrację po czasie spędzonym na serwerze? true / false
											'autochannelgroups' => true, #Włączyć automatyczną rejestrację po wejściu na kanał? true / false
											'time' => 60, #Czas (w sekundach) spędzony na serwerze potrzebny do autorejestracji
											'group' => 7, #Ranga przyznawana po czasie
											'guest' => 8, #ID serwerowej grupy domyślnej (Guest). Jeżeli nic nie grzebałeś jako query, to domyślnie 8.
											'channels' => array( #Lista kanałów na których przyznawane będą rangi oraz rangi, które będą na nich przyznawane
															10 => 9, #(ID Kanału) => (ID Rangi)
															11 => 10
											)
);



#######
# 
# Konfiguracja AFK Bota
#
#######

$config['module']['movetoafk'] = array( #Przenoszenie na kanał AFK
											'enable' => true, #Włączyć? true / false
											'moveadmins' => true, #Czy przenosić również adminów? true/flase
											'kickme' => true, #Czy bot ma kickować po jakimś czasie z serwera za zajmowanie slota? true / false
											'ignoreme' => true, #Czy dodać wyjątek do bota? Nie będzie przenoszony na kanał AFK. true / false
											'ignore' => 11, #ID rangi którą ma ignorować bot bez względu na wszystko (np. ranga MusicBota)
											'kicktime' => 80, #Czas nieaktywności (w sekundach) po jakim bot wyrzuci usera za AFK
											'channel' => 9, #ID kanału AFK
											'idletime' => 60, #Czas nieaktywności (w sekundach) po jakim bot ma przenosić
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
# Konfiguracja Rangi Dostępowej
#
#######

$config['module']['accessgroup'] = array( #Przenoszenie na kanał AFK
											'enable' => true, #Włączyć? true / false
											'blockgroup' => 9, #ID kanałowej rangi która będzie blokować wstęp. Wymagane permissie i_channel_join_power == -999
											'guestgroup' => 8, #ID domyślnej kanałowej rangi. Zwykle 8
											'channels' => array( #Lista kanałów na których wstęp mają tylko określone rangi
															10 => 9, #(ID Kanału) => (ID Serwerowej rangi dostępowej)
															11 => 10
											),
											'datazero' => '1970-01-01 00:00:00', #Data zerowa. Przy uruchomieniu bota akcja wykona się raz bez czekania
											'interval' => array( #Interwał sprawdzania kto ma dostęp do kanałów. Co ile ma wykonywać akcję
															'days' => 0,
															'hours' => 0,
															'minutes' => 0,
															'seconds' => 10
											)
);


?>
