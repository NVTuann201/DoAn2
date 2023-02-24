<template>
    <div class="site-wrapper">
        <main class="main">
            <div class="viewContentWrapper">
                <div class="site-content rtl-supported rtl-bootstrap">
                    <div class="site-content__page">
                        <div class="publisherKey light-background">
                            <div class="wrapper-horizontal-stripes occurrenceKey page--data">
                                <Header v-model="this.value"></Header>
                                <section class="horizontal-stripe light-background occurrenceKey__highlights seperator">
                                    <div class="container--normal">
                                        <div class="balanced-row">
                                            <div>
                                                <dl class="inline">
                                                    <div>
                                                        <dt>{{$t('nbds_lang.submenu_species')}}</dt>
                                                        <dd dir="auto">
                                                            <a>
                                                                {{value.scientific_name}}
                                                            </a>
                                                        </dd>
                                                    </div>
                                                    <div>
                                                        <dt>Location</dt>
                                                        <dd class="classification-list">
                                                            <span>{{value.region.name_vietnamese}}</span>
                                                            <span>{{value.province.name_vietnamese}}</span>
                                                            <span>{{value.district.name_vietnamese}}</span>
                                                            <span>{{value.locality}}</span>
                                                        </dd>
                                                    </div>
                                                    <div>
                                                        <dt>{{$t('nbds_lang.darwin_core_occurrences.basis_of_record')}}</dt>
                                                        <dd>{{value.basis_of_record}}</dd>
                                                    </div>
                                                </dl>
                                            </div>
                                            <div>
                                                <dl class="inline">
                                                    <div>
                                                        <dt>{{$t('nbds_lang.submenu_dataset')}}</dt>
                                                        <dd>
                                                            <a :href="'/detail/dataset/' + value.data_resource.id">
                                                                {{value.data_resource.title}}
                                                            </a>
                                                        </dd>
                                                    </div>
                                                    <div>
                                                        <dt>{{$t('nbds_lang.submenu_publisher')}}</dt>
                                                        <dd>
                                                            <a :href="'/detail/publisher/' + value.data_resource.organization.id">
                                                                {{value.data_resource.organization.name}}
                                                            </a>
                                                        </dd>
                                                    </div>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section class="horizontal-stripe white-background occurrenceKey__details seperator">
                                    <div class="container--normal">
                                        <div class="row scrollable">
                                            <div class="occurrence-key__term-groups">
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.record')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.institution_code')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.institution_code}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        {{$t('nbds_lang.darwin_core_occurrences.basis_of_record')}}
                                                                    </td>
                                                                    <td>{{value.basis_of_record}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.collection_code')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.collection_code}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.occurrence')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.individual_count')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.individual_count}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.life_stage')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.life_stage}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.catalog_number')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.catalog_number}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.event')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.day')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.day}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.month')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.month}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.year')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.year}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.event_date')}}
                                                                    </td>
                                                                    <td>{{value.event_date}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.taxon')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.kingdom')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.king_dom.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.phylum')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.phylum.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.class')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.class.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.order')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.order.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.family')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.family.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.genus')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.genus.name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.specific_epithet')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.specific_epithet}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.scientific_name')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.scientific_name}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.taxon_rank')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.darwin_core_taxon.taxon_rank}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.taxonomic_status')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.taxonomic_status}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.location')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.country')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.country}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.country_code')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.country_code}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.decimal_latitude')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.decimal_latitude}}</td>
                                                                </tr>
                                                                <tr class="isDifferent">
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.decimal_longitude')}}
                                                                    </td>
                                                                    <td>{{value.decimal_longitude}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">
                                                                        {{$t('nbds_lang.darwin_core_occurrences.locality')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.locality}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                                <section class="term-block is-collapsed">
                                                    <div class="term-block__header">
                                                        <h4>{{$t('nbds_lang.darwin_core_occurrences.other')}}</h4>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>Term</th>
                                                                    <th>Interpreted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                         {{$t('nbds_lang.darwin_core_occurrences.references')}}
                                                                    </td>
                                                                    <td dir="auto">{{value.references}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <section class="horizontal-stripe light-background seperator extensions ng-scope" v-if="value.data_resource.citation">
                <div class="container--normal">
                    <div class="row">
                        <div class="col-xs-12">
                            <h5 class="card-header ng-scope" translate="occurrence.citation">Citation</h5>
                            <div class="card card--spaced">
                                <div class="card__content">
                                    <div dir="auto">
                                        {{value.data_resource.citation}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
    import * as env from "../../../env.js";
    import Header from "./header-detail";
    export default {
        components: {
            Header,
        },
        props: ['value'],
    }
</script>

