<template>
    <md-table-row>

        <!-- Role name -->
        <md-table-cell>{{ user.role_name }}</md-table-cell>


        <!-- Full name -->
        <md-table-cell>{{ user.full_name }}</md-table-cell>


        <!-- Email -->
        <md-table-cell>{{ user.email }}</md-table-cell>


        <!-- Active status -->
        <md-table-cell width="1">
            <md-icon v-if="user.is_active" class="md-primary">
                check <md-tooltip md-direction="top">Active</md-tooltip>
            </md-icon>
            <md-icon v-if="!user.is_active" class="md-accent">
                clear <md-tooltip md-direction="top">Inactive</md-tooltip>
            </md-icon>
        </md-table-cell>


        <!-- Created at -->
        <md-table-cell class="text-nowrap" width="1">{{ user.created_at | date('%d-%m-%Y') }}</md-table-cell>


        <!-- Actions -->
        <md-table-cell class="table-actions" width="1">

            <!-- Edit button -->
            <router-link class="md-dense md-primary"
                         tag="md-button"
                         :to="{ name: 'users.edit', params: { id: user.id } }">Edit</router-link>

            <!-- Delete button -->
            <md-button id="delete" class="md-dense md-accent" @click="openDialog('user')">Delete</md-button>

        </md-table-cell>


        <!-- Modal deleted dialog -->
        <md-dialog ref="user" md-open-from="#delete" md-close-to="#delete">
            <md-dialog-title>Are you sure?</md-dialog-title>

            <md-dialog-actions>
                <md-button @click="closeDialog('user')">Cancel</md-button>
                <md-button class="md-accent" @click="onDelete">Delete</md-button>
            </md-dialog-actions>
        </md-dialog>

    </md-table-row>
</template>

<script type="text/babel">
    export default {
        props: ['user'],
        methods: {
            onDelete: function() {
                this.closeDialog('user')

                this.$emit('delete', this.user)
            },
            openDialog(ref) {
                this.$refs[ref].open()
            },
            closeDialog(ref) {
                this.$refs[ref].close()
            }
        },
    }
</script>
