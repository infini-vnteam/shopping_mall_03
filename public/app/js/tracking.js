$(function () {
    const order = new Vue({
        el: '#track-order',
        data: {
            track_form: true,
            order_id: '',
            email: '',
            order: {},
        },
        methods: {
            tracking: function () {
                let hasError = !this.order_id
                    || !this.email
                    || this.errors.has('email')
                    || this.errors.has('order id');
                if (hasError) {
                    Snackbar.pushMessage('You must fill email and phone number');
                } else {
                    this.fetchOrder();
                }
            },
            continueTrack: function () {
                this.track_form = true;
            },
            fetchOrder() {
                axios.post('/order/track', {
                    order_id: this.order_id,
                    email: this.email,
                })
                    .then(res => {
                        this.order = res.data;
                        this.track_form = false;
                    })
                    .catch(err => {

                    });
            },
        },
    });
});
