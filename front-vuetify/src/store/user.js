import Vue from 'vue'
import store from "./index";

class User {
  constructor (id, token = null) {
    this.id = id
    this.token = token
  }
}

export default {
  state: {
    user: null
  },
  mutations: {
    setUser (state,payload) {
      state.user = payload
      console.log('mutations setUser(), user object: ', state.user)
      if (state.user !== null) {
        localStorage.setItem('user_id', state.user.id);
        localStorage.setItem('token', state.user.token);
      }
    }
  },
  actions: {
    async registerUser ({commit}, {username, password}) {
      commit('clearError')
      commit('setLoading', true)
      try {
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
        console.log('actions loginUser(), username, password: ', username, password)
        const res = await Vue.http.post('auth', {username, password})
        console.log('actions loginUser(), response: ', res)
        commit('setLoading', false)
        if (res.status === 200) {
          if (res.body.token) {
            commit('setUser', new User(res.body.id, res.body.token))
          } else {
            throw res.bodyText
          }
        }
      } catch (error) {
        console.log('actions loginUser(), catch error: ', error)
        commit('setLoading', false)
        // commit('setError', error)
        throw JSON.parse(error)
      }
    },
    checkLoginUser ({commit}) {
      //для удержания сессии юзера проверяю его по localStorage
      const user = {
        id: localStorage.getItem('user_id'),
        token: localStorage.getItem('token')
      }
      console.log('auth-guard, checkLoginUser.user: ', user)
      if(user.id && user.token) {
        store.dispatch('autoLoginUser', user)
      }
    },
    autoLoginUser ({commit}, {id, token}) {
      commit('setUser', new User(id, token))
    },
    logoutUser ({commit}) {
      localStorage.removeItem('user_id');
      localStorage.removeItem('token');
      commit('setUser', null, null)
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
