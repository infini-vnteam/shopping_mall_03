$(function () {
    const product = new Vue({
        el: '#product',
        data: {
            login: true,
            user_id: 1,
            user: {},

            product: {
                comment: {},
                review: {},
            },
            selected_color: '',
            selected_size: '',
            selected_image: -1,
            quantity: 1,
            current_list_image: [],
            current_list_size: [],
            current_image_index: 0,

            is_write_review: false,
            review: {
                rate: 0,
                content: '',
            },

            comment_content: '',
            replies: {},
        },
        mounted: function () {
            this.fetchLoginInfo();
            this.fetchData();
            if (this.product.error) {
                location.href = '/';
            }
        },
        computed: {
            current_image: function () {
                return this.current_list_image[this.current_image_index];
            },
            rate_avg: function () {
                if (this.product.rate_count === 0) {
                    return 0;
                }
                return Math.round(this.product.rate_sum * 100 / this.product.rate_count) / 100;
            },
        },
        methods: {
            fetchData: function () {
                let url = location.href;
                let id = url.split('/').pop();

                axios.get('/product/getinfo/' + id)
                    .then(res => {
                        this.product = res.data;
                        this.selected_color = this.product.colors[0];
                        this.current_list_size = this.product.sizes[this.selected_color];
                        this.selected_size = this.current_list_size[0];
                        this.selected_image = 0;
                        this.updateImage();
                    })
                    .catch(err => {

                    });
            },
            fetchLoginInfo: function () {
                axios.get('/user/getinfo')
                    .then(res => {
                        if (res.data.login) {
                            this.login = true;
                            this.user_id = res.data.user.id;
                            this.user = res.data.user;
                        } else {
                            this.login = false;
                            this.user_id = -1;
                        }
                    })
                    .catch(err => {
                    });
            },
            search: function (keyword) {
                Search.keyword = keyword;
                Search.search(keyword);

                $('html, body').animate({
                    scrollTop: $('html').offset().top
                }, 500, 'easeInOutExpo');
            },
            selectSize: function (size) {
                this.selected_size = size;
            },
            selectColor: function (color) {
                this.selected_color = color;
                this.current_list_size = this.product.sizes[this.selected_color];
                this.selected_size = this.current_list_size[0];
                this.updateImage();
            },
            changeImage: function (index) {
                this.current_image_index = index;
            },
            updateImage: function () {
                this.current_list_image = this.product.images[this.selected_color];
                this.current_image_index = 0;
            },
            addToCart: function () {
                if (this.quantity < 1 || this.quantity > 100) {
                    Snackbar.pushMessage('The quantity must be between 1 and 100', 'danger');
                    return;
                }

                if (this.current_list_size.length === 0) {
                    Snackbar.pushMessage('The size is out of stock', 'danger');
                    return;
                }

                let item = {};
                item.real_id = this.product.id;
                item.name = this.product.name;
                item.quantity = this.quantity;
                item.img = this.product.images[this.selected_color][0];
                item.price = this.product.current_price;
                item.size = this.selected_size;
                item.color = this.selected_color;
                item.id = [this.product.id, item.color, item.size].join('_');

                Cart.addToCart(item);

                Snackbar.pushMessage('Add to cart success', 'success', 'Checkout', function () {
                    window.location.href = '/cart/view';
                });
            },
            page: function () {

            },
            sort_user_name: function (name) {
                let arr = name.split(' ');

                if (arr.length < 2) {
                    return name;
                }

                return arr[arr.length - 2] + ' ' + arr[arr.length - 1];
            },
            switchWriteReviewShowReview: function () {
                this.is_write_review = !this.is_write_review;
            },
            writeReview: function () {
                this.is_write_review = true;
            },
            tabChange: function (idx) {
                if (idx !== 1) {
                    this.is_write_review = false;
                }
            },
            postReview: function () {
                if (this.review.rate === 0) {
                    this.review.error = 'You must rate the product';
                    Snackbar.pushMessage(this.review.error, 'danger');
                } else {
                    let data = {
                        type: 'review',
                        user_id: this.user_id,
                        product_id: this.product.id,
                        review: this.review,
                    };

                    axios.post('/product/view/' + this.product.id, data)
                        .then(res => {
                            console.log(res);
                            // res.data
                            let receive = res.data;
                            receive.user_name = this.user.full_name;
                            receive.province = this.user.province;

                            this.product.review.items.unshift(receive);

                            this.product.rate_sum += this.review.rate;
                            this.product.rate_count++;

                            Snackbar.pushMessage('Post review success', 'success');

                            this.review.rate = 0;
                            this.review.content = '';
                        })
                        .catch(err => {
                            console.log(err);
                        });

                    this.is_write_review = false;
                }
            },
            postComment: function () {
                let data = {
                    content: this.comment_content,
                    type: 'comment',
                    user_id: this.user_id,
                    product_id: this.product.id,
                };

                axios.post('/product/view/' + this.product.id, data)
                    .then(res => {
                        // res.data
                        let receive = res.data;
                        receive.user_name = this.user.full_name;

                        this.product.comment.items.unshift(receive);
                        this.product.comment.total++;
                        this.comment_content = '';
                    })
                    .catch(err => {

                    });
            },
            postReply: function (comment_id) {
                let data = {
                    user_id: this.user_id,
                    product_id: this.product.id,
                };
                data.type = 'reply';
                data.content = this.replies[comment_id];
                data.comment_id = comment_id;

                axios.post('/product/view/' + this.product.id, data)
                    .then(res => {
                        // res.data
                        let receive = res.data;
                        receive.user_name = this.user.full_name;

                        let items = this.product.comment.items;

                        let idx = items.findIndex(item => {
                            return item.id === receive.comment_id;
                        });

                        if (!items[idx].replies) {
                            items[idx].replies = [];
                        }

                        items[idx].replies.push(receive);
                        this.replies[comment_id] = '';
                    })
                    .catch(err => {

                    });
            },
            deleteComment: function (comment_id, parent_id) {
                let data = {
                    comment_id: comment_id,
                    user_id: this.user_id,
                    product_id: this.product.id,
                };

                if (parent_id) {
                    data.parent_id = parent_id;
                    data.type = 'delete_reply';
                } else {
                    data.type = 'delete_comment';
                }

                axios.post('/product/view/' + this.product.id, data)
                    .then(res => {
                        // res.data
                        let receive = res.data;

                        let comments = this.product.comment.items;

                        if (receive.status === 'done') {
                            if (receive.type === 'delete_comment') {
                                let idx = comments.findIndex(item => {
                                    return item.id === receive.comment_id;
                                });
                                comments.splice(idx, 1);
                            } else {
                                let idx = comments.findIndex(item => {
                                    return item.id === receive.parent_id;
                                });
                                let replies = comments[idx].replies;
                                idx = replies.findIndex(item => {
                                    return item.id === receive.comment_id;
                                });
                                replies.splice(idx, 1);

                                comments.push({});
                                comments.pop();
                            }
                        }
                    })
                    .catch(err => {

                    });
            },
        },
    });
});
