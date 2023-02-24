<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <Header v-model="this.value"></Header>
                <section class="horizontal-stripe--paddingless white-background seperator--b">
                  <div class="container--desktop">
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="tabs__wrapper">
                          <div class="tabs__actions">
                            <ul>
                              <li class="tab tab-right" v-if="value.dataset_resources_count > 0">
                                <span>
                                  <a
                                    class="gb-button--primary"
                                    :href="'/search/dataset?publisher=' + value.id"
                                  >
                                    {{value.dataset_resources_count}}
                                    <span
                                      class="ng-isolate-scope"
                                    >{{$t('nbds_lang.submenu_dataset')}}</span>
                                  </a>
                                </span>
                              </li>
                            </ul>
                          </div>
                          <nav class="tabs tabs--noBorder">
                            <ul>
                              <li
                                class="tab"
                                @click="tab = 1"
                                :class="[tab == 1 ? 'isActive' : '']"
                              >
                                <a href="#about">{{$t('nbds_lang.about')}}</a>
                              </li>
                              <li
                              v-if="value.dataset_resources_count > 0"
                                class="tab"
                                @click="tab = 2"
                                :class="[tab == 2 ? 'isActive' : '']"
                              >
                                <a href="#bodulieu">{{$t('nbds_lang.submenu_dataset')}}</a>
                              </li>
                              <li class="tab dropdown" v-if="value.url">
                                <a
                                  :href="value.url"
                                  type="button"
                                  class="dropdown-toggle"
                                  target="_blank"
                                >
                                  <span class="gb-icon-link"></span>
                                  <span>{{$t('nbds_lang.menu_home_desc')}}</span>
                                </a>
                              </li>
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <div class="anchor-block--tabs" v-if="tab == 1">
                  <section class="horizontal-stripe light-background seperator--b" >
                    <div class="container--desktop" v-if="(this.image && this.image != 'null') || value.description ||value.parent  ">
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="card card--spaced container-fluid">
                            <div class="row">
                              <div class="col-xs-12 card__content">
                                <div
                                  class="logoImg pull-right"
                                  v-if="this.image && this.image != 'null'"
                                >
                                  <img :src="this.image" class="publisher_logo" />
                                </div>
                                <div>
                                  <dl class="inline" v-if="value.description">
                                    <div>
                                      <dt>{{$t('nbds_lang.organizations.description')}}</dt>
                                      <dd>{{value.description}}</dd>
                                    </div>
                                    <div v-if="value.parent">
                                      <dt>{{$t('nbds_lang.organizations.parent_organization_id')}}</dt>
                                      <dd>
                                        <a
                                          :href="'/detail/publisher/' + value.parent.id"
                                        >{{value.parent.name}}</a>
                                      </dd>
                                    </div>
                                  </dl>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <section class="horizontal-stripe--paddingless dataset-key__contributors m-t-1">
                      <div class="container--desktop">
                        <h4 class="card-header--bold">{{$t('nbds_lang.organizations.contacts')}}</h4>
                        <div class="contactsCard card card--spaced">
                          <div class="rtl-supported">
                            <div href class="inherit contactsCard__name">
                              <div>
                                <div>{{value.name}}</div>
                              </div>
                            </div>
                            <div class="small discreet">
                              <div>
                                <ul class="inline-bullet-list">
                                  <li v-if="value.contacts">
                                    <span>{{$t('nbds_lang.organizations.contacts') + ': ' + value.contacts}}</span>
                                  </li>
                                  <li v-if="value.address">
                                    <span>{{ $t('nbds_lang.organizations.address') + ': ' + value.address}}</span>
                                  </li>
                                  <li v-if="value.organization_type">
                                    <span>{{$t('nbds_lang.organizations.organization_type') + ': ' + value.organization_type}}</span>
                                  </li>
                                </ul>
                              </div>
                              <div v-if="value.email">
                                <ul class="inline-bullet-list">
                                  <li>
                                    <a :href="'mailto:' + value.email">{{value.email}}</a>
                                  </li>
                                </ul>
                              </div>
                              <div v-if="value.tel">
                                <ul class="inline-bullet-list">
                                  <li>
                                    <a :href="'unsafe:tel:84' + value.tel">{{value.tel}}</a>
                                  </li>
                                </ul>
                              </div>
                              <div v-if="value.url">
                                <ul class="inline-bullet-list">
                                  <li>
                                    <a class="breakAll" :href="value.url">{{value.url}}</a>
                                  </li>
                                </ul>
                              </div>
                              <div v-if="value.portal_url_element">
                                <ul class="inline-bullet-list">
                                  <li>
                                    <a
                                      class="breakAll"
                                      :href="value.portal_url_element"
                                    >{{value.portal_url_element}}</a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                  </section>
                </div>
                <div class="anchor-block--tabs" v-if="tab == 2 && value.dataset_resources_count > 0">
                  <section class="horizontal-stripe light-background seperator--b">
                    <div class="container--desktop">
                      <Dataset :id="value.id"></Dataset>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Header from "./header-detail.vue";
