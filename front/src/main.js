import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import router from './routes'

Vue.use(VueRouter);

Vue.use(VueResource);
Vue.http.options.root = 'https://api.yii2-advanced.cyberdevel.ru/';
// https://github.com/pagekit/vue-resource/blob/develop/docs/http.md#interceptors
Vue.http.interceptors.push(function(request) {
  console.log('from interceptors, request.method: ', request.method);
  if (request.method === 'POST'
      || request.method === 'PATCH'
      || request.method === 'PUT'
      || request.method === 'DELETE'
  ) {
    // request.headers.set('X-CSRF-TOKEN', 'TOKEN');
    request.headers.set('Authorization', 'Bearer token-correct');
  }
});

export const eventEmitter = new Vue();

new Vue({
  el: '#app',
  render: h => h(App),
  router
})


