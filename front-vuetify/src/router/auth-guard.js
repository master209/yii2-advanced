import store from '../store'

export default function (to, from, next) {
  console.log(from.name + ' - ' + to.name)

  //для удержания сессии юзера проверяю его по localStorage
  const user = {
    id: localStorage.getItem('user_id'),
    token: localStorage.getItem('token')
  }
  console.log('auth-guard, localStorage.user: ', user)
  if(user.id && user.token) {
    store.dispatch('autoLoginUser', user)
  }

  if (store.getters.user) {
    next()
  } else {
    next('/login?loginError=true')
  }
}
