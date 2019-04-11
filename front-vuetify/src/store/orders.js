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
    markOrderDone (state, {order_id, done}) {
      console.log('mutations markOrderDone() order_id, done: ', order_id, done)
      const ad = state.myOrders.find(a => {
        return a.id == order_id
      })
      ad.done = done
    }
  },


  actions: {


    async createOrder ({commit}, {name, phone, adId/*, ownerId*/}) {
      const newOrder = {
        name,
        phone,
        ad_id: adId,
        // owner_id: ownerId
      }

      console.log('actions createOrder() order: ', newOrder)
      commit('clearError')

      try {
        const o = await Vue.http.post('orders', newOrder)
        commit('setLoading', false)
        console.log('actions createOrder(), new order object: ', o.body)
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
        const objs = await Vue.http.get(`users/${getters.user.id}/orders`,  {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
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


    async markOrderDone ({commit, getters}, {order_id, done}) {
      commit('clearError')
      console.log('actions markOrderDone() order_id, done: ', order_id, done)
      try {
        // const o = await Vue.http.put(`users/${getters.user.id}/orders/${id}/mark-done`, {
        const o = await Vue.http.put(`orders/${order_id}/mark-done`, {done}, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        commit('setLoading', false)
        commit('markOrderDone', {
          order_id,
          done:o.body.done
        })
        console.log('actions markOrderDone(), new ad object: ', o)
      } catch (error) {
        commit('setLoading', false)
        console.log('actions markOrderDone() ERR: ', error)
        if(!error.ok) {
          const mes = 'actions markOrderDone() ERROR'
          commit('setError', mes)
          throw mes
          // window.location = '/login?loginError=true'  // если токен истек, а воспользовались кнопкой "Редактировать"
        }
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
