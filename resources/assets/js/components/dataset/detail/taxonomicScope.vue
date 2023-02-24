<template>    
    <span>
        <a href="" id="taxonomicCoverages"></a>
        <div class="anchor-block--tabs">
            <h3>{{$t('nbds_lang.dataset_resources.taxonomic_coverage')}}</h3>        
            <p>{{this.taxonomic_coverage}}</p>
            <div class="dataset-key__taxonomicCoverages__rank" v-if="(this.king_doms && this.king_doms.length > 0) || (this.phylums && this.phylums.length > 0)">
                <h4 >{{$t('nbds_lang.taxon_kingdom')}}</h4>
                <div class="dataset-key__taxonomicCoverages__columns">
                    <span class="no-break" v-for="item in this.king_doms">
                        <a href="#">
                            <span v-if="item.name_vietnamese">{{item.name + ' (' + item.name_vietnamese + ')'}}</span>               
                            <span v-else>{{item.name}}</span>      
                        </a>
                    </span>
                </div>
                <h4 >{{$t('nbds_lang.taxon_phylum')}}</h4>
                <div class="dataset-key__taxonomicCoverages__columns">
                    <span class="no-break" v-for="item in this.phylums">
                        <a href="#">                            
                            <span v-if="item.name_vietnamese">{{item.name + ' (' + item.name_vietnamese + ')'}}</span>               
                            <span v-else>{{item.name}}</span>               
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </span>
</template>

<script>
export default {
    props: ['taxonomic_coverage', 'king_doms', 'phylums']
}
</script>

