import axios from 'axios';

axios.defaults.baseURL = 'http://blogger.test/api';

export default {
    install(Vue) {
        Vue.mixin({
            methods: {
                /* get category list from backend */
                getCategoryList: function (url) {
                    return axios.get(url);
                },
                /* send message in contact */
                sendMessage: function (url, dataObj) {
                    return axios.post(url, dataObj);
                },
                /* get list of latest blogs from backend */
                getLatestBlogs: function (url) {
                    return axios.get(url);
                },
                /* get blog's details from backend */
                getBlogDetails: function (url) {
                    return axios.get(url);
                },
                /* post comment */
                postComment: function (url, dataObj) {
                    return axios.post(url, dataObj);
                },
                /* subsribe */
                subscribe: function(url, dataObj) {
                    return axios.post(url, dataObj);
                }
            },
        })
    }
}