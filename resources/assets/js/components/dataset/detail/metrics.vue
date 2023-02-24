<template>
    <div class="container--desktop">
        <div class="col-md-12 m-t-1 layout-padding layout-gt-sm-row">
            <div flex="100" class="flex-100">
                <h3 class="card-header ng-scope">{{$t('nbds_lang.occurrence_metrics')}}</h3>
                <div class="card">
                    <div class="card__content container-fluid layout-padding">
                        <div class="row summaryStats rtl-bootstrap ng-scope" ng-if="datasetKey.occurrences.count">
                            <a class="col-xs-12 col-sm-6 col-md-3 summaryStats__item">
                                <div>
                                    <div class="summaryStats__circle summaryStats__circle--occurrences"></div>
                                    <div class="summaryStats__percentage">
                                        <span class="ng-binding">{{dataset.darwin_core_occurrences_count}}</span>
                                        <div class="ng-scope">
                                            {{$t('nbds_lang.occurrences')}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a class="col-xs-12 col-sm-6 col-md-3 summaryStats__item ng-scope">
                                <div>
                                    <div class="summaryStats__circle" v-if="countTaxon">
                                        <vue-circle
                                                :progress="countTaxon"
                                                :size="50"
                                                :reverse="false"
                                                line-cap="round"
                                                :fill="fill"
                                                empty-fill="rgba(0, 0, 0, .1)"
                                                :animation-start-value="0.0"
                                                :start-angle="-3.14/2"
                                                insert-mode="append"
                                                :thickness="5"
                                                :show-percent="false"
                                                @vue-circle-progress="progress"
                                                @vue-circle-end="progress_end">
                                        </vue-circle>
                                    </div>
                                    <div class="summaryStats__circle" v-show="!countTaxon">
                                        <vue-circle
                                                :progress="0"
                                                :size="50"
                                                :reverse="false"
                                                line-cap="round"
                                                :fill="fill"
                                                empty-fill="rgba(0, 0, 0, .1)"
                                                :animation-start-value="0.0"
                                                :start-angle="-3.14/2"
                                                insert-mode="append"
                                                :thickness="5"
                                                :show-percent="false"
                                                @vue-circle-progress="progress"
                                                @vue-circle-end="progress_end">
                                        </vue-circle>
                                    </div>
                                    <div class="summaryStats__percentage">
                                        <span class="ng-binding">{{countTaxon}} %</span>
                                        <div class="ng-scope">{{$t('nbds_lang.with_taxon_match')}}
                                        </div>
                                    </div>
                                </div>
                            </a><a class="col-xs-12 col-sm-6 col-md-3 summaryStats__item ng-scope">
                            <div>
                                <div class="summaryStats__circle" v-if="countCoordinates">
                                    <vue-circle
                                            :progress="countCoordinates"
                                            :size="50"
                                            :reverse="false"
                                            line-cap="round"
                                            :fill="fill"
                                            empty-fill="rgba(0, 0, 0, .1)"
                                            :animation-start-value="0.0"
                                            :start-angle="-3.14/2"
                                            insert-mode="append"
                                            :thickness="5"
                                            :show-percent="false"
                                            @vue-circle-progress="progress"
                                            @vue-circle-end="progress_end">
                                    </vue-circle>
                                </div>
                                <div class="summaryStats__circle" v-show="!countCoordinates">
                                    <vue-circle
                                            :progress="0"
                                            :size="50"
                                            :reverse="false"
                                            line-cap="round"
                                            :fill="fill"
                                            empty-fill="rgba(0, 0, 0, .1)"
                                            :animation-start-value="0.0"
                                            :start-angle="-3.14/2"
                                            insert-mode="append"
                                            :thickness="5"
                                            :show-percent="false"
                                            @vue-circle-progress="progress"
                                            @vue-circle-end="progress_end">
                                    </vue-circle>
                                </div>
                                <div class="summaryStats__percentage">
                                    <span class="ng-binding">{{countCoordinates}}%</span>
                                    <div class="ng-scope">{{$t('nbds_lang.with_coordinates')}}
                                    </div>
                                </div>
                            </div>
                        </a><a
                                ui-sref="occurrenceSearchTable({dataset_key:datasetKey.key, year: '*,3000'})"
                                class="col-xs-12 col-sm-6 col-md-3 summaryStats__item ng-scope"
                                ng-if="datasetKey.withYear.$resolved"
                        >
                            <div>
                                <div class="summaryStats__circle" v-if="countYear">
                                    <vue-circle
                                            :progress='countYear'
                                            :size="50"
                                            :reverse="false"
                                            line-cap="round"
                                            :fill="fill"
                                            empty-fill="rgba(0, 0, 0, .1)"
                                            :animation-start-value="0.0"
                                            :start-angle="-3.14/2"
                                            insert-mode="append"
                                            :thickness="5"
                                            :show-percent="false"
                                            @vue-circle-progress="progress"
                                            @vue-circle-end="progress_end">
                                    </vue-circle>
                                </div>
                                <div class="summaryStats__circle" v-show="!countYear">
                                    <vue-circle
                                            :progress="0"
                                            :size="50"
                                            :reverse="false"
                                            line-cap="round"
                                            :fill="fill"
                                            empty-fill="rgba(0, 0, 0, .1)"
                                            :animation-start-value="0.0"
                                            :start-angle="-3.14/2"
                                            insert-mode="append"
                                            :thickness="5"
                                            :show-percent="false"
                                            @vue-circle-progress="progress"
                                            @vue-circle-end="progress_end">
                                    </vue-circle>
                                </div>
                                <div class="summaryStats__percentage">
                                    <span class="ng-binding">{{countYear}}%</span>
                                    <div class="ng-scope">{{$t('nbds_lang.with_year')}}</div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 m-t-1 layout-padding layout-gt-sm-row" style="margin-bottom: 50px">
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t('nbds_lang.taxonomic_distribution_of_occurrences')}}</h4>
                <div class="card  layout-fill layout-padding">
                    <div class="ng-isolate-scope">
                        <div class="occurrenceTaxonomyTree rtl-supported">
                            <ul class="occurrenceTaxonomyTree__root">
                                <li>
                                    <div class="pipe"></div>
                                    <div class="title occurrenceTaxonomyTree__toggle" dir="auto">
                                        <a href="#"
                                           class="ng-scope isActive">Explore</a>
                                    </div>
                                    <ul class="hasTreeLines ng-scope">
                                        <li v-for="(item, index) in datatree" class="ng-scope">
                                            <div class="pipe"></div>
                                            <div class="title" :style="item.name ? '': 'color: #919191;'">
                                                <a dir="auto" style="cursor:pointer"
                                                   class="inherit noUnderline ng-binding"
                                                   @click="getdataPhylum(item, index)">{{item.name
                                                    ? item.name : 'Unknown'}}</a>
                                                <a dir="auto" href="#"
                                                   class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{item.count}}</a>
                                            </div>
                                            <ul class="hasTreeLines ng-scope" v-if="item.phylums != 0">
                                                <li v-for="(phylum, c) in item.phylums" class="ng-scope">
                                                    <div class="pipe"></div>
                                                    <div class="title" :style="phylum.name ? '': 'color: #919191;'">
                                                        <a dir="auto" style="cursor:pointer"
                                                           class="inherit noUnderline ng-binding"
                                                           @click="getDataChildrenPhylum(item, phylum, index, c)">{{phylum.name
                                                            ? phylum.name : 'Unknown'}}</a>
                                                        <a dir="auto" href="#"
                                                           class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{phylum.count}}</a>
                                                    </div>
                                                    <ul class="hasTreeLines ng-scope" v-if="phylum.classes != 0">
                                                        <li v-for="(classes, d) in phylum.classes" class="ng-scope">
                                                            <div class="pipe"></div>
                                                            <div class="title"
                                                                 :style="classes.name ? '': 'color: #919191;'">
                                                                <a dir="auto" style="cursor:pointer"
                                                                   class="inherit noUnderline ng-binding"
                                                                   @click="getDataChildrenClass(item, phylum, classes, index, c, d)">{{classes.name
                                                                    ? classes.name : 'Unknown'}}</a>
                                                                <a dir="auto" href="#"
                                                                   class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{classes.count}}</a>
                                                            </div>
                                                            <ul class="hasTreeLines ng-scope"
                                                                v-if="classes.orders != 0">
                                                                <li v-for="(order, e) in classes.orders"
                                                                    class="ng-scope">
                                                                    <div class="pipe"></div>
                                                                    <div class="title"
                                                                         :style="order.name ? '': 'color: #919191;'">
                                                                        <a dir="auto" style="cursor:pointer"
                                                                           class="inherit noUnderline ng-binding"
                                                                           @click="getDataChildrenOrders(item, phylum, classes, order, index, c, d, e)">{{order.name
                                                                            ? order.name : 'Unknown'}}</a>
                                                                        <a dir="auto" href="#"
                                                                           class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{order.count}}</a>
                                                                    </div>
                                                                    <ul class="hasTreeLines ng-scope"
                                                                        v-if="order.families != 0">
                                                                        <li v-for="(family, g) in order.families"
                                                                            class="ng-scope">
                                                                            <div class="pipe"></div>
                                                                            <div class="title"
                                                                                 :style="family.name ? '': 'color: #919191;'">
                                                                                <a dir="auto" style="cursor:pointer"
                                                                                   class="inherit noUnderline ng-binding"
                                                                                   @click="getDataChildrenFamily(item, phylum, classes, order, family, index, c, d, e, g)">{{family.name
                                                                                    ? family.name : 'Unknown'}}</a>
                                                                                <a dir="auto" href="#"
                                                                                   class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{family.count}}</a>
                                                                            </div>
                                                                            <ul class="hasTreeLines ng-scope"
                                                                                v-if="family.genus != 0">
                                                                                <li v-for="(genus, u) in family.genus"
                                                                                    class="ng-scope">
                                                                                    <div class="pipe"></div>
                                                                                    <div class="title"
                                                                                         :style="genus.name ? '': 'color: #919191;'">
                                                                                        <a dir="auto"
                                                                                           style="cursor:pointer"
                                                                                           class="inherit noUnderline ng-binding"
                                                                                           @click="getDataChildrenGenus(item, phylum, classes, order, family, genus, index, c, d, e, g, u)">{{genus.name
                                                                                            ? genus.name :
                                                                                            'Unknown'}}</a>
                                                                                        <a dir="auto" href="#"
                                                                                           class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{genus.count}}</a>
                                                                                    </div>
                                                                                    <ul class="hasTreeLines ng-scope"
                                                                                        v-if="genus.species != 0">
                                                                                        <li v-for="(species, k) in genus.species"
                                                                                            class="ng-scope">
                                                                                            <div class="pipe"></div>
                                                                                            <div class="title"
                                                                                                 :style="species.name ? '': 'color: #919191;'">
                                                                                                <a dir="auto"
                                                                                                   style="cursor:pointer"
                                                                                                   class="inherit noUnderline ng-binding">{{species.name
                                                                                                    ? species.name :
                                                                                                    'Unknown'}}</a>
                                                                                                <a dir="auto" href="#"
                                                                                                   class="discreet--very occurrenceTaxonomyTree__filter inherit noUnderline ng-binding">{{species.count}}</a>
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>

                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">Occurrences per Year</h4>
                <highcharts :options="options" ref="highcharts"></highcharts>
            </div>
        </div>
    </div>
