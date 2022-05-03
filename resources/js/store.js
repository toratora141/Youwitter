import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import  Cookies from 'js-cookie';

Vue.use(Vuex)

export default new Vuex.Store({
    namespaced: true,
    state: {
        isLoggedIn: false,
        user: {
            accountName:null
        },
        videoLists: null,
        videos: null,
        noLoggedInAlert: null,
        searchKeyword: null,
        searchResult: null,
    },
    mutations: {
        login(state, data) {
            state.isLoggedIn = true;
            state.user = data.user;
            //データベースから取ってきた引数のため変数名がスネークケースをキャメルケースに戻す
            state.user.accountName = data.user.account_name;
        },
        logout(state) {
            state.isLoggedIn = false;
            state.user = null;
        },
        updateUser(state, user) {
            state.user = user;
            state.user.accountName = user.account_name;
        },
        search(state, search,) {
            state.searchResult = search.result;
            state.searchKeyword = search.keyword;
            if (search.keyword == null) {
                state.searchKeyword = '';
            }
        }
    },
    actions: {
    },
    modules: {
        // auth,
    },
    getters: {
    },
    plugins: [createPersistedState(
        {
            key: 'do',
            paths:[
                'isLoggedIn',
                'user',
                'videoLists',
                'videos',
            ],
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) => Cookies.set(key, value, { expires: 31536000, secure: true }),
                removeItem:(key) => Cookies.remove(key)
            }
        }
    )]
})
