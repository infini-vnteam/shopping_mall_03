$(function () {
    const home = new Vue({
        el: '#home',
        data: {
            sale_programs: [],
            num_sales_per_page: 3,
        },
        mounted: function () {
            let width = $(window).width();
            if (width < 640) {
                this.num_sales_per_page = 1;
            } else if (width < 950) {
                this.num_sales_per_page = 2;
            } else {
                this.num_sales_per_page = 3;
            }
            this.fetchSale();
        },
        methods: {
            fetchSale: function () {
                axios.get('/sale/getSales')
                    .then(res => {
                        let data = res.data;
                        this.formatData(data);
                    })
                    .catch(err => {

                    });
            },
            formatData: function (data) {
                data.forEach((ele, idx) => {
                    if (ele.products.length > 2) {
                        data[idx].products = ele.products.slice(0, 2);
                    }
                });
                const format_data = [];
                for (let i = 0; i < data.length; i += this.num_sales_per_page) {
                    format_data.push(data.slice(i, i + this.num_sales_per_page));
                }
                this.sale_programs = format_data;
            },
            viewDetails: function (id) {
                location.href = '/sale/view/' + id;
            },
        },
    });
});
