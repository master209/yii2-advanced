import * as fb from 'firebase'

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
    ads: []
  },
  mutations: {
    createAd (state, payload) {
      state.ads.push(payload)
    },
    loadAds (state, payload) {
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
      console.log('actions createAd: ', payload)
      commit('clearError')
      commit('setLoading', true)

      // загруженное изобр. приходит как payload.image (см. NewAd.vue)
      // это объект File с формы
      const image = payload.image

      try {
        const newAd = new Ad(
          payload.title,
          payload.description,
          getters.user.id,
          '', // ссылку на изображение не передаем через payload, это будет ссылка на fb.store
          payload.promo
        )

        console.log(newAd)
        const ad = await fb.database().ref('ads').push(newAd) // асинхр запись в БД (без изобр.)
        const imageExt = image.name.slice(image.name.lastIndexOf('.'))  // расширение файла изобр.

        // загрузка изображения в storage с заданным именем (`ads/${ad.key}.${imageExt}`)
        const ref = fb.storage().ref().child(`ads/${ad.key}.${imageExt}`) // reference to image
        await ref.put(image)  // store image

        // получение URL загруженного изображения
        const imageSrc = await ref.getDownloadURL()

        // обновляем (update) в записи БД свойство imageSrc
        await fb.database().ref('ads').child(ad.key).update({
          imageSrc
        })

        commit('setLoading', false)
        commit('createAd', {
          ...newAd,
          id: ad.key,
          imageSrc
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
        const fbVal = await fb.database().ref('ads').once('value')
        const ads = fbVal.val()

        const resultAds = []
        Object.keys(ads).forEach(key => {
          const ad = ads[key]
          resultAds.push(   // формируется массив объектов
            new Ad(ad.title, ad.description, ad.ownerId, ad.imageSrc, ad.promo, key)
          )
        })

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
        return ad.ownerId === getters.user.id
      })
    },
    adById (state) {    // "ЭТО - ЗАМЫКАНИЕ"
      return adId => {  // откуда берется adId ???
        return state.ads.find(ad => ad.id === adId)   // возвращает весь объект ad
      }
    }

  }
}
