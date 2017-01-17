import auth from './auth'

import Dashboard   from './components/dashboard/dashboard.vue'
import Auth        from './components/auth/auth.vue'
import Login       from './components/auth/login.vue'

import Profile     from './components/profile/profile.vue'
import ProfileEdit from './components/profile/profile-edit.vue'

import Users       from './components/users/users.vue'
import UserList    from './components/users/user-list.vue'
import UserCreate  from './components/users/user-create.vue'
import UserEdit    from './components/users/user-edit.vue'

import Orders      from './components/orders/orders.vue'
import OrderList   from './components/orders/order-list.vue'

export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Auth,
            children: [
                {
                    path: '/',
                    component: Login,
                    name: 'auth.login'
                }
            ]
        },
        {
            path: '/dashboard',
            component: Dashboard,
            name: 'dashboard',
            beforeEnter: (to, from, next) => {
                if (!auth.loggedIn()) {
                    next({ name: 'auth.login' })
                } else {
                    next()
                }
            },
            children: [
                
                // Profile
                {
                    path: 'profile',
                    component: Profile,
                    children: [
                        {
                            path: '/',
                            component: ProfileEdit,
                            name: 'profile.edit'
                        }
                    ]
                },
                
                // Users
                {
                    path: 'users',
                    component: Users,
                    children: [
                        {
                            path: '/',
                            component: UserList,
                            name: 'users.list'
                        },
                        {
                            path: 'create',
                            component: UserCreate,
                            name: 'users.create'
                        },
                        {
                            path: ':id',
                            component: UserEdit,
                            name: 'users.edit'
                        }
                    ]
                },
                
                // Orders
                {
                    path: 'orders',
                    component: Orders,
                    children: [
                        {
                            path: '/',
                            component: OrderList,
                            name: 'orders.list'
                        }
                    ]
                }
            ]
        }
    ]
}