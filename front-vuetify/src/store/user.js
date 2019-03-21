import Vue from 'vue'
import * as fb from 'firebase'

class User {
  constructor (id, token = null) {
    this.id = id
    this.token = token
  }
}

export default {
  state: {
    // token: null,
    user: null
  },
  mutations: {
    setUser (state,payload) {
      state.user = payload
      console.log('from setUser(), user object: ', state.user)
      if (state.user.id && state.user.token) {
        localStorage.setItem('user_id', state.user.id);
        localStorage.setItem('token', state.user.token);
      }
    },
    setToken (state, payload) {
      state.user.token = payload
      console.log('from setToken(), token: ', state.user.token)
    }
  },
  actions: {
    async registerUser ({commit}, {username, password}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        // const res = await fb.auth().createUserWithEmailAndPassword(username, password)
        commit('setUser', new User(res.body.id))
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
        console.log('from loginUser(), username, password: ', username, password)
        const res = await Vue.http.post('auth', {username, password})
        console.log('from loginUser(), response: ', res)
        if (res.status === 200) {
          if (res.body.token) {
            commit('setUser', new User(res.body.id, res.body.token))
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
    autoLoginUser ({commit}, {id, token}) {
      commit('setUser', new User(id, token))
    },
    logoutUser ({commit}) {
      localStorage.removeItem('user_id');
      localStorage.removeItem('token');
      commit('setUser', null, null)
      commit('setToken', null)
    }
  },
  getters: {
    token (state) {
      return state.user.token
    },
    user (state) {
      return state.user
    },
    isUserLoggedIn (state) {
      return state.user !== null
    }
  }
}
