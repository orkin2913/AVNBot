/*
	AVNBot
	Copyright (C) 2016  Orkin (AVNTeam.net)

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

var fs = require('fs'),
	compareVersions = require('compare-versions'),
	botClass = require('./lib/AVNBot.class.js'),
	Addons = require('./lib/Addons.class.js'),
	configFile = 'config.yml';

//Ładowanie wybranej konfiguracji jeżeli wystąpił argument startowy
if (typeof process.argv[2] !== 'undefined') {
	configFile = process.argv[2] + '.yml';
}
//Sprawdzanie czy istnieje plik
try {
	var config = require('js-yaml').safeLoad(fs.readFileSync(`config/${configFile}`, 'utf8')),
	lang = JSON.parse(fs.readFileSync('lang/lang.'+config.bot.lang+'.json', 'UTF8'));
} catch (err) {
	console.log(err);
	process.exit();
}

//Tworzenie obiektu bota
var avn = new botClass(config);
avn.sendLog(['console'], {type: 'ok', msg: `${lang[5]}: ${configFile}`});

var addons = new Addons(avn, lang, config.bot.addons, configFile );
addons.loadAll();


//Właściwa część bota
avn.login(function() {
	//Ustawianie nicku
	avn.bot.setNick(config.bot.nick);
	avn.bot.move(config.bot.channel);
	avn.pingPong();
	
	//Ładowanie startowej funkcji z addonów
	setTimeout(function() { addons.onStart(); }, 100);
	
	setTimeout(function() { avn.checkUpdates(); }, 1000);

/**
  * Nasłuchiwanie na wszystkich kanałach
  * 
  */
	avn.listenOnAllChannels(function() {
	/**
	  * Nasłuchiwanie na PW
	  * 
	  */
		avn.listenOnPM(function() {
		/**
		  * Nasłuchiwanie na czacie kanałowym
		  * 
		  */
			avn.listenOnChannelMessage(function() {
				
			/**
			  * Jeżeli client wejdzie na serwer
			  * 
			  */
				avn.client.onConnect(function(client) {
					avn.welcomeMessage.onConnect(client);
					avn.statusOnline.onConnect();
					addons.onClientConnect(client);
				});
				
			/**
			  * Jeżeli client wyjdzie z serwera
			  * 
			  */
				avn.client.onLeft(function(client) {
					avn.pokebot.onLeft(client);
					avn.statusOnline.onLeft();
					addons.onClientLeft(client);
				});
				
			/**
			  * Jeżeli client zostanie wyrzucony z serwera
			  * 
			  */
				avn.client.onKick(function(data) {
					avn.pokebot.onLeft(data);
					avn.statusOnline.onLeft();
					addons.onClientKick(data);
				});
				
			/**
			  * Jeżeli client zostanie zbanowany
			  * 
			  */
				avn.client.onBan(function(data) {
					avn.pokebot.onLeft(data);
					avn.statusOnline.onLeft();
					addons.onClientBan(data);
				});
				
			/**
			  * Jeżeli client zmieni kanał
			  * 
			  */
				avn.client.onChannelSwitch(function(data) {
					avn.pokebot.onEnter(data);
					avn.pokebot.onLeft(data);
					addons.onClientChannelSwitch(data);
				});
				
			/**
			  * Jeżeli na czacie kanałowym pojawi się wiadomość
			  * 
			  */
				avn.bot.onChannelMessage(function(data) {
					addons.onChannelMessage(data);
				});
				
			/**
			  * Jeżeli bot otrzyma PW
			  * 
			  */
				avn.bot.onPM(function(data) {
					addons.onPM(data);
				});
				
			});
		});
	});
});

