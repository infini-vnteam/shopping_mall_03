$(function () {
    const sale = new Vue({
        el: '#sale-details',
        data: {
            sale: {},
        },
        mounted: function () {
            this.fetchSale();
        },
        methods: {
            fetchSale: function () {
                let url = location.href;
                let id = url.split('/').pop();

                axios.get('/sale/getSale/' + id)
                    .then(res => {
                        this.sale = res.data;
                    })
                    .catch(err => {

                    });
            },
        },
    });
});
