<template>
    <div>
        <template v-if="active">

            <!-- Main title -->
            <md-toolbar class="md-transparent">

                <!-- Main title -->
                <h1 class="md-headline">Orders</h1>

                <!-- Adding button -->
                <md-button class="md-icon-button md-primary"
                           v-if="$root.profile.role == 'waiter'"
                           @click="openDialog('order')">
                    <md-icon>add</md-icon>
                    <md-tooltip md-direction="right">Create order</md-tooltip>
                </md-button>

            </md-toolbar>


            <!-- Order list -->
            <md-table v-if="orders.length">
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Waiter</md-table-head>
                        <md-table-head>Table number</md-table-head>
                        <md-table-head>Dish name</md-table-head>
                        <md-table-head>Status</md-table-head>
                        <md-table-head>Created at</md-table-head>
                        <md-table-head>Cooking</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <template v-for="item in orders">
                        <order-item :order="item"
                                    @delete="deleteOrder(arguments[0])"
                                    @update="updateOrder(arguments[0])"></order-item>
                    </template>
                </md-table-body>
            </md-table>


            <!-- "No items" -->
            <p v-if="!orders.length" class="md-caption">Sorry, no items found.</p>


            <!-- Modal dialog for creating order -->
            <md-dialog ref="order">
                <md-dialog-title>Create order</md-dialog-title>

                <md-dialog-content>

                    <!-- Table number -->
                    <md-input-container :class="{ 'md-input-invalid': error.table_number }">
                        <label>Table number</label>
                        <md-input v-model="order.table_number" @keyup.enter="addOrder()"></md-input>
                        <span class="md-error" v-for="message of error.table_number">{{ message }}</span>
                    </md-input-container>

                    <!-- Dish name -->
                    <md-input-container :class="{ 'md-input-invalid': error.dish_name }">
                        <label>Dish name</label>
                        <md-input v-model="order.dish_name" @keyup.enter="addOrder()"></md-input>
                        <span class="md-error" v-for="message of error.dish_name">{{ message }}</span>
                    </md-input-container>
                </md-dialog-content>

                <md-dialog-actions>
                    <md-button class="md-dense" @click="closeDialog('order')">Cancel</md-button>
                    <md-button class="md-primary md-dense" @click="addOrder()">Save</md-button>
                </md-dialog-actions>
            </md-dialog>

        </template>
    </div>
</template>

<script type="text/babel">
    import Echo      from 'laravel-echo'
    import OrderItem from './order-item.vue'

    export default {
        components: {
            'order-item': OrderItem
        },
        created() {
            this.getOrders()

            window.Echo.private('orders')
                .listen('OrderStatusUpdated', (response) => {
                    let order = response.order

                    if (order.status_id != 3 && response.user_id == this.$root.profile.id) {
                        return
                    }

                    if (order.status_id == 1) {
                        this.orders.unshift(order)
                    } else {
                        this.updateOrder(order)
                    }
                })
        },
        data() {
            return {
                active: false,
                error: {},
                orders: [],
                order: {}
            }
        },
        methods: {
            getOrders: function() {
                return this.$http.get('orders').then(
                    (response) => {
                        this.orders = response.body.orders

                        this.active = true
                    }
                )
            },
            addOrder: function() {
                this.$http.post('orders', this.order).then(
                    (response) => {
                        this.closeDialog('order')

                        this.order = {}

                        this.orders.unshift(response.body.data)

                        this.$root.notification = {
                            text: response.body.message
                        }
                    },
                    (error) => {
                        if (error.status == 422) {
                            this.error = error.body
                        } else {
                            this.$root.notification = {
                                text: error.statusText,
                                state: 'accent'
                            }
                        }
                    }
                )
            },
            updateOrder: function(order) {
                var index = _.findIndex(this.orders, { id: order.id })
                this.orders.splice(index, 1, order)
            },
            deleteOrder: function(order) {
                this.$http.delete(`orders/${order.id}`).then(
                    (response) => {
                        var index = this.orders.indexOf(order)
                        this.orders.splice(index, 1)

                        this.$root.notification = {
                            text: response.body.message
                        }
                    },
                    (error) => {
                        this.$root.notification = {
                            text: error.body.message || error.body.error || error.statusText,
                            state: 'accent'
                        }
                    }
                )
            },
            openDialog(ref) {
                this.order = {
                    table_number: null,
                    dish_name: null
                }

                this.$refs[ref].open()
            },
            closeDialog(ref) {
                this.$refs[ref].close()
            }
        }
    }
</script>
