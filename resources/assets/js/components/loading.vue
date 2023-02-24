<template>
    <div v-bind:style="[baseStyles]">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
        </svg>
    </div>
</template>

<script>
export default {
    props: {
        height: {
            default: 25,
            type: Number
        },
        width: {
            default: 25,
            type: Number
        }
    },
    computed: {
        baseStyles: function() {
            return {
                position: 'relative',
                height: this.height + 'px',
                width: this.width + 'px',
            }
        }
    }
}
</script>

