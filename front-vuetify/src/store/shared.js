export default {
  state: {
    loading: false,
    error: null,
    apiUrl: 'https://api.yii2-advanced.cyberdevel.ru/',
    storageUrl: 'https://yii2-advanced.cyberdevel.ru/files/',
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
    apiUrl (state) {
      return state.apiUrl
    },
    storageUrl (state) {
      return state.storageUrl
    }
  }
}
