<template>
    <div>
        <md-snackbar ref="snackbar"
                     md-position="bottom left"
                     :md-duration="duration"
                     :class="'md-' + state">
            <span>{{ text }}</span>
        </md-snackbar>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['notification'],
        watch: {
            notification: function (value) {
                this.addNotification(value)
            }
        },
        data() {
            return {
                text: null,
                duration: null,
                state: 'primary'
            }
        },
        methods: {
            addNotification: function(item) {
                if (!item.text) return
                
                this.text = item.text
                this.duration = item.duration || 4000
                this.state = item.state || 'primary'

                this.$refs.snackbar.open()
            }
        }
    }
</script>
