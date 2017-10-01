let Order;
$(function () {
    Order = new Vue({
        el: '#order',
        data: {
            orders: [],
        },
        mounted: function () {
            this.fetchOrderList();
        },
        methods: {
            fetchOrderList: function () {
                axios.get('/order/getOrders')
                    .then(res => {
                        this.orders = res.data;
                    })
                    .catch();
            },
        },
    });
});
