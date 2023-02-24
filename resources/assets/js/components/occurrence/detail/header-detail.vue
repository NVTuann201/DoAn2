<template>
    <section class="horizontal-stripe article-header white-background">
        <div class="container--desktop">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <nav class="article-header__category">
                        <span><a href="/search/occurrence" style="cursor: pointer;">{{$t('nbds_lang.submenu_occurrence')}}</a></span>
                        <span
                            class="article-header__category__lower">{{ value.created_at | moment("dddd, MMMM Do YYYY") }}</span>
                    </nav>
                    <h1 class="text-center">
                        <span><span><i>{{value.scientific_name}}</i></span></span>
                    </h1>
                    <div class="h4 article-header__highlights">
                        <div>
                            <span class="vernacular"></span>
                        </div>
                        <span>{{$t('nbds_lang.observed_in')}} {{value.province.name_vietnamese}}</span>
                    </div>
                    <div class="classification-list discreet rtl-supported inline-block">
                        <span><a class="kingdom">{{value.darwin_core_taxon.king_dom.name}}</a></span>
                        <span><a class="phylum">{{value.darwin_core_taxon.phylum.name}}</a></span>
                        <span><a class="class">{{value.darwin_core_taxon.class.name}}</a></span>
                        <span><a class="order">{{value.darwin_core_taxon.order.name}}</a></span>
                        <span><a class="family">{{value.darwin_core_taxon.family.name}}</a></span>
                        <span><a class="genus">{{value.darwin_core_taxon.genus.name}}</a></span>
                    </div>
                </div>
            </div>
            <div class="stripe-comment">
                <div>
                    <span>{{ value.created_at | moment("from", "now") }}</span>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import VueMoment from 'vue-moment'

    export default {
        props: ['value'],
        data: function () {
            return {

            }
        },
        mounted() {

        },
        methods: {
        }
    }
</script>
