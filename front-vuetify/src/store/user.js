import Vue from 'vue'
import * as fb from 'firebase'

class User {
  constructor (username) {
    this.username = username
  }
}

export default {
  state: {
    token: null,
    user: null
  },
  mutations: {
    setUser (state, payload) {
      state.user = payload
      console.log('from setUser(), state.user: ', state.user)
    },
    setToken (state, payload) {
      state.token = payload
      console.log('from setToken(), state.token: ', state.token)
    }
  },
  actions: {
    async registerUser ({commit}, {username, password}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        const user = await fb.auth().createUserWithEmailAndPassword(username, password)
        commit('setUser', new User(user.username))
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },
    async loginUser ({commit}, {username, password}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        // const user = await fb.auth().signInWithEmailAndPassword(username, password)
        console.log('from loginUser(), username, password: ', username, password)
// !!! https://stackoverflow.com/questions/45633408/cant-access-vue-resource-inside-action-vuex
// ЗА ПРЕДЕЛАМИ vue instance (store in this case) use Vue.http, ВНУТРИ instance use  this.$http
        const res = await Vue.http.post('auth', {username, password})
        console.log('from loginUser(), response: ', res)
        if (res.status === 200) {
          if (res.body.token) {
            console.log('from loginUser(), token: ', res.body.token)
            commit('setUser', new User(username))
            commit('setToken', res.body.token)
          } else {
            throw res.bodyText
          }
        }
        commit('setLoading', false)
      } catch (error) {
        console.log('from loginUser(), catch error: ', error)
        commit('setLoading', false)
        // commit('setError', error)
        throw JSON.parse(error)
      }
    },
    autoLoginUser ({commit}, payload) {
      // console.log('before autoLoginUser')
      commit('setUser', new User(payload.username))
      // console.log('after autoLoginUser')
    },
    logoutUser ({commit}) {
      console.log('logoutUser')
      fb.auth().signOut()
      commit('setUser', null)
    }
  },
  getters: {
    token (state) {
      return state.token
    },
    user (state) {
      return state.user
    },
    isUserLoggedIn (state) {
      return state.user !== null
    }
  }
}
