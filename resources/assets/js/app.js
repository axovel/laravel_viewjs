
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import 'babel-polyfill';

import Vue from 'vue';
// import App from './components/App';
// window.Vue = require('vue');
import VueRouter from 'vue-router';
import router from './routes';
import BootstrapVue from 'bootstrap-vue';
import JsonExcel from 'vue-json-excel'

window.Vue = Vue;
window.swal = require('sweetalert2');
Vue.use(VueRouter);
Vue.use(BootstrapVue);

// import App from './components/App.vue';
// import AdminDetail from './components/admin/AdminDetail.vue';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 // const routes = [
 //   {path: '/home', component: AdminIndex, name: 'adminIndex'},
 //   {path: '/admin', component: AdminDetail, name: 'adminDetail'}
 // ]
 //
 // const router = new VueRouter({ routes });
Vue.component('admin-sidebar', require('./components/AdminSidebar.vue'));
Vue.component('downloadExcel', JsonExcel);

const app = new Vue({
    el: '#app',
    router
});

// const app = new Vue({
//   el: '#app',
//   router,
//   render: h => h(App)
// })
