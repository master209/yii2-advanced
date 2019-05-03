import Vue from 'vue'

class Ad {
  constructor (title, description, ownerId, imageSrc = '', promo = false, id = null) {
    this.title = title
    this.description = description
    this.ownerId = ownerId
    this.imageSrc = imageSrc
    this.promo = promo
    this.id = id
  }
}

export default {
  state: {
    ads: [],
    myAds: []
  },
  mutations: {
    createAd (state, payload) {
      console.log('mutations createAd() ad: ', payload)
      state.ads.push(payload)
    },
    setAds (state, payload) {
      state.ads = payload
      console.log('mutations setAds() ads arr: ', state.ads)
    },
    setAd (state, payload) {
      const id = payload.id
      const ad = state.ads.find(a => a.id == id)
      if(ad === undefined) {      //если в массиве ads[] не найдено элемента с (a.id == id),
        state.ads[0] = payload    // то добавляю его как единственный элемент
        console.log('mutations setAd() ads[0]: ', state.ads[0])
      }
      console.log('mutations setAd() ads: ', state.ads)
    },
    setMyAds (state, payload) {
      state.myAds = payload
      console.log('mutations setMyAds() myAds arr: ', state.myAds)
    },
    updateAd (state, {title, description, id}) {
      const ad = state.ads.find(a => a.id == id)
      ad.title = title
      ad.description = description
      console.log('mutations updateAd() ads: ', state.ads)
    }
  },


  actions: {


    async createAd ({commit, getters}, payload) {
/*
      if(!getters.token) {
        const error = 'actions createAd(): token is NULL'
        commit('setError', error)
        throw error
      }
*/
      // console.log('actions createAd(): ', payload)
      commit('clearError')
      commit('setLoading', true)

      // загруженное изобр. приходит как payload.image (см. NewAd.vue)
      // это объект File с формы
      const image = payload.image
console.log('actions createAd() image: ', payload.image)

      try {
        // {ownerId: "3", title: "99", description: "999", imageSrc: "", promo: true}
        const newAd = {
            title: payload.title,
            description: payload.description,
            owner_id: getters.user.id,
            image_src:  '', // ссылка на изображение в fb.store
            promo: payload.promo
        }

        // 1. Создание новой объявы
        console.log('from createAd(), ads object, token: ', newAd, getters.token)
        // const ad = await Vue.http.post('users/' + getters.user.id + '/ads', newAd, {
        const ad = await Vue.http.post('ads', newAd, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        console.log('from createAd(), new ad object: ', ad)

        // 2. Отправка файла на сервер
        var http = new XMLHttpRequest();


/*
        http.onload = function() {
          // console.log("Отправка завершена");
          const obj = Vue.http.get(`ads/${ad.body.id}`)
          // const imageSrc = obj.body.image_src
          console.log('http obj: ', obj)
        };
*/

        if (http.upload && http.upload.addEventListener) {

          http.upload.addEventListener( // Создаем обработчик события в процессе загрузки.
            'progress',
            function(e) {
              if (e.lengthComputable) {
                console.log('addEventListener сколько байтов загружено: ', e)

                // e.loaded — сколько байтов загружено.
                // e.total — общее количество байтов загружаемых файлов.
                // Кто не понял — можно сделать прогресс-бар :-)
              }
            },
            false
          );

          http.onreadystatechange = function () {
            // Действия после загрузки файлов
            if (this.readyState == 4) { // Считываем только 4 результат, так как их 4 штуки и полная инфа о загрузке находится
              if(this.status == 200) { // Если все прошло гладко

                // Действия после успешной загрузки.
                // Например, так
                console.log('onreadystatechange response: ', this)
                // var result = $.parseJSON(this.response);
                // можно получить ответ с сервера после загрузки.

              } else {
                // Сообщаем об ошибке загрузки либо предпринимаем меры.
              }
            }
          };

          http.upload.addEventListener(
            'load',
            function(e) {
              // Событие после которого также можно сообщить о загрузке файлов.
              // Но ответа с сервера уже не будет.
              // Можно удалить.
            });

          http.upload.addEventListener(
            'error',
            function(e) {
              // Паникуем, если возникла ошибка!
            });
        }

        var form = new FormData();
        form.append("image_file", payload.image); // под таким именем файл будет передан в массив $_FILES
        http.open('post', `${getters.serverUrl}ads/load-file/${ad.body.id}`, true);
        http.send(form);

        commit('setLoading', false)

        commit('createAd', {
          title: ad.body.title,
          description: ad.body.description,
          ownerId: ad.body.owner_id,
          // imageSrc,
          promo: ad.body.promo,
          id: ad.body.id
        })

        return ad.body
      } catch (error) {
        commit('setError', error.message)
        commit('setLoading', false)
        throw error   // "позволяет в промисе обработать ошибку"
      }
    },


    async fetchAds ({commit}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        const objs = await Vue.http.get('ads')
// console.log('actions fetchAds(), ads array: ', objs.body)
        const _arr = []
        Object.keys(objs.body).forEach(key => {
          const o = objs.body[key]
          _arr.push(   // формируется массив объектов
            new Ad(o.title, o.description, o.owner_id, o.image_src, o.promo, o.id)
          )
        })
console.log('from fetchAds(), _arr: ', _arr)

        commit('setAds', _arr)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },


    async myAds ({commit, getters}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        const objs = await Vue.http.get(`users/${getters.user.id}/ads`, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
console.log('actions myAds(), ads array: ', objs.body)
        const _arr = []
        Object.keys(objs.body).forEach(key => {
          const o = objs.body[key]
          _arr.push(
            new Ad(o.title, o.description, o.owner_id, o.image_src, o.promo, o.id)
          )
        })
console.log('from myAds(), _arr: ', _arr)

        commit('setMyAds', _arr)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },


    async adById ({commit, getters}, payload) {
      commit('clearError')
      commit('setLoading', true)

      try {
        console.log('actions adById() ad id: ', payload)
        const o = await Vue.http.get(`ads/${payload}`)      //  ads/31
        const ad = o.body
console.log('actions adById(), ad: ', ad)
        commit('setLoading', false)
        commit('setAd', new Ad(ad.title, ad.description, ad.owner_id, ad.image_src, ad.promo, ad.id))
      } catch (error) {
        if(error.body.status == 404) {
          // TODO:обработать 404
        }
        commit('setLoading', false)
        commit('setError', error.body.name)
        throw error
      }
    },


    async updateAd ({commit, getters}, {title, description, id}) {
      commit('clearError')
      commit('setLoading', true)
      console.log('actions updateAd() title, description, id: ', title, description, id)
      console.log('actions updateAd() token: ', getters.token)
      try {
        const res = await Vue.http.put(`users/${getters.user.id}/ads/${id}`, {title, description}, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        commit('setLoading', false)
        console.log('actions updateAd(), new ad object: ', res.body)
        commit('updateAd', {title, description, id})
      } catch (error) {
        commit('setLoading', false)
        console.log('actions updateAd() ERR: ', error)
        if(!error.ok) {
          const mes = 'actions updateAd() ERROR'
          commit('setError', mes)
          throw mes
          // window.location = '/login?loginError=true'  // если токен истек, а воспользовались кнопкой "Редактировать"
        }
      }
    }
  },
  getters: {
    ads (state) {
      return state.ads
    },
    ad: (state) => (id) => {                      // You can also pass arguments to getters
      return state.ads.find(ad => ad.id == id)    // https://vuex.vuejs.org/guide/getters.html
    },
    promoAds (state) {
      return state.ads.filter(ad => {
        return ad.promo
      })
    },
    myAds (state) {
      return state.myAds
    }
  }
}
