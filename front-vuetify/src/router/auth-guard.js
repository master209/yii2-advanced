import store from '../store'

export default function (to, from, next) {
  console.log(from.name + ' - ' + to.name)

  //для удержания сессии юзера проверяю его по localStorage
  const user = localStorage.getItem('user')
  console.log('auth-guard, localStorage.user: ', user)
  if(user) {
    store.dispatch('autoLoginUser', user)
  }

  if (store.getters.user) {
    next()
  } else {
    next('/login?loginError=true')
  }
}
