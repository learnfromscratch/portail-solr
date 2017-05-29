var chokidar = require('chokidar');
 
// One-liner for current directory, ignores .dotfiles 
/*
chokidar.watch('../Articles', {ignored: /[\/\\]\./}).on('all', (event, path) => {
   console.log(event, path);
 });
 */

var watcher = chokidar.watch('../Articles', {
	  ignored: /[\/\\]\./,
	    persistent: true
});
 
// server.js
require('dotenv').config();

const server = require('http').Server();

const io = require('socket.io')(server);

const Redis = require('ioredis');

const redis = new Redis();

 watcher.on('change', function(path){

	 console.log(path);
	 redis.publish('test-channel', path);

});

//redis.subscribe('all-channel');

console.log(process.env.SOCKET_PORT);

redis.on('message', function (channel, message) {
    const event = JSON.parse(message);
    io.emit(event.event, channel, event.data);
});

server.listen({
    port: process.env.SOCKET_PORT
});