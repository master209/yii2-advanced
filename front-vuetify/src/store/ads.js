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
    setMyAds (state, payload) {
      state.myAds = payload
      console.log('mutations setMyAds() myAds arr: ', state.myAds)
    },
    updateAd (state, {title, description, id}) {
      const ad = state.ads.find(a => {
        return a.id == id
      })

      ad.title = title
      ad.description = description
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
      console.log('actions createAd(): ', payload)
      commit('clearError')
      commit('setLoading', true)

      // загруженное изобр. приходит как payload.image (см. NewAd.vue)
      // это объект File с формы
      // const image = payload.image

      try {
// {ownerId: "3", title: "99", description: "999", imageSrc: "", promo: true}
        const newAd = {
            title: payload.title,
            description: payload.description,
            owner_id: getters.user.id,
            image_src:  '', // ссылка на изображение в fb.store
            promo: payload.promo
        }

        console.log('from createAd(), ads object, token: ', newAd, getters.token)
        // const ad = await Vue.http.post('users/' + getters.user.id + '/ads', newAd, {
        const ad = await Vue.http.post('ads', newAd, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        console.log('from createAd(), new ad object: ', ad)

/*
        const imageExt = image.name.slice(image.name.lastIndexOf('.'))  // расширение файла изобр.

        // загрузка изображения в storage с заданным именем (`ads/${ad.key}.${imageExt}`)
        const ref = fb.storage().ref().child(`ads/${ad.key}.${imageExt}`) // reference to image
        await ref.put(image)  // store image
*/

        // получение URL загруженного изображения
        // const imageSrc = await ref.getDownloadURL()

        // обновляем (update) в записи БД свойство imageSrc
/*
        await fb.database().ref('ads').child(ad.key).update({
          imageSrc
        })
*/

        commit('setLoading', false)
        commit('createAd', {
          title: ad.body.title,
          description: ad.body.description,
          ownerId: ad.body.owner_id,
          // imageSrc = imageSrc,
          promo: ad.body.promo,
          id: ad.body.id
        })
      } catch (error) {
        commit('setError', error.message)
        commit('setLoading', false)
        throw error   // "позволяет в промисе обработать ошибку"
      }
    },
    async fetchAds ({commit, getters}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        const ads = await Vue.http.get('ads')
// console.log('actions fetchAds(), ads array: ', ads.body)
        const resultAds = []
        Object.keys(ads.body).forEach(key => {
          const ad = ads.body[key]
          resultAds.push(   // формируется массив объектов
            new Ad(ad.title, ad.description, ad.owner_id, ad.image_src, ad.promo, ad.id)
          )
        })
console.log('from fetchAds(), resultAds: ', resultAds)

        commit('setAds', resultAds)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },
    async fetchMyAds ({commit, getters}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        const ads = await Vue.http.get(`users/${getters.user.id}/ads`)
console.log('actions fetchMyAds(), ads array: ', ads.body)
        const resultAds = []
        Object.keys(ads.body).forEach(key => {
          const ad = ads.body[key]
          resultAds.push(
            new Ad(ad.title, ad.description, ad.owner_id, ad.image_src, ad.promo, ad.id)
          )
        })
console.log('from fetchMyAds(), resultAds: ', resultAds)

        commit('setMyAds', resultAds)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },
    async updateAd ({commit, getters}, {title, description, id}) {
      commit('clearError')
      commit('setLoading', true)
      console.log('actions updateAd() title, description, id: ', title, description, id)
      try {
        const res = await Vue.http.put(`users/${getters.user.id}/ads/${id}`, {title, description}, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        commit('setLoading', false)
        commit('updateAd', {title, description, id})
        console.log('actions updateAd(), new ad object: ', res)
      } catch (error) {
        commit('setLoading', false)
        console.log('actions updateAd() ERR: ', error)
        if(!error.ok) {
          const mes = 'actions updateAd() ERROR'
          commit('setError', mes)
          throw mes
        }
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
    myAds (state) {
      return state.myAds
    },
    adById (state) {    // "ЭТО - ЗАМЫКАНИЕ"
      return adId => {  // откуда берется adId ???
        return state.ads.find(ad => ad.id == adId)   // возвращает весь объект ad
      }
    }

  }
}
