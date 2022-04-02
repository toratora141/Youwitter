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
            account_name:null
        },
        videoLists: null,
        videos: null,
        noLoggedInAlert: null,
    },
    mutations: {
        login(state, data) {
            state.isLoggedIn = true;
            state.user = data.user;
        },
        logout(state) {
            state.isLoggedIn = false;
            state.user = null;
        },
        updateUser(state, user) {
            state.user = user;
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
                setItem: (key, value) => Cookies.set(key, value, { expires: null, secure: true }),
                removeItem:(key) => Cookies.remove(key)
            }
        }
    )]
})
