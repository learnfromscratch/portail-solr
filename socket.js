// server.js
//require('dotenv').config();
var chokidar = require('chokidar');

var watcher = chokidar.watch('./public/Articles', {
	  ignored: /[\/\\]\./,
	    persistent: true
});
//const server = require('http').Server();

//const io = require('socket.io')(server);

const Redis = require('ioredis');

const redis = new Redis();

 watcher.on('add', function(path){
	 console.log(path);
	 redis.publish('test-channel', path);

});





