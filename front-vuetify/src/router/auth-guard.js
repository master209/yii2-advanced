import store from '../store'

export default function (to, from, next) {
  console.log(from.name + ' - ' + to.name)

  store.dispatch('checkLoginUser')  // нужно на случай перезагрузки защищенной страницы
  .then(() => {
    if (store.getters.user) {
      next()
    } else {
      next('/login?loginError=true')
    }
  })
}
