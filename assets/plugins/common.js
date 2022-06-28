export default {
    install(Vue) {
        Vue.mixin({
            methods: {
                scrollToTop() {
                    window.scrollTo(0, 0);
                }
            },
        })
    }
}