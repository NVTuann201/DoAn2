<template>
    <span v-if="value">
        <a href="" id="abstract"></a>
        <div class="anchor-block--tabs">
            <h3>{{$t('nbds_lang.dataset_resources.abstract')}}</h3>
            <p v-if="value">{{this.value}}</p>
            <p v-else>{{$t('nbds_lang.no_information')}}</p>
        </div>
    </span>
</template>

<script>
export default {
    props: ['value'],
    data: function() {
        return {
        }
    },
    created() {
    }
}
</script>

