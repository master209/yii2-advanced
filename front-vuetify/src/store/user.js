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
    async loginUser2 ({commit}, {username, password}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        // const user = await fb.auth().signInWithEmailAndPassword(username, password)
        console.log('from loginUser2, username, password: ', username, password)
// !!! https://stackoverflow.com/questions/45633408/cant-access-vue-resource-inside-action-vuex
// ЗА ПРЕДЕЛАМИ vue instance (store in this case) use Vue.http, ВНУТРИ instance use  this.$http
        const user = await Vue.http.post('auth', {username, password})
        commit('setUser', new User(username))
        commit('setLoading', false)
      } catch (error) {
        console.log('from loginUser2, catch error: ', error)
        commit('setLoading', false)
        commit('setError', error.bodyText)
        throw error
      }
    },
/*
    loginUser ({commit}, {username, token}) {
      console.log('from loginUser(): ', username, token)
      commit('setUser', new User(username))
      commit('setToken', token)
    },
*/
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
