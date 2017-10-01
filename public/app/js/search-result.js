let Search_result;

$(function () {
    Search_result = new Vue({
        el: '#search-result',
        data: {
            show_result: false,
            results: [],
        },
        computed: {
            keyword: function () {
                return Search.keyword;
            }
        },
        methods: {
            join: function (arr, char) {
                return arr.join(char);
            },
            getCurrentPrice: function (pro) {
                if (pro.sale && pro.sale > 0) {
                    return Math.round(parseInt(pro.price) / 100.0 * parseInt(pro.sale));
                } else {
                    return pro.price;
                }
            },
            truncate: function (name) {
                if (name.length > 24) {
                    return name.substr(0, 24).trim() + '...';
                } else {
                    return name;
                }
            },
            closeSearchResult: function () {
                this.show_result = false;
                Search.keyword = '';
            },
            openSearchResult: function () {
                this.show_result = true;
            },
        },
    });
});
