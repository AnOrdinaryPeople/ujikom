require('./bootstrap')

import App from './components/App.vue'
import auth from './auth.js'
import router from './router.js'
import Guide from './components/Guide'
import Modal from './components/Modal'

import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import VueSession from 'vue-session'

import LaravelVuePagination from 'laravel-vue-pagination'
import MarkdownItVue from 'markdown-it-vue'
import 'markdown-it-vue/dist/markdown-it-vue.css'
import VueSimplemde from 'vue-simplemde'
import 'simplemde/dist/simplemde.min.css'
import VueRecaptcha from 'vue-recaptcha'
import InfiniteLoad from 'vue-infinite-loading'
import Socialite from './components/auth/Socialite'
import Invut from './components/Input'
import Loading from './components/Loading'
import VueApexCharts from 'vue-apexcharts'
import Modar from './components/admin/Modar'
import Invuut from './components/admin/Invut'

import { library } from '@fortawesome/fontawesome-svg-core'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(fab, fas, far)

window.Vue = require('vue')

Vue.use(VueAxios, axios)
axios.defaults.baseURL = process.env.MIX_APP_URL + '/api'

Vue.router = router
Vue.use(VueRouter)
Vue.use(VueAuth, auth)
Vue.use(VueSession)
Vue.use(VueApexCharts)

Vue.component('fa', FontAwesomeIcon)
Vue.component('paginate', LaravelVuePagination)
Vue.component('markdown', MarkdownItVue)
Vue.component('input-mark', VueSimplemde)
Vue.component('recaptcha', VueRecaptcha)
Vue.component('infinite', InfiniteLoad)
Vue.component('socialite', Socialite)
Vue.component('guide', Guide)
Vue.component('invut', Invut)
Vue.component('post-load', Loading)
Vue.component('modal', Modal)
Vue.component('chart', VueApexCharts)
Vue.component('admin-modal', Modar)
Vue.component('admin-invut', Invuut)

new Vue({
    router,
    render: h => h(App)
}).$mount('#app')
