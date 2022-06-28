/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './css/styles/styles.scss';
import './css/bootstrap.min.css';
import './css/elegant-icons.css';
import './css/owl.carousel.min.css';
import './css/font-awesome.min.css';
import './css/slicknav.min.css';

// start the Stimulus application
import './bootstrap';

//import other vue components
import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import Vuetify from 'vuetify';
import routes from './router.js';
import axios from './plugins/axios';
import common from './plugins/common.js';
import moment from './plugins/moment.js';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import notice from './plugins/notice.js';
import VueCompositionAPI from '@vue/composition-api';
import store from './plugins/store.js';
import VueSocialSharing from 'vue-social-sharing';
import VueCalendar from 'vue-functional-calendar';

//define other components to use in global component's context
Vue.use(ViewUI);
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(axios);
Vue.use(common);
Vue.use(notice);
Vue.use(VueCompositionAPI);
Vue.use(moment);
Vue.use(VueSocialSharing);
Vue.use(VueCalendar, {
    dayNames: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']
});

//define vue router
const router = new VueRouter({
    routes,
    mode: 'hash',
    scrollBehavior (to, from , savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        return {x: 0, y: 0};
    }
})


//create app
const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});

export default app;
