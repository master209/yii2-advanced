import Vue from 'vue'
import * as fb from 'firebase'
import userStore from './user'

class Ad {
  constructor (title, description, owner_id, image_src = '', promo = false, id = null) {
    this.title = title
    this.description = description
    this.owner_id = owner_id
    this.image_src = image_src
    this.promo = promo
    this.id = id
  }
}

export default {
  state: {
    ads: []
  },
  mutations: {
    createAd (state, payload) {
      state.ads.push(payload)
    },
    loadAds (state, payload) {
      console.log('actions loadAds() ads arr: ', payload)
      state.ads = payload
    },
    updateAd (state, {title, description, id}) {
      const ad = state.ads.find(a => {
        return a.id === id
      })

      ad.title = title
      ad.description = description
    }
  },
  actions: {
    async createAd ({commit, getters}, payload) {
      console.log('actions createAd(): ', payload)
      commit('clearError')
      commit('setLoading', true)

      // загруженное изобр. приходит как payload.image (см. NewAd.vue)
      // это объект File с формы
      // const image = payload.image

      try {
// {owner_id: "3", title: "99", description: "999", image_src: "", promo: true}
        const newAd = new Ad(
          payload.title,
          payload.description,
          getters.user.id,
          '', // ссылку на изображение не передаем через payload, это будет ссылка на fb.store
          payload.promo
        )

        console.log('from createAd(), ads object, token: ', newAd, userStore.state.token)
        const ad = await Vue.http.post('ads', newAd, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + userStore.state.token
          }
        })

/*
        const imageExt = image.name.slice(image.name.lastIndexOf('.'))  // расширение файла изобр.

        // загрузка изображения в storage с заданным именем (`ads/${ad.key}.${imageExt}`)
        const ref = fb.storage().ref().child(`ads/${ad.key}.${imageExt}`) // reference to image
        await ref.put(image)  // store image
*/

        // получение URL загруженного изображения
        // const image_src = await ref.getDownloadURL()

        // обновляем (update) в записи БД свойство image_src
/*
        await fb.database().ref('ads').child(ad.key).update({
          image_src
        })
*/

        commit('setLoading', false)
        commit('createAd', {
          ...newAd,
          id: ad.key,
          // image_src
        })
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
        // const fbVal = await fb.database().ref('ads').once('value')
        const ads = await Vue.http.get('ads')
// console.log('from fetchAds(), ads array: ', ads.body)

        const resultAds = []
        Object.keys(ads.body).forEach(key => {
          const ad = ads.body[key]
          resultAds.push(   // формируется массив объектов
            new Ad(ad.title, ad.description, ad.owner_id, ad.image_src, ad.promo, key)
          )
        })
// console.log('from fetchAds(), resultAds: ', resultAds)

        commit('loadAds', resultAds)
        commit('setLoading', false)
      } catch (error) {
        commit('setError', error.message)
        commit('setLoading', false)
        throw error
      }
    },
    async updateAd ({commit}, {title, description, id}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        await fb.database().ref('ads').child(id).update({
          title, description
        })
        commit('updateAd', {
          title, description, id
        })
        commit('setLoading', false)
      } catch (error) {
        commit('setError', error.message)
        commit('setLoading', false)
        throw error
      }
    }
  },
  getters: {
    ads (state) {
      return state.ads
    },
    promoAds (state) {
      return state.ads.filter(ad => {
        return ad.promo
      })
    },
    myAds (state, getters) {
      console.log('myAds user.id: ', getters.user.id)
      return state.ads.filter(ad => {
        return ad.owner_id === getters.user.id
      })
    },
    adById (state) {    // "ЭТО - ЗАМЫКАНИЕ"
      return adId => {  // откуда берется adId ???
        return state.ads.find(ad => ad.id === adId)   // возвращает весь объект ad
      }
    }

  }
}
