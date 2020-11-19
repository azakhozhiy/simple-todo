import http from './http';
import authComponent from './components/auth'
import taskForm from './components/taskForm';

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = http;
window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('../vendor/summernote/summernote-lite');
require('../vendor/notifier/notifier')

const appSelector = '#app'

$(document).ready(() => {
  
  authComponent.install();
  taskForm.install();
  
  // main page
  $('#accordion').collapse({
    toggle: false
  });
  
  $('[data-toggle="tooltip"]').tooltip()
  
  /*
  $.notifier.callSystem({
    type: 'done',
    icon: 'check',
    text: 'Задача успешна добавлена!'
  });
   */
});
