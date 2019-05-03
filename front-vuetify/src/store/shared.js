export default {
  state: {
    loading: false,
    error: null,
    serverUrl: 'https://api.yii2-advanced.cyberdevel.ru/',
    storageUrl: 'files/'
  },
  mutations: {
    setLoading (state, payload) {
      state.loading = payload
    },
    setError (state, payload) {
      state.error = payload
    },
    clearError (state) {
      state.error = null
    }
  },
  actions: {
    setLoading ({commit}, payload) {
      commit('setLoading', payload)
    },
    setError ({commit}, payload) {
      commit('setError', payload)
    },
    clearError ({commit}) {
      commit('clearError')
    }
  },
  getters: {
    loading (state) {
      return state.loading
    },
    error (state) {
      return state.error
    },
    serverUrl (state) {
      return state.serverUrl
    },
    storageUrl (state) {
      return `${state.serverUrl}.${state.serverUrl}`
    }
  }
}
