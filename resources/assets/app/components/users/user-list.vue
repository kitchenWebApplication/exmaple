<template>
    <div>
        <template v-if="active">

            <!-- Toolbar -->
            <md-toolbar class="md-transparent">

                <!-- Main title -->
                <h1 class="md-headline">Users</h1>

                <!-- Adding button -->
                <router-link tag="md-button" class="md-icon-button md-primary" :to="{ name: 'users.create' }">
                    <md-icon>add</md-icon>
                </router-link>

            </md-toolbar>


            <!-- User list -->
            <md-table v-if="users.length">
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Role</md-table-head>
                        <md-table-head>Full name</md-table-head>
                        <md-table-head>Email</md-table-head>
                        <md-table-head>Active</md-table-head>
                        <md-table-head>Created at</md-table-head>
                        <md-table-head>Actions</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <template v-for="item in users">
                        <user-item :user="item" v-on:delete="deleteUser(arguments[0])"></user-item>
                    </template>
                </md-table-body>
            </md-table>


            <!-- "No items" -->
            <md-card-content v-if="!users.length" style="padding-bottom: 16px;">
                <span class="md-caption">Sorry, no items found.</span>
            </md-card-content>

        </template>
    </div>
</template>

<script type="text/babel">
    import UserItem from './user-item.vue'

    export default {
        components: {
            'user-item': UserItem
        },
        created() {
            this.getUsers()
        },
        data() {
            return {
                active: false,
                users: []
            }
        },
        methods: {
            getUsers: function() {
                return this.$http.get('users').then(
                    (response) => {
                        this.users = response.body.users

                        this.active = true
                    }
                )
            },
            deleteUser: function(user) {
                this.$http.delete(`users/${user.id}`).then(
                    (response) => {
                        var index = this.users.indexOf(user)
                        this.users.splice(index, 1)

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
            }
        }
    }
</script>