</template>

<script>
    import * as env from "../../../env.js";
    import VueCircle from 'vue2-circle-progress'


    export default {
        props: ['value', 'data', 'dataset'],
        components: {
            VueCircle
        },
        data: function () {
            return {
                fill: {gradient: ["#43915e"]},
                datatree: [],
                countCoordinates: null,
                countTaxon: null,
                countYear: null,
                options: {
                    colors: ['#4C8A55'],
                    chart: {
                        type: 'spline',
                        scrollablePlotArea: {
                            minWidth: 600,
                            scrollPositionX: 1
                        }
                    },
                    title: {
                        text: 'Occurrences',
                        align: 'left'
                    },
                    subtitle: {
                        align: 'left'
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Occurrences'
                        },
                    },
                    tooltip: {
                        valueSuffix: ' Occurrences'
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: false
                            },
                            pointInterval: 3600000, // one hour
                            pointStart: Date.UTC(2018, 1, 13, 0, 0, 0),
                        }
                    },
                    series: [{
                        name: 'Occurrences',
                        data: []

                    }],
                    navigation: {
                        menuItemStyle: {
                            fontSize: '10px'
                        }
                    }
                },
            }
        },
        methods: {
            progress(event, progress, stepValue) {
            },
            progress_end(event) {
            },
            getdataPhylum(value, index) {
                if (value.name) {
                    console.log("this.datatree[index].children != 0::", this.datatree[index].phylums != 0);
                    if (this.datatree[index].phylums == 0) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: value.dimension,
                                    kingdom_id: value.kingdom_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                this.datatree[index].phylums = response.data.datatree;
                            });
                    } else {
                        console.log(2);
                        this.datatree[index].phylums = 0;
                    }
                }
            },
            getDataChildrenPhylum(valueKingdom, value, index, c) {
                console.log("valueKingdom::", valueKingdom);
                if (value.name) {
                    console.log("this.datatree[index].children != 0::", this.datatree[index].phylums[c].classes == 0);
                    if (this.datatree[index].phylums[c].classes == 0) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: value.dimension,
                                    kingdom_id: valueKingdom.kingdom_id,
                                    phylum_id: value.phylum_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                this.datatree[index].phylums[c].classes = response.data.datatree;
                            });
                    } else {
                        console.log(2);
                        this.datatree[index].phylums[c].classes = 0;
                    }
                }

            },
            getDataChildrenClass(valueKingdom, valuePhylum, classes, index, c, d) {
                if (classes.name) {
                    console.log("this.datatree[index].phylums[c].classes[d].orders", this.datatree[index].phylums[c].classes[d].orders == 0);
                    if (this.datatree[index].phylums[c].classes[d].orders == 0) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: classes.dimension,
                                    kingdom_id: valueKingdom.kingdom_id,
                                    phylum_id: valuePhylum.phylum_id,
                                    class_id: classes.class_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                this.datatree[index].phylums[c].classes[d].orders = response.data.datatree;
                            });
                    } else {
                        console.log(2);
                        this.datatree[index].phylums[c].classes[d].orders = 0;
                    }
                }
            },
            getDataChildrenOrders(valueKingdom, valuePhylum, classes, order, index, c, d, e) {
                if (order.name) {
                    console.log("this.datatree[index].phylums[c].classes[d].orders[e].families", this.datatree[index].phylums[c].classes[d].orders[e].families == 0);
                    if (this.datatree[index].phylums[c].classes[d].orders[e].families == 0) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: order.dimension,
                                    kingdom_id: valueKingdom.kingdom_id,
                                    phylum_id: valuePhylum.phylum_id,
                                    class_id: classes.class_id,
                                    order_id: order.order_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                console.log("this.response::", response);
                                this.datatree[index].phylums[c].classes[d].orders[e].families = response.data.datatree;

                            });
                    } else {
                        console.log(2);
                        this.datatree[index].phylums[c].classes[d].orders[e].families = 0;
                    }
                }
            },
            getDataChildrenFamily(valueKingdom, valuePhylum, classes, order, family, index, c, d, e, g) {
                if (order.name) {
                    console.log("this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus", this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus == 0);
                    if (this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: family.dimension,
                                    kingdom_id: valueKingdom.kingdom_id,
                                    phylum_id: valuePhylum.phylum_id,
                                    class_id: classes.class_id,
                                    order_id: order.order_id,
                                    family_id: family.family_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                console.log("this.response::", response);
                                this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus = response.data.datatree;

                            });
                    } else {
                        console.log(2);
                        this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus = 0;
                    }
                }
            },
            getDataChildrenGenus(valueKingdom, valuePhylum, classes, order, family, genus, index, c, d, e, g, u) {
                if (order.name) {
                    console.log("this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus[u].species", this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus[u].species == 0);
                    if (this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus[u].species) {
                        console.log(1);
                        axios
                            .get(env.endpointhttp + "dataset/info-tree", {
                                params: {
                                    dimension: genus.dimension,
                                    kingdom_id: valueKingdom.kingdom_id,
                                    phylum_id: valuePhylum.phylum_id,
                                    class_id: classes.class_id,
                                    order_id: order.order_id,
                                    family_id: family.family_id,
                                    genus_id: genus.genus_id,
                                    dataset_resource_id: this.dataset.id,
                                }
                            })
                            .then(response => {
                                console.log("this.response::", response);
                                this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus[u].species = response.data.datatree;

                            });
                    } else {
                        console.log(2);
                        family
                        this.datatree[index].phylums[c].classes[d].orders[e].families[g].genus[u].species = 0;
                    }
                }
            },
            getPercent() {
                axios
                    .get(env.endpointhttp + "dataset/info-percent", {
                        params: {
                            dataset_resource_id: this.dataset.id,
                        }
                    })
                    .then(response => {
                        this.countCoordinates = response.data.count_coordinates;
                        this.countCoordinates = this.countCoordinates / (this.dataset.darwin_core_occurrences_count) * 100;
                        this.countTaxon = response.data.count_taxon;
                        this.countTaxon = this.countTaxon / (this.dataset.darwin_core_occurrences_count) * 100;
                        this.countYear = response.data.count_year;
                        this.countYear = this.countYear / (this.dataset.darwin_core_occurrences_count) * 100;
                    });
            }

        },
        created() {
            console.log("value::", this.value);
            console.log("dataset::", this.dataset);
            this.options.series[0].data = this.value;
            this.datatree = this.data;
            console.log("da  ta metrics::", this.datatree);
            this.getPercent();

        }
    }
</script>
<style>
    .circle {
        border-radius: unset;
        border: 0px solid;
    }
</style>
