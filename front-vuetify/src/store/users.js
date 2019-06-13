import Vue from 'vue'

class User {
  constructor (id, username, email, status, fio, phoneMob) {
    this.id = id,
    this.username = username,
    this.email = email,
    this.status = status,
    this.fio = fio,
    this.phoneMob = phoneMob
  }
}

export default {
  state: {
    users: []
  },
  mutations: {
    setUsers (state, payload) {
      state.users = payload
      console.log('mutations setUsers() users arr: ', state.users)
    }
  },
  actions: {
    async fetchUsers ({commit, getters}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        console.log('actions fetchUsers(), token', getters.token)
        const objs = await Vue.http.get('users?expand=profile', {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        console.log('actions fetchUsers(), users array: ', objs.body)
        const _arr = []
        Object.keys(objs.body).forEach(key => {
          const o = objs.body[key]
          _arr.push(
            new User(o.id, o.username, o.email, o.status, o.profile.shortname, o.profile.phone_mob)
          )
        })
        console.log('from fetchUsers(), _arr: ', _arr)

        commit('setUsers', _arr)
        commit('setLoading', false)
      } catch (error) {
        commit('setLoading', false)
        commit('setError', error.message)
        throw error
      }
    },

    async updateUser ({commit, getters}, {id, username, password, email, status}) {
      commit('clearError')
      commit('setLoading', true)
      try {
        console.log('actions updateUser(), id, username, password, email, status: ', id, username, password, email, status)
        const res = await Vue.http.put(`users/${id}`, {username, password, email, status}, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getters.token
          }
        })
        console.log('actions updateUser(), response: ', res)
        commit('setLoading', false)
        if (res.status === 200) {
          if (res.body.token) {
            commit('setUser', new User(res.body.id, res.body.token))
          } else {
            throw res.bodyText
          }
        }
      } catch (error) {
        console.log('actions updateUser(), catch error: ', error)
        commit('setLoading', false)
        // commit('setError', error)
        throw JSON.parse(error)
      }
    },
    
  },
  
  getters: {
    users (state) {
      return state.users
    }
  }
}
