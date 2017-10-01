$(document).ready(function () {

    var socket = io.connect('http://shopping.app:8056/');

    function emitMessage(message) {
        socket.emit('new message', message);
    }

    socket.on('new message', msg => {
        app.messages.push(msg);
    });

    var app = new Vue({
        el: '#app',
        data: {
            messages: [],
            text: '',
            user: '',
            isChatBoxClosed: false,
            chatlist: [],
            current_chatroom: '',
            current_chatting_user: 'Chat',
            isInited: true,
            isChatListClosed: false,
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
                        this.chatlist = response.data;
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
                        this.user = response.data;
                        if (response.data !== '')
                        {
                            this.getChatrooms();

                        }
                    });

            },

            closeChatbox: function () {
                this.isChatBoxClosed = true;
            },
            openChatbox: function () {
                this.isChatBoxClosed = false;
            },
            closeChatList: function () {
                this.isChatListClosed = true;
            },
            openChatList: function () {
                this.isChatListClosed = false;
            },

            openChatroom: function (id) {
                cr = this.chatlist.find(c => c.id === id);
                this.messages = cr.messages;
                this.current_chatroom = id;
                this.current_chatting_user = cr.user.name;
                this.openChatbox();
            }
        }
    });
});
