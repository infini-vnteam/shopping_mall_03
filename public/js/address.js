const Address = {
    data: {
        provinces: [],
        districts: [],

        selected_state: '',
        selected_dist: '',
    },
    mounted: function () {
        this.fetchProvince();
    },
    methods: {
        fetchProvince: function () {
            axios.get('/loadprovince')
                .then(res => {
                    this.provinces = res.data;
                })
                .catch(err => {

                });
        },
        selectState: function () {
            axios.post('/loaddistrict', {
                province_name: this.selected_state,
            })
                .then(res => {
                    this.districts = Object.values(res.data);
                    this.selected_dist = this.districts[0];
                })
                .catch(err => {

                });
        },
    },
};