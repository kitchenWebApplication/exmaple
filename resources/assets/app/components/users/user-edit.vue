<template>
    <div>
        <template v-if="active">

            <!-- Main title -->
            <md-toolbar class="md-transparent">
                <h1 class="md-headline">Update user #{{ user.id }}</h1>
            </md-toolbar>

            <form class="main-form" @submit.stop.prevent="submit()">

                <!-- Form -->
                <user-form :user="user" :roles="roles" :error="error"></user-form>

                <!-- Submit button -->
                <md-button type="submit" class="md-raised md-primary">Update</md-button>

            </form>
        </template>
    </div>
</template>

<script type="text/babel">
    import UserForm from './user-form.vue'

    export default {
        components: {
            'user-form': UserForm
        },
        created() {
            this.getRoles().then(() => {
                this.getUser().then(() => {
                    this.active = true
                })
            })
        },
        data() {
            return {
                active: false,
                error: {},
                user: {},
                roles: []
            }
        },
        methods: {
            getUser: function() {
                let id = this.$route.params.id

                return this.$http.get(`users/${id}`).then(
                    (response) => {
                        this.user = response.body.user
                    }
                )
            },
            getRoles: function() {
                return this.$http.get('roles').then(
                    (response) => {
                        this.roles = response.body.roles
                    }
                )
            },
            submit: function() {
                this.$http.put(`users/${this.user.id}`, this.user).then(
                    (response) => {
                        this.$root.notification = {
                            text: response.body.message
                        }

                        this.$router.push({ name: 'users.list' })
                    },
                    (error) => {
                        if (error.status == 422) {
                            this.error = error.body

                            this.user.password = this.user.password_confirmation = ''
                        } else {
                            this.$root.notification = {
                                text: error.statusText,
                                state: 'accent'
                            }
                        }
                    }
                )
            }
        },
    }
</script>
