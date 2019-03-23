import store from '../store'

export default function (to, from, next) {
  console.log(from.name + ' - ' + to.name)

  store.dispatch('checkLoginUser')  // нужно на случай перезагрузки защищенной страницы

  if (store.getters.user) {
    next()
  } else {
    next('/login?loginError=true')
  }
}
