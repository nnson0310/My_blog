import moment from 'moment';

export default {
    install(Vue) {
        Vue.mixin({
            methods: {
                formatDate: function(date) {
                    return moment(date).format('D');
                },
                formatMonth: function(month) {
                    return moment(month).format('MMM');
                },
                formatTime: function(time) {
                    return moment(time).format('DD MMM YYYY');
                }
            },
        })
    }
}