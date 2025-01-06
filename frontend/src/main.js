import { createApp } from 'vue'
import App from '@/App.vue'
import router from '@/router'
import store from '@/store'

import 'bootstrap/dist/css/bootstrap.css'
//import bootstrap from 'bootstrap/dist/js/bootstrap'
import Bootstrap from 'bootstrap'

import VueSweetalert2 from 'vue-sweetalert2';
import helpers from './helpers/helpers'

const VueSweetalert2Options = {
  confirmButtonColor: '#41b882',
  cancelButtonColor: '#ff7674',
};

import VueCountdown from '@chenfengyuan/vue-countdown';

//import '@vueform/vueform/themes/bootstrap/css/index.min.css'

import Vueform from '@vueform/vueform'
import vueformConfig from '@/vueform.config'

//instala plugins
/* const plugins = {
  install() {
    createApp.helpers = helpers;
    //createApp.prototype.$globalAuth = globalAuth;
  }
}; */

/* global bootstrap: false */
(function () {
  'use strict'
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })
})()

createApp(App)
  .use(router)
  .use(store)
  .use(helpers)
  .use(Bootstrap)
  .component(VueCountdown.name, VueCountdown)
  .use(VueSweetalert2, VueSweetalert2Options)
  .use(Vueform, vueformConfig)  
  .mount('#app')
