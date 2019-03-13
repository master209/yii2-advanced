import * as fb from 'firebase'

class User {
  constructor (username) {
    this.username = username
  }
}

export default {
  state: {
    user: null
  },
  mutations: {
    setUser (state, payload) {
      state.user = payload
      console.log('from setUser(), state.user: ', state.user)
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
/*    async loginUser ({commit}, {username, password}) {
      console.log('from loginUser, username: ', username)
      commit('clearError')
      commit('setLoading', true)
      try {
        // const user = await fb.auth().signInWithEmailAndPassword(username, password)
        const user = await this.$http.post('auth', {username, password})
        console.log('from loginUser, user: ', user)
        commit('setUser', new User(user.username))
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.bodyText)
        throw error
      }
    },*/
    loginUser ({commit}, {username, password}) {
      console.log('from loginUser(): ', username, password)
      commit('setUser', new User(username))
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
    user (state) {
      return state.user
    },
    isUserLoggedIn (state) {
      return state.user !== null
    }
  }
}
