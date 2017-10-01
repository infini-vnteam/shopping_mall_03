$(document).ready(function () {

    var socket = io.connect('http://shopping.app:8056/');

    function emitMessage(message) {
        console.log(socket);
        console.log(message);
        socket.emit('new message', message);
    }

    socket.on('new message', msg => {
        app.messages.push(msg);
        console.log(msg);
    });

    var app = new Vue({
        el: '#app',
        data: {
            messages: [],
            text: '',
            user: '',
            isChatBoxClosed: true,
            chatrooms: [],
            current_chatroom: '',
            current_chatting_user: 'Chat',
            isLogedIn: false,
        },
        created: function () {
            this.getCurrentUser();
        },

        methods: {
            getMessages: function () {
                axios.get('/getmessages', {id: this.user.id})
                    .then(response => {
                        this.messages = response.data.messages;
                        this.current_chatroom = response.data.chatroom_id
                    })
            },
            getChatrooms: function () {
                axios.get('/getchatrooms')
                    .then(response => {
                        this.chatrooms = response.data;
                    })
            },
            sendMessage: function () {
                if (this.text.trim() === '') {
                    return;
                }
                message = {
                    sender_id: this.user.id,
                    content: this.text,
                    chatroom_id: this.current_chatroom
                };

                emitMessage(message);

                $.post("/sendmessage", {
                    sender_id: this.user.id,
                    content: this.text,
                    chatroom_id: this.current_chatroom
                }, function (data, status) {

                });
                this.text = '';

            },
            getCurrentUser: function () {
                axios.get('/getCurrentUser')
                    .then(response => {
                        console.log(response.data.status);

                        if (response.data.status !== 0) {
                            this.user = response.data.current_user;

                            if (this.user.id === "1")
                                this.getChatrooms();
                            else {
                                this.isLogedIn = true;
                                this.getMessages();
                            }

                        }
                    });

            },

            closeChatbox: function () {
                this.isChatBoxClosed = true;
            },
            openChatbox: function () {
                this.isChatBoxClosed = false;
            },

            openChatroom: function (id) {
                cr = this.chatrooms.find(c => c.id === id);
                this.messages = cr.messages;
                this.current_chatroom = id;
                this.current_chatting_user = cr.user.fullname;
                this.openChatbox();
            }
        }
    });
});
