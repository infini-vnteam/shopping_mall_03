let Search;

$(function () {
    Search = new Vue({
        el: '#search',
        data: {
            keyword: '',
        },
        methods: {
            postQuery: function () {
                if (this.keyword === '') return;

                let search = this;

                axios.post('/getDataSearch', {
                    content: search.keyword,
                })
                    .then(res => {
                        Search_result.results = res.data;
                        Search_result.openSearchResult();
                    })
                    .catch(err => {

                    });
            },
            search: function (keyword) {
                if (keyword === '') return;

                axios.post('/getDataSearch', {
                    content: keyword,
                })
                    .then(res => {
                        Search_result.results = res.data;
                        Search_result.openSearchResult();
                    })
                    .catch(err => {

                    });
            },
        },
    });
});
