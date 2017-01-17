import Echo from 'laravel-echo'

const LOGIN_URL = 'auth/login'

export default {
    
    user: {
        authenticated: false
    },
    
    // Send a request to the login URL and save the returned JWT
    login(context, creds) {
        return context.$http.post(LOGIN_URL, creds).then(
            (response) => {
                context.errors = {}
                
                localStorage.setItem('token', response.body.token)
    
                this.user.authenticated = true
            },
            (error) => {
                context.errors = error
            }
        )
    },
    
    // To log out, we just need to remove the token
    logout() {
        localStorage.removeItem('token')
        this.user.authenticated = false
        if (window.Echo instanceof Echo) {
            window.Echo.leave('orders')
        }
    },
    
    loggedIn() {
        var jwt = localStorage.getItem('token')
        if(jwt) {
            this.user.authenticated = true
        } else {
            this.user.authenticated = false
        }
        
        return this.user.authenticated
    },
    
    // The object to be passed as a header for authenticated requests
    getToken() {
        return 'Bearer ' + localStorage.getItem('token')
    }
}