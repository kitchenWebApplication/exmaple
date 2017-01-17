<template>
    <md-table-row>

        <!-- Waiter -->
        <md-table-cell>
            <template v-if="order.user">{{ order.user.full_name }}</template>
        </md-table-cell>


        <!-- Table number -->
        <md-table-cell>
            <md-input-container md-inline v-if="order.is_edit">
                <md-input v-model="order.table_number"></md-input>
            </md-input-container>
            <template v-else>{{ order.table_number }}</template>
        </md-table-cell>


        <!-- Dish name -->
        <md-table-cell>
            <md-input-container md-inline v-if="order.is_edit">
                <md-input v-model="order.dish_name"></md-input>
            </md-input-container>
            <template v-else>{{ order.dish_name }}</template>
        </md-table-cell>


        <!-- Status -->
        <md-table-cell>
            <div v-if="order.status_id == 1">
                <md-icon>alarm_off</md-icon>
                <span class="align-center">New Order</span>
            </div>
            <div v-if="order.status_id == 2 && countdown">
                <md-icon class="md-warn">alarm</md-icon>
                <span>
                    <span>Cooking</span> (<span class="align-center">{{ countdown }}</span>)
                </span>
            </div>
            <div v-if="order.status_id == 3">
                <md-icon class="md-primary">alarm_on</md-icon>
                <span class="align-center">Cooked</span>
            </div>
        </md-table-cell>


        <!-- Created at -->
        <md-table-cell>{{ order.created_at | date('%d-%m-%Y %H:%M') }}</md-table-cell>


        <!-- Actions -->
        <md-table-cell class="table-actions" width="1">

            <!-- "Start Cooking" button -->
            <md-button class="md-dense md-primary"
                       v-if="$root.profile.role == 'manager' && order.status_id == 1"
                       @click="openDialog('time')">Start</md-button>

            <!-- "Finish Cooking" button -->
            <md-button class="md-dense md-warn"
                       v-if="$root.profile.role == 'manager' && order.status_id == 2"
                       @click="finishCooking">Finish</md-button>

            <!-- Delete button -->
            <md-button id="delete" class="md-dense md-accent"
                       v-if="$root.profile.role == 'waiter' && order.status_id == 1"
                       @click="openDialog('order')">Delete</md-button>

        </md-table-cell>


        <!-- Modal dialog for deleting -->
        <md-dialog ref="order">
            <md-dialog-title>Are you sure?</md-dialog-title>
            <md-dialog-actions>
                <md-button class="md-dense" @click="closeDialog('order')">Cancel</md-button>
                <md-button class="md-accent md-dense" @click="onDelete()">Yes</md-button>
            </md-dialog-actions>
        </md-dialog>


        <!-- Modal dialog for setting time -->
        <md-dialog ref="time">
            <md-dialog-title>Please, set cookie time</md-dialog-title>
            <md-dialog-content>
                <md-input-container :class="{ 'md-input-invalid': error.time }">
                    <label>Time</label>
                    <md-input v-model="time" placeholder="01:30" autofocus></md-input>
                    <span class="md-error" v-for="message of error.time">{{ message }}</span>
                </md-input-container>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-dense" @click="closeDialog('time')">Cancel</md-button>
                <md-button class="md-primary md-dense" @click="startCooking();">Save</md-button>
            </md-dialog-actions>
        </md-dialog>

    </md-table-row>
</template>

<script type="text/babel">
    export default {
        props: ['order'],
        created() {
            this.setCountdown(this.order.remaining_time)
        },
        data() {
            return {
                time: null,
                countdown: '00:00:00',
                interval: null,
                error: {}
            }
        },
        watch: {
            order: function(value) {
                if (value.remaining_time) {
                    this.setCountdown(this.order.remaining_time)
                }
            }
        },
        methods: {
            onDelete: function() {
                this.closeDialog('order')

                this.$emit('delete', this.order)
            },
            startCooking: function() {
                let data = {
                    status: 2,
                    time: this.time
                }

                this.$http.put(`orders/${this.order.id}/update-status`, data).then(
                    (response) => {
                        this.closeDialog('time')

                        this.$emit('update', response.body.data)

                        this.setCountdown(response.body.data.remaining_time)
                    },
                    (error) => {
                        if (error.status == 422) {
                            this.error = error.body
                        }
                    }
                )
            },
            finishCooking: function() {
                let data = { status: 3 }

                this.$http.put(`orders/${this.order.id}/update-status`, data).then(
                    (response) => {
                        this.$emit('update', response.body.data)
                    }
                )
            },
            openDialog(ref) {
                this.$refs[ref].open()
            },
            closeDialog(ref) {
                this.$refs[ref].close()
            },
            setCountdown(seconds) {
                this.countdown = null
                clearInterval(this.interval)

                if (!seconds) return

                let eventTime = moment().add(seconds, 'seconds').unix()
                let currentTime = moment().unix()
                let diffTime = eventTime - currentTime
                let duration = moment.duration(diffTime * 1000, 'milliseconds')
                let step = 1000

                this.interval = setInterval(() => {
                    duration = moment.duration(duration - step, 'milliseconds')

                    if (duration.asMilliseconds() == 0) {
                        clearInterval(this.interval)
                        this.countdown = '00:00:00'
                        return
                    }

                    this.countdown = moment.utc(duration.asMilliseconds()).format('HH:mm:ss')
                }, step)
            }
        }
    }
</script>
