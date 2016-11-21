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

function main(avn, config, lang) {
	this.onStart = function() {
		console.log(config.addon.name);
		console.log(lang['running']);
		console.log(lang['example1']);
		console.log(lang[1]);
	}
	this.onClientConnect = function(client) {
		console.log('connect');
	}
	this.onClientLeft = function(client) {
		console.log('left');
	}
	this.onClientKick = function(data) {
		console.log('kick');
	}
	this.onClientBan = function(data) {
		console.log('ban');
	}
	this.onClientChannelSwitch = function(data) {
		console.log('switch');
	}
	this.onPM = function(data) {
		console.log('pm');
	}
	this.onChannelMessage = function(data) {
		console.log('channelmessage');
	}

}

module.exports = main;