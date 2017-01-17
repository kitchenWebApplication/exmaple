<template>
    <div class="auth-form">
        <h1 class="md-headline text-center">Please, login into your account</h1>
        <md-card md-with-hover>
            <md-card-content>
                <form @submit.stop.prevent="submit()">

                    <!-- Email -->
                    <md-input-container :class="{ 'md-input-invalid': error.email }">
                        <label>Email</label>
                        <md-input type="email" v-model="user.email"></md-input>
                        <span class="md-error" v-for="message of error.email">{{ message }}</span>
                    </md-input-container>

                    <!-- Password -->
                    <md-input-container :class="{ 'md-input-invalid': error.password }">
                        <label>Password</label>
                        <md-input type="password" v-model="user.password"></md-input>
                        <span class="md-error" v-for="message of error.password">{{ message }}</span>
                    </md-input-container>

                    <!-- Submit button -->
                    <md-button type="submit" class="md-primary md-raised" style="margin: 0;">Login</md-button>

                </form><!-- /.col -->
            </md-card-content>
        </md-card>
    </div><!-- /.auth-form -->
</template>

<script type="text/babel">
    import AuthService from '../../auth'

    export default {
        data() {
            return {
                errors: {},
                error: {
                    email: null
                },
                user: {}
            }
        },
        methods: {
            submit: function() {
                AuthService.login(this, this.user).then(() => {
                    if (this.errors.status == 422) {
                        this.error = this.errors.body
                    } else if (this.errors.status == 401) {
                        this.error.email = [this.errors.body.error]
                    } else if (!this.errors.status || this.errors.status == 200) {
                        this.$router.push({ name: 'orders.list' })
                    }
                })
            }
        }
    }
</script>
