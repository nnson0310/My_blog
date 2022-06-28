import Vuex from 'vuex';
import Vue from 'vue';
import axios from 'axios';

Vue.use(Vuex);

//handle Vuex state
const state = {
    humberger: {
        show: '',
        overlay: ''
    },
    categoryItems: [],
    categoryId: [],
    topics: [],
    paginatedBlogPosts: [],
    comments: [],
    blogId: '',
    showSearch: 'none',
    tags: [],
    tagName: '',
    featurePosts: []
}

//hangle Vuex getters
const getters = {
    activeHumberger: state => state.humberger.show,
    activeOverlay: state => state.humberger.overlay,
}

//handle Vuex mutations
const mutations = {
    TOGGLE_HUMBERGER: (state, payload) => {
        state.humberger.show = payload
    },
    TOGGLE_OVERLAY: (state, payload) => {
        state.humberger.overlay = payload
    },
    SHOW_SEARCH: (state, payload) => {
        state.showSearch = payload;
    },
    SET_TOPICS: (state, payload) => {
        state.topics = payload;
    },
    SET_PAGINATED_BLOG_POSTS: (state, payload) => {
        state.paginatedBlogPosts = payload
    },
    SET_COMMENTS: (state, payload) => {
        state.comments = payload;
    },
    SET_BLOG_ID: (state, payload) => {
        state.blogId = payload;
    },
    SET_CATEGORY_ITEMS: (state, payload) => {
        state.categoryItems = payload;
    },
    SET_CATEGORY_ID: (state, payload) => {
        state.categoryId = payload;
    },
    SET_TAGS: (state, payload) => {
        state.tags = payload;
    },
    SET_TAG_NAME: (state, payload) => {
        state.tagName = payload;
    },
    SET_FEATURE_POSTS: (state, payload) => {
        state.featurePosts = payload;
    }
}

//handle Vuex actions:
const actions = {
    getTopics({ commit }) {
        const url = 'topic/list';
        axios.get(url).then(res => {
            commit('SET_TOPICS', res.data);
        })
    },
    getPaginatedBlogPosts({ commit }, page) {
        const url = '/paginated_posts/get';
        axios.get(url, {
            params: {
                page: page
            }
        }).then(res => {
           /*  console.log(res.data.totalPages); */
            commit('SET_PAGINATED_BLOG_POSTS', res.data);
        })
    },
    getComments({ commit }, id) {
        const url = "/post_details/comments/get/" + id;
        axios.get(url).then(res => {
            /* console.log(res.data); */
            commit('SET_COMMENTS', res.data);
        });
    },
    getBlogId({ commit }, blogId) {
        commit('SET_BLOG_ID', blogId);
    },
    getCategoryItems({ commit }) {
        const url = "category/list";
        axios.get(url).then(res => {
            commit('SET_CATEGORY_ITEMS', res.data);
        });    
    },
    getCategoryId({ commit }, catId) {
        commit('SET_CATEGORY_ID', catId);
    },
    getTags({ commit }) {
        const url = "tags/list";
        axios.get(url).then(res => {
            commit('SET_TAGS', res.data);
        });
    },
    getFeaturePosts({ commit }) {
        const url = "feature_posts/get";
        axios.get(url).then(res => {
            console.log(res.data);
            commit('SET_FEATURE_POSTS', res.data);
        });
    }
}

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})
