/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import store from './store';
import VueRouter from 'vue-router';
import HeaderComponent from "./components/HeaderComponent";
import Setting from "./components/Setting";
import AlertLogin from "./components/AlertLogin";
import RegisterComponent from "./components/RegisterComponent";
import LoginComponent from "./components/LoginComponent";
import SearchUserComponent from "./components/SearchUserComponent";
import MyProfileComponent from "./components/MyProfileComponent";
import Profile from "./components/Profile";
import Followed from "./components/Followed";
import Follower from "./components/Follower";
import Notice from "./components/Notice";
import MessageList from "./components/MessageList";
import Message from "./components/Message";
import MovieListComponent from "./components/MovieListComponent";
import MovieListCreateComponent from "./components/MovieListCreateComponent";
import HomeComponent from "./components/HomeComponent";
require('./bootstrap');
import myVueMixin  from './my_vue_mixin';

window.Vue = require('vue').default;
Vue.mixin(myVueMixin );

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('header-component', HeaderComponent);
Vue.component('alert-login', AlertLogin);
Vue.component('movie-list-component', MovieListComponent);

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeComponent,
            meta: {title: 'Youwitter'}
        },
        {
            path: '/register',
            name: 'user.register',
            component: RegisterComponent,
        },
        {
            path: '/login',
            name: 'user.login',
            component: LoginComponent,
        },
        {
            path: '/search',
            name: 'user.search',
            component: SearchUserComponent,
        },
        {
            path: '/myprofile',
            name: 'user.myprofile',
            component: MyProfileComponent,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/profile/:accountName',
            name: 'user.profile',
            component: Profile,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/movieList/create',
            name: 'movieList.create',
            component: MovieListCreateComponent,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/setting',
            name: 'user.setting',
            component: Setting,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/followed/:accountName',
            name: 'user.followed',
            component: Followed,
        },
        {
            path: '/follower/:accountName',
            name: 'user.follower',
            component: Follower,
        },
        {
            path: '/notice',
            name: 'user.notice',
            component: Notice,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/messageList',
            name: 'message.list',
            component: MessageList,
            meta: {
                isAuthenticated: true,
            },
        },
        {
            path: '/message/:roomId',
            name: 'message',
            component: Message,
            meta: {
                isAuthenticated: true,
            },
        }
    ]
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store: store,
    router,

});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.isAuthenticated)) {
      if (!store.state.isLoggedIn) {
        store.state.noLoggedInAlert = true;
        next({ name: 'user.login' });
    } else {
      next();
    }
  }
  next();
});
