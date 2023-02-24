<template>
    <div class="container--desktop">
        <div class="col-md-12 m-t-1 layout-padding layout-gt-sm-row" style="margin-bottom: 50px">
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t("nbds_lang.occurrences") + ' ' + $t("nbds_lang.per") + ' ' + $t("nbds_lang.month")}}</h4>
                <highcharts :options="optionsMonth" ref="highcharts"></highcharts>
            </div>
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t("nbds_lang.occurrences") + ' ' + $t("nbds_lang.per") + ' ' + $t("nbds_lang.year")}}</h4>
                <highcharts :options="options" ref="highcharts" ></highcharts>
            </div>
        </div>
        <div class="col-md-12 m-t-1 layout-padding layout-gt-sm-row" style="margin-bottom: 50px">
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t("nbds_lang.occurrences") + ' ' + $t("nbds_lang.per") + ' ' + $t("nbds_lang.submenu_dataset")}}</h4>
                <highcharts :options="optionsDataset" ref="highcharts" ></highcharts>
            </div>
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t("nbds_lang.occurrences") + ' ' + $t("nbds_lang.per") + ' ' + $t("nbds_lang.province")}}</h4>
                <highcharts :options="optionsProvince" ref="highcharts" ></highcharts>
            </div>
        </div>
        <div class="col-md-12 m-t-1 layout-padding layout-gt-sm-row" style="margin-bottom: 50px">
            <div flex-gt-sm="50" flex="100" class="flex-gt-sm-50 flex-100">
                <h4 class="card-header text-center inline-block ng-scope">{{$t("dataset_type.taxon") + ' ' + $t("nbds_lang.per") + ' ' + $t("nbds_lang.submenu_dataset")}}</h4>
                <highcharts :options="optionsTaxonDataset" ref="highcharts" ></highcharts>
            </div>
        </div>
    </div>
</template>

<script>
    import * as env from "../../../env.js";
    import VueCircle from 'vue2-circle-progress'


    export default {
        props: ['data'],
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
                optionsMonth: {
                    colors: ['#4C8A55'],
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    subtitle: {
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
                        min: 0,
                        title: {
                            text: 'Occurrences '
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        valueSuffix: ' Occurrences'
                    },
                    series: [{
                        name: 'Occurrences',
                        data: [],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            y: 10,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]

                },
                optionsDataset: {
                    colors: ['#4C8A55'],
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    subtitle: {
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
                        min: 0,
                        title: {
                            text: 'Occurrences '
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        valueSuffix: ' Occurrences'
                    },
                    series: [{
                        name: 'Occurrences',
                        data: [],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            y: 10,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]

                },
                optionsProvince: {
                    colors: ['#4C8A55'],
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    subtitle: {
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
                        min: 0,
                        title: {
                            text: 'Occurrences '
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        valueSuffix: ' Occurrences'
                    },
                    series: [{
                        name: 'Occurrences',
                        data: [],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            y: 10,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]

                },
                optionsTaxonDataset: {
                    colors: ['#4C8A55'],
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    subtitle: {
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
                        min: 0,
                        title: {
                            text: 'Taxon '
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        valueSuffix: ' Taxon'
                    },
                    series: [{
                        name: 'Taxon',
                        data: [],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            y: 10,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]

                },
            }
        },
        methods: {
            progress(event, progress, stepValue) {
            },
            progress_end(event) {
            },
            getInfo(){
                axios
                    .get(env.endpointhttp + "species/info-percent", {
                        params: {
                            darwin_core_taxon_tree_id: this.data
                        }
                    })
                    .then(response => {
                        if(response.data.occurrences.length != 0){
                            this.$emit('occurrencesCount', response.data.occurrences[0].count);
                        }
                        this.options.series[0].data = JSON.parse(response.data.datasetWithYear);
                        this.optionsMonth.series[0].data =  JSON.parse(response.data.datasetWithMonth);
                        let occurrencesDataset = response.data.occurrencesDataset;
                        let dataDataset = [];
                        let valueDataset= [];
                        valueDataset[0] = null;
                        valueDataset[1] = null;
                        for (let i in occurrencesDataset){
                            valueDataset[0] = occurrencesDataset[i].title;
                            valueDataset[1] = occurrencesDataset[i].count;
                            dataDataset[i] = valueDataset;
                            valueDataset = [];
                        }
                        this.optionsDataset.series[0].data = dataDataset;
                        let dataProvince = [];
                        let valueProvince = [];
                        valueProvince[0] = null;
                        valueProvince[1] = null;
                        let occurrencesProvince = response.data.occurrencesProvince;
                        for (let i in occurrencesProvince){
                            valueProvince[0] = occurrencesProvince[i].name;
                            valueProvince[1] = occurrencesProvince[i].count;
                            dataProvince[i] = valueProvince;
                            valueProvince = [];
                        }
                        this.optionsProvince.series[0].data = dataProvince;
                        let taxonDataset = response.data.taxonDataset;
                        let dataTaxonDataset = [];
                        let valueTaxonDataset= [];
                        valueTaxonDataset[0] = null;
                        valueTaxonDataset[1] = null;
                        for (let i in taxonDataset){
                            valueTaxonDataset[0] = taxonDataset[i].title;
                            valueTaxonDataset[1] = taxonDataset[i].count;
                            dataTaxonDataset[i] = valueTaxonDataset;
                            valueTaxonDataset = [];
                        }
                        this.optionsTaxonDataset.series[0].data = dataTaxonDataset;
                    });
            }

        },
        created() {
            this.getInfo();
        }
    }
</script>
<style>
    .circle {
        border-radius: unset;
        border: 0px solid;
    }
</style>