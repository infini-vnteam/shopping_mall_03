$(function () {
    // let socket = io.connect('http://shopping.app:8056/');
    //
    // function emitMessage(message) {
    //     console.log(socket);
    //     console.log(message);
    //     socket.emit('new message', message);
    // }
    //
    // socket.on('new message', msg => {
    //     app.messages.push(msg);
    //     console.log(msg);
    // });
    //
    // socket.on('new message', msg => {
    //     app.messages.push(msg);
    //     console.log(msg);
    // });

    const notification = new Vue({
        el: '#notification',
        data: {
            raw_notice: [],
            show_notice: [],
            num_unread: 0,
            num_per_page: 5,
            page: 0,
        },
        computed: {
            has_new_notice: function () {
                return this.num_unread > 0;
            },
        },
        mounted: function () {
            this.fetchNotify();
            //setInterval(this.fetchNotify, 10000);
        },
        methods: {
            fetchNotify: function () {
                axios.get('/loadNotification')
                    .then(res => {
                        this.num_unread = res.data.unread_count;
                        this.raw_notice = Object.values(res.data);
                        this.showNotice();
                    })
                    .catch(err => {
                    });
            },
            showNotice: function () {
                this.show_notice = this.raw_notice.slice(
                    0, (this.page + 1) * this.num_per_page,
                );
            },
            seeMoreNotice: function () {
                this.page++;
                this.showNotice();
            }
        },
    });
});
