let Snackbar;
const types = ['danger', 'success', 'warning', 'info'];

$(function () {
    Snackbar = new Vue({
        el: '#snackbar',
        data: {
            vertical: 'bottom',
            horizontal: 'center',
            duration: 4000,
            notifyMessage: '',
            notifyAction: 'Close',
            type: '',
        },
        methods: {
            pushMessage: function (message, type, action, action_fn) {
                this.notifyMessage = message;
                this.notifyAction = action ? action : 'Close';
                this.action_fn = action_fn ? action_fn : null;
                this.$refs.snackbar.open();
                types.forEach(ele => {
                    $('.md-snackbar-container').removeClass(ele);
                });
                let idx = types.indexOf(type);
                if (idx !== -1) {
                    $('.md-snackbar-container').addClass(type);
                }
            },
            notifyActionAdapter: function () {
                this.action_fn ? this.action_fn() : this.$refs.snackbar.close();
            },
        },
    });
});
