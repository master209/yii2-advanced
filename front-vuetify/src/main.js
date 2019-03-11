import Vue from 'vue'
import './plugins/vuetify'
import App from './App'
import router from './router'
import store from './store'
import Vuetify from 'vuetify'
import * as fb from 'firebase'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router,
  store,
  components: { App },
  template: '<App/>',
  created () {
    fb.initializeApp({
      apiKey: 'AIzaSyCef7HxxH5g5Ar8pCV3rnineJ-UgHDRnLE',
      authDomain: 'vue-practice-25d09.firebaseapp.com',
      databaseURL: 'https://vue-practice-25d09.firebaseio.com',
      projectId: 'vue-practice-25d09',
      storageBucket: 'vue-practice-25d09.appspot.com',
      messagingSenderId: '591970981597'
    })
  }
}).$mount('#app')
