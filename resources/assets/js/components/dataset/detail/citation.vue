<template>
    <span v-if="citation">
        <a href="" id="citation"></a>
        <div class="anchor-block--tabs">
            <h3>{{$t('nbds_lang.dataset_resources.citation')}}</h3>
            <div class="small" v-if="this.citation">
                {{this.citation}}
            </div>
            <div class="dataset-key__taxonomicCoverages__rank" v-if="this.references && this.references.length > 0">
                <h4 >{{$t('nbds_lang.table_dataset_references')}}</h4>
                <div class="dataset-key__taxonomicCoverages__columns">
                    <span class="no-break" v-for="item in this.references">
                        <a href="#">
                            <span>{{item.name}}</span>
                        </a>
                    </span>
                </div>
            </div>
            <p v-if="!this.citation && !(this.references && this.references.length > 0)">{{$t('nbds_lang.no_information')}}</p>
        </div>
    </span>
</template>

<script>
export default {
    props: ['citation', 'references'],
    data: function() {
        return{
        }
    },
    created() {

    }
}
</script>

