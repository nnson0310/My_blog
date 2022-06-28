export default {
    install(Vue) {
        Vue.mixin({
            methods: {
                errorNotice(config) {
                    this.$Notice.error({
                        title: config.title,
                        desc: config.status ? "" : config.msg,
                        duration: config.duration
                    })
                },
                successNotice(config) {
                    this.$Notice.success({
                        title: config.title,
                        desc: config.status ? "" : config.msg,
                        duration: config.duration
                    })
                },
                infoNotice(config) {
                    this.$Notice.info({
                        title: config.title,
                        desc: config.status ? "" : config.msg,
                        duration: config.duration
                    })
                }
            }
        })
    }
};
