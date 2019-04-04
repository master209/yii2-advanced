import Vue from 'vue'
import './plugins/vuetify'
import App from './App.vue'
import router from './router'
import VueResource from 'vue-resource'
import store from './store'
import BuyModalComponent from '@/components/Shared/BuyModal'
import * as fb from 'firebase'

Vue.config.productionTip = false
Vue.component('app-buy-modal', BuyModalComponent)

Vue.use(VueResource);
Vue.http.options.root = 'https://api.yii2-advanced.cyberdevel.ru/';

/*
// https://github.com/pagekit/vue-resource/blob/develop/docs/http.md#interceptors
Vue.http.interceptors.push(function(request) {
  console.log('from interceptors, request.method: ', request.method);
  if (request.method === 'POST'
    || request.method === 'PATCH'
    || request.method === 'PUT'
    || request.method === 'DELETE'
  ) {

// https://forum.vuejs.org/t/vue-resource-api-call-inside-mutation-doesnt-recognize-vue/2863/6
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    request.headers.set('Authorization', 'Bearer token-correct');

    next();
  }
});
*/

new Vue({
  router,
  store,
  created: function () {
    var config = {
      apiKey: 'AIzaSyCef7HxxH5g5Ar8pCV3rnineJ-UgHDRnLE',
      authDomain: 'vue-practice-25d09.firebaseapp.com',
      databaseURL: 'https://vue-practice-25d09.firebaseio.com',
      projectId: 'vue-practice-25d09',
      storageBucket: 'vue-practice-25d09.appspot.com',
      messagingSenderId: '591970981597'
    }
    fb.initializeApp(config)
    store.dispatch('checkLoginUser')
    // store.dispatch('fetchAds')
  },
  render: h => h(App)
}).$mount('#app')
