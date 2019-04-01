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
    orders: []
  },
  mutations: {
    setOrders (state, payload) {
      state.orders = payload
      console.log('mutations setOrders() ads arr: ', state.orders)
    }
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
    async fetchOrders ({commit, getters}) {
      commit('setLoading', true)
      commit('clearError')

      const resultOrders = []

      try {
        // const fbVal = await fb.database().ref(`/users/${getters.user.id}/orders`).once('value')
        // const orders = fbVal.val()
        const orders = await Vue.http.get('orders')
// console.log('actions fetchOrders(), orders array: ', orders.body)

        Object.keys(orders.body).forEach(key => {
          const o = orders.body[key]
          resultOrders.push(
            new Order(o.name, o.phone, o.ad_id, o.done, o.id)
          )
        })

        commit('setOrders', resultOrders)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
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
    orders (state, getters) {
      return getters.undoneOrders.concat(getters.doneOrders)
    }
  }
}
