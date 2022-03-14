/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import HeaderComponent from "./components/HeaderComponent";
import RegisterComponent from "./components/RegisterComponent";
import LoginComponent from "./components/LoginComponent";
import MyProfileComponent from "./components/MyProfileComponent";
import MovieListCreateComponent from "./components/MovieListCreateComponent";
import HomeComponent from "./components/HomeComponent";
require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('header-component', HeaderComponent);

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
            path: '/myprofile',
            name: 'user.myprofile',
            component: MyProfileComponent,
        },
        {
            path: '/movieList/create',
            name: 'movieList.create',
            component: MovieListCreateComponent,
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
    router
});