import TabVertical from "../../tabVertical.vue";
import Dataset from "./dataset";
export default {
  props: ["value", "image", "year", "dataset", "protected", "region"],
  components: {
    Header,
    TabVertical,
    Dataset
  },
  data: function () {
    return {
      tab: 1,
      options: {
        colors: ["#4C8A55"],
        chart: {
          type: "spline",
          scrollablePlotArea: {
            minWidth: 600,
            scrollPositionX: 1,
          },
        },
        title: {
          text: "Phát hiện loài",
          align: "left",
        },
        subtitle: {
          align: "left",
        },
        xAxis: {
          type: "category",
          labels: {
            rotation: -45,
            style: {
              fontSize: "13px",
              fontFamily: "Verdana, sans-serif",
            },
          },
        },
        yAxis: {
          title: {
            text: "Phát hiện loài",
          },
        },
        tooltip: {
          valueSuffix: " Phát hiện loài",
        },
        plotOptions: {
          spline: {
            lineWidth: 4,
            states: {
              hover: {
                lineWidth: 5,
              },
            },
            marker: {
              enabled: false,
            },
            pointInterval: 3600000, // one hour
            pointStart: Date.UTC(2018, 1, 13, 0, 0, 0),
          },
        },
        series: [
          {
            name: "Phát hiện loài",
            data: [],
          },
        ],
        navigation: {
          menuItemStyle: {
            fontSize: "10px",
          },
        },
      },
      optionsDataset: {
        colors: ["#4C8A55"],
        chart: {
          type: "column",
        },
        title: {
          text: null,
        },
        subtitle: {},
        xAxis: {
          type: "category",
          labels: {
            rotation: -45,
            style: {
              fontSize: "13px",
              fontFamily: "Verdana, sans-serif",
            },
          },
        },
        yAxis: {
          min: 0,
          title: {
            text: "Phát hiện loài ",
          },
        },
        legend: {
          enabled: false,
        },
        tooltip: {
          valueSuffix: " Phát hiện loài",
        },
        series: [
          {
            name: "Phát hiện loài",
            data: [],
            dataLabels: {
              enabled: true,
              rotation: -90,
              color: "#FFFFFF",
              align: "right",
              y: 10,
              style: {
                fontSize: "13px",
                fontFamily: "Verdana, sans-serif",
              },
            },
          },
        ],
      },
      optionsProtected: {
        colors: ["#4C8A55"],
        chart: {
          type: "column",
        },
        title: {
          text: null,
        },
        subtitle: {},
        xAxis: {
          type: "category",
          labels: {
            rotation: -45,
            style: {
              fontSize: "13px",
              fontFamily: "Verdana, sans-serif",
            },
          },
        },
        yAxis: {
          min: 0,
          title: {
            text: "Phát hiện loài ",
          },
        },
        legend: {
          enabled: false,
        },
        tooltip: {
          valueSuffix: " Phát hiện loài",
        },
        series: [
          {
            name: "Phát hiện loài",
            data: [],
            dataLabels: {
              enabled: true,
              rotation: -90,
              color: "#FFFFFF",
              align: "right",
              y: 10,
              style: {
                fontSize: "13px",
                fontFamily: "Verdana, sans-serif",
              },
            },
          },
        ],
      },
      optionsRegion: {
        colors: ["#4C8A55"],
        chart: {
          type: "column",
        },
        title: {
          text: null,
        },
        subtitle: {},
        xAxis: {
          type: "category",
          labels: {
            rotation: -45,
            style: {
              fontSize: "13px",
              fontFamily: "Verdana, sans-serif",
            },
          },
        },
        yAxis: {
          min: 0,
          title: {
            text: "Phát hiện loài",
          },
        },
        legend: {
          enabled: false,
        },
        tooltip: {
          valueSuffix: " Phát hiện loài",
        },
        series: [
          {
            name: "Phát hiện loài",
            data: [],
            dataLabels: {
              enabled: true,
              rotation: -90,
              color: "#FFFFFF",
              align: "right",
              y: 10,
              style: {
                fontSize: "13px",
                fontFamily: "Verdana, sans-serif",
              },
            },
          },
        ],
      },
    };
  },
  created() {
    this.options.series[0].data = this.year;
    let occurrencesDataset = this.dataset;
    let dataDataset = [];
    let valueDataset = [];
    valueDataset[0] = null;
    valueDataset[1] = null;
    for (let i in occurrencesDataset) {
      valueDataset[0] =
        occurrencesDataset[i].title + "- id: " + occurrencesDataset[i].id;
      valueDataset[1] = occurrencesDataset[i].count;
      dataDataset[i] = valueDataset;
      valueDataset = [];
    }
    this.optionsDataset.series[0].data = dataDataset;

    let dataProtected = [];
    let valueProtected = [];
    valueProtected[0] = null;
    valueProtected[1] = null;
    let occurrencesProtected = this.protected;
    for (let i in occurrencesProtected) {
      valueProtected[0] = occurrencesProtected[i].name;
      valueProtected[1] = occurrencesProtected[i].count;
      dataProtected[i] = valueProtected;
      valueProtected = [];
    }
    this.optionsProtected.series[0].data = dataProtected;

    let dataRegion = [];
    let valueRegion = [];
    valueRegion[0] = null;
    valueRegion[1] = null;
    let occurrencesRegion = this.region;
    for (let i in occurrencesRegion) {
      valueRegion[0] = occurrencesRegion[i].name_vietnamese;
      valueRegion[1] = occurrencesRegion[i].count;
      dataRegion[i] = valueRegion;
      valueRegion = [];
    }
    this.optionsRegion.series[0].data = dataRegion;
  },
};
</script>

<style scoped>
.inline > div dt,
.inline > div dd {
  display: inline;
  word-break: break-word;
}
.rtl-supported {
  margin: 10px;
  padding: 5px;
}
.site-content__page {
    min-height: calc(100vh - 18.699999999992142857142857142857rem);
}
</style>

