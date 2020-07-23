/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';
import VueProgressBar from 'vue-progressbar';

const options = {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'right',
  inverse: false
}

Vue.use(VueProgressBar, options)

window.Fire = new Vue();

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//import vueRouter 
import VueRouter from 'vue-router'

//import sweet alert
import swal from 'sweetalert2';
window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

window.toast = toast;
//use vuerouter
Vue.use(VueRouter);

//import components
import Dashboard from './components/Dashboard.vue';
import Developer from './components/Developer.vue';
import Profile from './components/Profile.vue';
import Users from './components/Users.vue';

//define the components links
let routes = [
  { path: '/dashboard', component:Dashboard},
  { path: '/developer', component:Developer},
  { path: '/profile', component:Profile},
  { path: '/users', component:Users}
  ]

  //create new vuerouter instance
  const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })

//vuu filters for username
Vue.filter("upText",function(text){
  return text.charAt(0).toUpperCase() + text.slice(1);
})
//vuu filters for date

Vue.filter("myDate",function(created){
  return moment(created).format('LL'); 
})
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue').default
);

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue').default
);

const app = new Vue({
  router
}).$mount('#app')
