import Vue from 'vue'
// import * as fb from 'firebase'

class Order {
  constructor (name, phone, adId, done = false, id = null) {
    this.name = name
    this.phone = phone
    this.adId = adId
    this.done = done
    this.id = id
  }
}

export default {
  state: {
    orders: [],
    myOrders: []
  },
  mutations: {
    setOrders (state, payload) {
      state.orders = payload
      console.log('mutations setOrders() ads arr: ', state.orders)
    },
    setMyOrders (state, payload) {
      state.myOrders = payload
      console.log('mutations setMyOrders() myOrders arr: ', state.myOrders)
    },
  },
  actions: {
    async createOrder ({commit}, {name, phone, adId, ownerId}) {
      const order = new Order(name, phone, adId, ownerId)
      console.log('actions createOrder() order: ', order)
      commit('clearError')

      try {
        // await fb.database().ref(`/users/${ownerId}/orders`).push(order)
        // const order = await Vue.http.get('orders')
      } catch (error) {
        commit('setError', error.message)
        throw error
      }
    },
/*    async fetchOrders ({commit, getters}) {
      commit('setLoading', true)
      commit('clearError')
      try {
        // const fbVal = await fb.database().ref(`/users/${getters.user.id}/orders`).once('value')
        // const orders = fbVal.val()
        const objs = await Vue.http.get('orders')
// console.log('actions fetchOrders(), orders array: ', orders.body)

        const _arr = []
        Object.keys(objs.body).forEach(key => {
          const o = objs.body[key]
          _arr.push(
            new Order(o.name, o.phone, o.ad_id, o.done, o.id)
          )
        })

        commit('setOrders', _arr)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
      }
    },*/
    async fetchMyOrders ({commit, getters}) {
      commit('clearError')
      commit('setLoading', true)

      try {
        const objs = await Vue.http.get(`users/${getters.user.id}/orders`)
        console.log('actions fetchMyOrders(), orders array: ', objs.body)
        const _arr = []
        Object.keys(objs.body).forEach(key => {
          const o = objs.body[key]
          _arr.push(
            new Order(o.name, o.phone, o.ad_id, o.done, o.id)
          )
        })
        console.log('from fetchMyOrders(), _arr: ', _arr)

        commit('setMyOrders', _arr)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        // throw error
      }
    },
    async markOrderDone ({commit, getters}, payload) {
      commit('clearError')
      try {
        await fb.database().ref(`/users/${getters.user.id}/orders`).child(payload).update({
          done: true
        })
      } catch (error) {
        commit('setError', error.message)
        throw error
      }
    }
  },
  getters: {
    doneOrders (state) {
      return state.orders.filter(o => o.done)
    },
    undoneOrders (state) {
      console.log('getters undoneOrders() orders: ', state.orders)
      return state.orders.filter(o => {
        console.log('getters undoneOrders() o: ', o)
        return !o.done
      })
    },
    myOrders (state) {
      return state.myOrders
    },
/*
    orders (state, getters) {
      return getters.undoneOrders.concat(getters.doneOrders)
    }
*/
  }
}
