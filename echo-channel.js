require('dotenv').config();

const server = require('http').Server();

const io = require('socket.io')(server);

const Redis = require('ioredis');

const redis = new Redis();

redis.subscribe('all-channel');

console.log(process.env.SOCKET_PORT);

redis.on('message', function (channel, message) {
    const event = JSON.parse(message);
    console.log(event);
    io.emit(event.event, channel, event.data);
});

server.listen({
    port: process.env.SOCKET_PORT
});