/**
 * Created by isling on 24/09/2017.
 */
 
const COLORS = [
    'PURPLE',
    'BLACK',
    'BLUE',
    'GREEN',
    'ORANGE',
    'GREY',
    'RED',
    'WHITE',
    'YELLOW',
];

$(function () {
    let category = new Vue({
        el: '#category',
        data: {
            colors: COLORS.map(ele => {
                return ele.toLowerCase();
            }),
            filter: {
                color: '',
                price_gte: '',
                price_lte: '',
            },
            sort: '',
        },
        computed: {
            truePriceLte: function () {
                if (this.filter.price_gte && this.filter.price_lte) {
                    let gte = parseInt(this.filter.price_gte);
                    let lte = parseInt(this.filter.price_lte);

                    if (lte < gte) return false;
                }
                return true;
            },
        },
        mounted: function () {
            this.initFilter();
        },
        methods: {
            clearFilter: function () {
                this.filter = {
                    color: '',
                    price_gte: '',
                    price_lte: '',
                };
                let url = location.href;
                url = url.split('?').shift();
                location.href = url;
            },
            changeFilter: function () {
                let url = location.href;
                url = url.split('?').shift();
                let query = '?';
                if (this.filter.color) {
                    query += 'color=' + this.filter.color + '&';
                }
                if (this.filter.price_gte && this.filter.price_lte) {
                    let gte = parseInt(this.filter.price_gte);
                    let lte = parseInt(this.filter.price_lte);

                    if (lte < gte) {
                        Snackbar.pushMessage('Price 2 must be greater than or equal to price 1', 'danger');
                        return;
                    }
                }
                if (this.filter.price_gte) {
                    query += 'price_gte=' + this.filter.price_gte + '&';
                }
                if (this.filter.price_lte) {
                    query += 'price_lte=' + this.filter.price_lte + '&';
                }
                if (this.sort) {
                    query += 'sort=' + this.sort + '&';
                }
                query = query.slice(0, query.length - 1);
                location.href = url + query;
            },
            selectColor: function (name) {
                name = name.toLowerCase();
                if (name === this.filter.color.toLowerCase()) {
                    this.filter.color = '';
                } else {
                    this.filter.color = name;
                }
                this.changeFilter();
            },
            initFilter: function () {
                let category = this;
                let url = location.href;
                let query = url.split('?').pop();
                let arr = query.split('&');
                arr.forEach(ele => {
                    ele = ele.split('=');
                    if (ele[0] !== 'sort') {
                        category.filter[ele[0]] = ele[1];
                    } else {
                        category.sort = ele[1];
                    }
                });
            },
        },
    });
});
