<template>
    <div>

        <!-- Main title -->
        <md-toolbar class="md-transparent">
            <h1 class="md-headline">Update profile</h1>
        </md-toolbar>

        <form class="main-form" @submit.stop.prevent="submit()" v-if="active">

            <!-- Form -->
            <profile-form :user="user" :error="error" v-if="active"></profile-form>

            <!-- Submit button -->
            <md-button type="submit" class="md-raised md-primary">Save</md-button>

        </form>
    </div>
</template>

<script type="text/babel">
    import ProfileForm from './profile-form.vue'

    export default {
        components: {
            'profile-form': ProfileForm
        },
        created() {
            this.getProfile().then(() => {
                this.active = true
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
            getProfile: function() {
                return this.$http.get('profile').then(
                    (response) => {
                        this.user = response.body.user
                    }
                )
            },
            submit: function() {
                this.$http.put('profile', this.user).then(
                    (response) => {
                        this.$root.notification = {
                            text: response.body.message
                        }

                        this.error = {}
                    },
                    (error) => {
                        if (error.status == 422) {
                            this.error = error.body

                            this.user.password = this.user.password_confirmation = ''
                        } else {
                            this.$root.notification = {
                                text: error.error || error.statusText,
                                state: 'accent'
                            }
                        }
                    }
                )
            }
        }
    }
</script>
