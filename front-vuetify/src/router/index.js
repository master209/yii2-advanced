import Vue from 'vue'
import Router from 'vue-router'
import AuthGuard from './auth-guard'
import Home from '@/components/Home'
import ErrorCmp from '@/components/Error'
import Ad from '@/components/Ads/Ad'
import AdList from '@/components/Ads/AdList'
import NewAd from '@/components/Ads/NewAd'
import Login from '@/components/Auth/Login'
import Registration from '@/components/Auth/Registration'
import Orders from '@/components/User/Orders'
import GridNoData from '@/components/Grids/GridNoData'
import GridStandard from '@/components/Grids/GridStandard'
import GridItemsAndHeadersSlots from '@/components/Grids/GridItemsAndHeadersSlots'
import GridHeadercellSlot from '@/components/Grids/GridHeadercellSlot'
import GridProgress from '@/components/Grids/GridProgress'
import GridFooter from '@/components/Grids/GridFooter'
import GridExpandable from '@/components/Grids/GridExpandable'
import GridSelectableRows from '@/components/Grids/GridSelectableRows'
import GridSearchFilter from '@/components/Grids/GridSearchFilter'
import GridPaginatorCustomIcons from '@/components/Grids/GridPaginatorCustomIcons'
import GridExternalPagination from '@/components/Grids/GridExternalPagination'
import GridExternalSorting from '@/components/Grids/GridExternalSorting'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '',
      name: 'home',
      component: Home
    },
    {
      path: '/ad/:id',
      props: true,
      name: 'ad',
      component: Ad,
    },
    {
      path: '/list',
      name: 'list',
      component: AdList,
      beforeEnter: AuthGuard
    },
    {
      path: '/new',
      name: 'newAd',
      component: NewAd,
      beforeEnter: AuthGuard
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/registration',
      name: 'reg',
      component: Registration
    },
    {
      path: '/orders',
      name: 'orders',
      component: Orders,
      beforeEnter: AuthGuard
    },

    {
      path: '/grid-no-data',
      name: 'grid-no-data',
      component: GridNoData
    },
    {
      path: '/grid-standard',
      name: 'grid-standard',
      component: GridStandard
    },
    {
      path: '/grid-items-and-headers-slots',
      name: 'grid-items-and-headers-slots',
      component: GridItemsAndHeadersSlots
    },
    {
      path: '/grid-headercell-slot',
      name: 'grid-headercell-slot',
      component: GridHeadercellSlot
    },
    {
      path: '/grid-progress',
      name: 'grid-progress',
      component: GridProgress
    },
    {
      path: '/grid-footer',
      name: 'grid-footer',
      component: GridFooter
    },
    {
      path: '/grid-expandable',
      name: 'grid-expandable',
      component: GridExpandable
    },
    {
      path: '/grid-selectable-rows',
      name: 'grid-selectable-rows',
      component: GridSelectableRows
    },
    {
      path: '/grid-search-filter',
      name: 'grid-search-filter',
      component: GridSearchFilter
    },
    {
      path: '/grid-paginator-custom-icons',
      name: 'grid-paginator-custom-icons',
      component: GridPaginatorCustomIcons
    },
    {
      path: '/grid-external-pagination',
      name: 'grid-external-pagination',
      component: GridExternalPagination
    },
    {
      path: '/grid-external-sorting',
      name: 'grid-external-sorting',
      component: GridExternalSorting
    },

    {
      path: '*',          // обработка несуществующего роута
      component: ErrorCmp
    }
  ],
  mode: 'history'
})
