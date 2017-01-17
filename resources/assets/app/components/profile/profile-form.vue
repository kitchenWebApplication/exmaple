<template>
    <div>

        <!-- First name -->
        <md-input-container :class="{ 'md-input-invalid': error.first_name }">
            <label>First name</label>
            <md-input v-model="user.first_name"></md-input>
            <span class="md-error" v-for="message of error.first_name">{{ message }}</span>
        </md-input-container>


        <!-- Last name -->
        <md-input-container :class="{ 'md-input-invalid': error.last_name }">
            <label>Last name</label>
            <md-input v-model="user.last_name"></md-input>
            <span class="md-error" v-for="message of error.last_name">{{ message }}</span>
        </md-input-container>


        <!-- Email -->
        <md-input-container :class="{ 'md-input-invalid': error.email }">
            <label>Email</label>
            <md-input v-model="user.email" type="email"></md-input>
            <span class="md-error" v-for="message of error.email">{{ message }}</span>
        </md-input-container>


        <!-- Current password -->
        <md-input-container :class="{ 'md-input-invalid': error.current_password }">
            <label>Current password</label>
            <md-input v-model="user.current_password" type="password"></md-input>
            <span class="md-error" v-for="message of error.current_password">{{ message }}</span>
        </md-input-container>


        <!-- Password -->
        <md-input-container :class="{ 'md-input-invalid': error.password }">
            <label>Password</label>
            <md-input v-model="user.password" type="password"></md-input>
            <span class="md-error" v-for="message of error.password">{{ message }}</span>
        </md-input-container>


        <!-- Confirm password -->
        <md-input-container :class="{ 'md-input-invalid': error.password_confirmation }">
            <label>Confirm password</label>
            <md-input v-model="user.password_confirmation" type="password"></md-input>
            <span class="md-error" v-for="message of error.password_confirmation">{{ message }}</span>
        </md-input-container>


        <!-- Is active -->
        <md-input-container :class="{ 'md-input-invalid': error.is_active }">
            <label>Is active</label>
            <md-switch v-model="user.is_active" class="md-primary"></md-switch>
            <span class="md-error" v-for="message of error.is_active">{{ message }}</span>
        </md-input-container>

    </div>
</template>

<script type="text/babel">
    export default {
        props: ['user', 'error']
    }
</script>
