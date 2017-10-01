
window.onload = function () {
    var sidebar = new Vue({
        el: '#sidebar',
        data: {
            user: '',
        },
        created: function () {
            this.getCurrentUser();
        },
        methods: {
            getCurrentUser: function () {
                axios.get('/getCurrentUser')
                    .then(response => {
                        this.user = response.data;
                    })
            }
        }
    });
}
