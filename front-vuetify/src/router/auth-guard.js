import store from '../store'

export default function (to, from, next) {
  console.log(from.name + ' - ' + to.name)
  if (store.getters.user) {
    next()
  } else {
    next('/login?loginError=true')
  }
}
