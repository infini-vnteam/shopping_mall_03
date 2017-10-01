/**
 * Created by isling on 12/09/2017.
 */

$(function () {
    let config = {
        el: '#checkout',
        data: {
            login: false,

            is_phone_number: true,

            ship_add: {},

            sub_total: Cart.cart.subTotalPrice,
            ship_tax: 20,

            success: false,
            order_id: '',
        },
        mounted: function () {
            this.fetchShipAddress();
            this.fetchProvince();
        },
        methods: {
            fetchShipAddress: function () {
                axios.get('/user/getinfo')
                    .then(res => {
                        if (res.data.login) {
                            this.login = true;
                            this.ship_add = res.data.user;

                            if (this.ship_add.province) {
                                this.selected_state = this.ship_add.province;
                                this.selectState();
                            }

                            this.selected_dist = this.ship_add.district ? this.ship_add.district : '';
                        } else {
                            this.login = false;
                        }
                        this.selected_dist = this.ship_add.dist;
                    })
                    .catch(err => {
                    });
            },
            // selectState: function () {
            //     this.districts = address[this.selected_state];
            // },
            checkout: function () {
                this.ship_add.province = this.selected_state;
                this.ship_add.dist = this.selected_dist;

                let data = {
                    ship_add: this.ship_add,
                    sub_total: this.sub_total,
                    ship_tax: this.ship_tax,
                };

                if (!this.validateData(data.ship_add)) {
                    Snackbar.pushMessage('You must fill all information.', 'warning');
                    return;
                }
                
                // post data and receive order_id
                axios.post('/cart/checkout', data)
                    .then(res => {
                        // show success page
                        if (res.data.status === 'ok') {
                            this.success = true;
                            this.order_id = res.data.order_id;
                        }
                    })
                    .catch(error => {
                    });
            },
            validateData: function (data) {
                let valid = {
                    full_name: 'required',
                    phone_number: 'required',
                    email: 'required',
                    province: 'required',
                    dist: 'required',
                    address: 'required',
                };

                let result = true;

                Object.keys(valid).forEach(key => {
                    if (valid[key] === 'required') {
                        if (!data[key] || data[key] === '') {
                            result = false;
                        }
                    }
                });

                return result;
            },
        },
    };

    Object.assign(config.data, Address.data);
    Object.assign(config.methods, Address.methods);

    const checkout = new Vue(config);
});
