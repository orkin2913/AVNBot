<?php

#######
# 
# Konfiguracja logowania
#
#######

$config['query']['login'] = 'serveradmin';
$config['query']['password'] = ''; #ServerQuery password

$config['server']['ip'] = '127.0.0.1';
$config['server']['port'] = 9987; #Server port
$config['server']['queryport'] = 10011; #Server query port

#######
# 
# Konfiguracja bota
#
#######

$config['bot']['path'] = '/root/bot0new/'; #Absolute path to bot directory
$config['bot']['nickname'] = 'AVNBot'; #Bots name
$config['bot']['channel'] = 2; #Bots channel
$config['bot']['admingroups'] = '2,6'; #Bots channel

#######
# 
# Konfiguracja modułów
#
#######

$config['module']['pokebot']['enable'] = true; #Pokebot
$config['module']['userecord']['enable'] = true; #Rekord użytkowników
$config['module']['welcomemsg']['enable'] = true; #Wiadomość powitalna
$config['module']['movetoafk']['enable'] = true; #Przenoszenie na kanał AFK
$config['module']['nickprotect']['enable'] = true; #Ochrona nicków
$config['module']['nickabuse']['enable'] = true; #Wyszukiwanie nadużyć w nickach
$config['module']['channelcheck']['enable'] = true; #Sprawdzanie kanałów
$config['module']['autoregister']['enable'] = true; #Automatyczna rejestracja
$config['module']['adminsonline']['enable'] = true; #Administratorzy online
$config['module']['automsg']['enable'] = true; #Automatyczna wiadomość na czacie głównym
$config['module']['channelgroups']['enable'] = true; #Ranga po wejściu na dany kanał
$config['module']['commands']['enable'] = true; #Komendy bota
$config['module']['controlbot']['enable'] = true; #Bot kontrolny

#######
# 
# Konfiguracja wiadomości powitalnej
#
#######

$config['module']['welcomemsg']['mode'] = 0; #0 - do wszystkich // 1 - pomiń adminów // 2 - tylko nowi użytkownicy
$config['module']['welcomemsg']['message'] = 'Witaj %nick%! Ostatni raz byłeś u nas %lastconnected%. Połączyłeś się z serwerem %totalconnections% razy. Twoje IP: %ip%, Twoje unikalne ID: %uid%'; #Treść wiadomości powitalnej

#######
# 
# Konfiguracja rekordu użytkowników
#
#######

$config['module']['userecord']['channel'] = 3; #ID kanału na którym będzie aktualny rekord użytkowników
$config['module']['userecord']['spacer'] = '[cspacer69]'; #Spacer jakiego bot ma użyć w nazwie kanału


?>
