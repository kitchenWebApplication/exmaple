<template>
    <div>
        <template v-if="active">

            <!-- Main title -->
            <md-toolbar class="md-transparent">
                <h1 class="md-headline">Create user</h1>
            </md-toolbar>

            <form class="main-form" @submit.stop.prevent="submit()">

                <!-- Form -->
                <user-form :user="user" :roles="roles" :error="error"></user-form>

                <!-- Submit button -->
                <md-button type="submit" class="md-raised md-primary">Create</md-button>

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
            this.getRoles().then((response) => {
                this.user.role = 'manager'
                this.active = true
            })
        },
        data() {
            return {
                active: false,
                error: {},
                user: {
                    role: null,
                    password: null,
                    password_confirmation: null,
                    is_active: true
                },
                roles: []
            }
        },
        methods: {
            getRoles: function() {
                return this.$http.get('roles').then(
                    (response) => {
                        this.roles = response.body.roles
                    }
                )
            },
            submit: function() {
                this.$http.post('users', this.user).then(
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
