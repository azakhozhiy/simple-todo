import http from './http';
import authComponent from './auth';
import Vue from 'vue';
import TaskPage from './pages/TasksPage';

window._ = require('lodash');
window.axios = http;
window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('../vendor/summernote/summernote-lite');
require('../vendor/notifier/notifier');

Vue.component(`task-page`, TaskPage);

$(document).ready(() => {
  
  authComponent.install();
  
  new Vue().$mount('#main-page');
});
