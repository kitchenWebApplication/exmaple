import Vue         from 'vue/dist/vue.js'
import VueRouter   from 'vue-router'
import VueResource from 'vue-resource'
import VueFilter   from 'vue-filter'
import VueMaterial from 'vue-material'
import io          from 'socket.io-client'

import AuthService  from './auth'


window.io = io
Vue.use(VueRouter)
Vue.use(VueResource)
Vue.use(VueMaterial)
Vue.use(VueFilter)


// Configuration material theme
Vue.material.registerTheme('default', {
    primary: 'blue',
    accent: 'red',
    warn: 'red',
    background: 'white'
})


// Routing
import routerConfig from './router'
const router = new VueRouter(routerConfig)


// Initialize an app
new Vue({
    router,
    template: `<router-view :notification="notification"></router-view>`,
    data() {
        return {
            notification: {}
        }
    }
}).$mount('#app')


// Configuration vue-resource
Vue.http.options.root = '/api'
Vue.http.interceptors.push((request, next) => {
    request.headers.set('Authorization', AuthService.getToken())
    
    next((response) => {
        if (response.status == 400 || response.status == 401) {
            AuthService.logout()
            router.push({ name: 'auth.login' })
        }
    })
})