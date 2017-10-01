var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);


app.get('/', function(resquest, responce) {
    responce.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket) {
    socket.on('new message', function(message){
        console.log('New message sent: ' + message.content);
        io.emit('new message', message);
    });

});

server.listen(8056, function(){
    console.log('listening on *:8056');
});
