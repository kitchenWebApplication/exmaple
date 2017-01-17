<template>
    <div>
        <md-spinner :md-size="150" md-indeterminate class="main-spinner" v-if="!active"></md-spinner>
        <md-layout class="main-wrapper" v-if="active">

            <!-- Sidebar -->
            <md-sidenav class="main-sidebar md-left md-fixed" ref="leftSidenav" @open="open('Left')" @close="close('Left')">

                <!-- Main title -->
                <md-toolbar class="md-transparent">
                    <div class="md-toolbar-container">
                        <h3 class="md-title">Kitchen</h3>
                    </div>
                </md-toolbar>

                <!-- Menu list -->
                <div class="main-sidebar-links">
                    <md-list>
                        <md-list-item v-if="$root.profile.role == 'manager'">
                            <router-link :to="{ name: 'users.list' }">
                                <md-icon>supervisor_account</md-icon> <span>Users</span>
                            </router-link>
                        </md-list-item>
                        <md-list-item>
                            <router-link :to="{ name: 'orders.list' }">
                                <md-icon>swap_horiz</md-icon> <span>Orders</span>
                            </router-link>
                        </md-list-item>
                    </md-list>
                </div><!-- /.main-sidebar-links -->
            </md-sidenav><!-- /.main-sidebar -->

            <!-- Main container -->
            <div class="page-content">
                <md-toolbar class="main-toolbar">
                    <div class="md-toolbar-container">
                        <md-button class="md-icon-button nav-trigger" @click="toggleLeftSidenav">
                            <md-icon>menu</md-icon>
                            <md-tooltip>Menu</md-tooltip>
                        </md-button>

                        <h1 class="md-title">{{ $root.profile.full_name }} <small>({{ $root.profile.role_name }})</small></h1>

                        <router-link tag="md-button" :to="{ name: 'profile.edit' }" class="md-icon-button">
                            <md-icon>person</md-icon>
                            <md-tooltip>Profile</md-tooltip>
                        </router-link>

                        <md-button class="md-icon-button" @click="logout()">
                            <md-icon>power_settings_new</md-icon>
                            <md-tooltip>Logout</md-tooltip>
                        </md-button>
                    </div>
                </md-toolbar>
                <main class="main-content">
                    <transition name="fade" mode="out-in">
                        <router-view></router-view>
                    </transition>
                </main>
            </div><!-- /.page-content -->
        </md-layout><!-- /.main-wrapper -->


        <!-- Main notification -->
        <notification :notification="notification"></notification>


        <!-- New order dialog -->
        <md-dialog ref="new-order">
            <md-dialog-title>{{ orderDialog.title }}</md-dialog-title>

            <md-dialog-content>
                <div class="md-subheading" style="margin-bottom: 5px;">Order information</div>
                <div><strong>Table number:</strong> {{ order.table_number }}</div>
                <div><strong>Dish name:</strong> {{ order.dish_name }}</div>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-raised md-primary"
                           tag="md-button"
                           @click="closeDialog('new-order')">OK</md-button>
            </md-dialog-actions>
        </md-dialog>

    </div>
</template>

<script type="text/babel">
    import Echo         from "laravel-echo"
    import AuthService  from '../../auth'
    import Notification from '../notification/notification.vue'

    export default {
        props: ['notification'],
        components: {
            'notification': Notification
        },
        created() {
            this.$nextTick(function () {
                this.authenticate().then(() => {
                    this.active = true

                    // Configure "laravel-echo" server
                    window.Echo = new Echo({
                        broadcaster: 'socket.io',
                        host: 'http://localhost:6001',
                        auth: {
                            headers: {
                                'Authorization': AuthService.getToken()
                            }
                        }
                    })

                    window.Echo.private('orders')
                        .listen('OrderStatusUpdated', (response) => {
                            let order = response.order

                            if (order.status_id != 3 && response.user_id == this.$root.profile.id) {
                                return
                            }

                            this.order = order
                            if (order.status_id == 1) {
                                this.orderDialog.title = 'New order'
                            } else if (order.status_id == 2) {
                                this.orderDialog.title = 'Start cooking'
                            } else if (order.status_id == 3) {
                                this.orderDialog.title = 'Finish cooking'
                            }
                            this.openDialog('new-order')
                        })
                })
            })
        },
        data() {
            return {
                active: false,
                order: {},
                orderDialog: { title: null }
            }
        },
        methods: {
            authenticate: function() {
                return this.$http.get('auth/verify').then(
                    (response) => {
                        this.$root.profile = response.body.user
                    }
                )
            },
            logout: function() {
                AuthService.logout()
                this.$router.push({ name: 'auth.login' })
            },
            toggleLeftSidenav() {
                this.$refs.leftSidenav.toggle()
            },
            openDialog(ref) {
                this.$refs[ref].open()
            },
            closeDialog(ref) {
                this.$refs[ref].close()
                this.$router.push({ name: 'orders.list' })
            }
        }
    }
</script>
