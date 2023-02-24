<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <Header v-model="this.value"></Header>
                <section
                  class="
                    horizontal-stripe--paddingless
                    white-background
                    seperator--b
                  "
                >
                  <div class="container--desktop">
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="tabs__wrapper">
                          <div class="tabs__actions">
                            <ul>
                              <li class="tab tab-right" v-if="orga.length > 0">
                                <span>
                                  <a
                                    class="gb-button--brand ng-binding"
                                    :href="hrefOrga"
                                  >
                                    {{ orga.length }}
                                    <span class="gb-button--brand__label">
                                      {{ $t("nbds_lang.submenu_publisher") }}
                                    </span>
                                  </a>
                                </span>
                              </li>
                              <li
                                class="tab tab-right"
                                v-if="value.dataset_resources_count > 0"
                              >
                                <span>
                                  <a
                                    class="gb-button--brand ng-binding"
                                    :href="
                                      '/search/dataset?protected_area=' +
                                      value.id
                                    "
                                  >
                                    {{ value.dataset_resources_count }}
                                    <span class="gb-button--brand__label">
                                      {{ $t("nbds_lang.submenu_dataset") }}
                                    </span>
                                  </a>
                                </span>
                              </li>
                            </ul>
                          </div>
                          <nav class="tabs tabs--noBorder">
                            <ul>
                              <li
                                class="tab"
                                :class="[tab == 1 ? 'isActive' : '']"
                                @click="tab = 1"
                              >
                                <a href="#description">
                                  {{ $t("nbds_lang.submenu_protected_area") }}
                                </a>
                              </li>
                              <li
                                class="tab"
                                :class="[tab == 2 ? 'isActive' : '']"
                                @click="tab = 2"
                              >
                                <a href="#description">
                                  {{
                                    $t(
                                      "nbds_lang.dataset_resources.geographic_description"
                                    )
                                  }}
                                </a>
                              </li>
                              <li
                                class="tab"
                                :class="[tab == 3 ? 'isActive' : '']"
                                @click="tab = 3"
                              >
                                <a href="#description">
                                  {{ $t("nbds_lang.list_of_species") }}
                                </a>
                              </li>
                              <li
                                class="tab"
                                :class="[tab == 4 ? 'isActive' : '']"
                                @click="tab = 4"
                              >
                                <a href="#description"> Nguồn gen </a>
                              </li>

                              <li
                                class="tab"
                                :class="[tab == 5 ? 'isActive' : '']"
                                @click="tab = 5"
                              >
                                <a href="#description"> Hệ sinh thái </a>
                              </li>
                              <li
                                class="tab"
                                :class="[tab == 6 ? 'isActive' : '']"
                                @click="tab = 6"
                              >
                                <a href="#description">
                                  Áp lực lên đa dạng sinh học
                                </a>
                              </li>

                              <!--<li class="tab dropdown">
                                <a href type="button" class="dropdown-toggle">
                                  <span class="gb-icon-file-download"></span>
                                  <span translate="dataset.download" class>Download</span>
                                </a>
                              </li>-->
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <div class="anchor-block--tabs" v-if="tab == 1">
                  <section
                    class="horizontal-stripe light-background seperator--b"
                  >
                    <div class="container--desktop">
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="container-fluid card rtl-bootstrap">
                            <div
                              class="row card__content"
                              v-if="value.description"
                            >
                              <div
                                class="col-xs-12 col-sm-6 col-md-10"
                                v-if="value.description.length >= 750"
                              >
                                <p>
                                  {{
                                    value.description.substring(0, 750) + "..."
                                  }}
                                </p>
                                <a class="m-l-1" href="#description">
                                  {{ $t("nbds_lang.more") }}
                                </a>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-10" v-else>
                                <p>{{ value.description }}</p>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-12 col-sm-6">
                              <section class="term-block">
                                <div class="term-block__header">
                                  <h3>
                                    {{ $t("nbds_lang.table_category_info") }}
                                  </h3>
                                </div>
                                <div class="term-block__terms">
                                  <table class="table table--compact">
                                    <tbody>
                                      <tr v-if="value.updated_at">
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.dataset_resources.updated_at"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{
                                            value.updated_at
                                              | moment("DD-MM-YYYY")
                                          }}
                                        </td>
                                      </tr>
                                      <tr v-if="value.region">
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.region"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{ value.region.name_vietnamese }}
                                        </td>
                                      </tr>
                                      <tr v-if="value.sub_loc">
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.sub_loc"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">{{ value.sub_loc }}</td>
                                      </tr>
                                      <tr v-if="value.desig">
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.desig"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">{{ value.desig }}</td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.name_vn"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{ value.orig_name }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.name_en"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">{{ value.name }}</td>
                                      </tr>
                                      <tr v-if="value.created_at">
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "nbds_lang.protected_areas.created_at"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{
                                            value.created_at
                                              | moment("DD-MM-YYYY")
                                          }}
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </section>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                              <OtherInfo v-model="this.value"></OtherInfo>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
                <div class="submenu-wrapper rtl-bootstrap" v-if="tab == 2">
                  <section
                    class="horizontal-stripe--paddingless white-background"
                  >
                    <div class="container--desktop" style="margin-bottom: 2rem">
                      <div class="row">
                        <div class="col-xs-12 col-md-12">
                          <MapDetail
                            :data="{
                              geom: value.geometry,
                              quan_huyen: { name_vietnamese: 'Hà Nội' },
                            }"
                          ></MapDetail>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
                <div class="submenu-wrapper rtl-bootstrap" v-if="tab == 3">
                  <section
                    class="horizontal-stripe--paddingless white-background"
                  >
                    <div class="container--desktop">
                      <Loai
                        :doiTuong="{ name: 'khu_bao_ton_id', id: idDetail }"
                      />
                    </div>
                    <!-- <div class="container--desktop">
                      <div class="row">
                        <div class="col-xs-12 col-md-12 body-text">
                          <ListSpecies
                            :id="value.id"
                            :listspecies="listspecies"
                          />
                        </div>
                      </div>
                    </div> -->
                  </section>
                </div>
                <div class="submenu-wrapper rtl-bootstrap" v-if="tab == 4">
                  <div class="container--desktop">
                    <NguonGen
                      :doiTuong="{ name: 'khu_bao_ton_id', id: idDetail }"
                    />
                  </div>
                </div>
                <div class="submenu-wrapper rtl-bootstrap" v-if="tab == 5">
     
                    <div class="container--desktop">
                      <HeSinhThai  :doiTuong="{ name: 'khu_bao_ton_id', id: idDetail }" />
                    </div>
                </div>
                <div class="submenu-wrapper rtl-bootstrap" v-if="tab == 6">
                  <section
                    class="horizontal-stripe--paddingless white-background"
                  >
                    <div class="container--desktop" style="margin-bottom: 2rem">
                      <ApLuc :doiTuong="{ name: 'khu_bao_ton_id', id: idDetail }" />
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
import Geographic from "./geographic.vue";
import OtherInfo from "./other.vue";
import MapDetail from "../../conservation-area/components/MapDetail.vue";
import ListSpecies from "./listspecies.vue";
import NguonGen from "../../dulieudoitruong/nguongen.vue";
import ApLuc from "../../dulieudoitruong/apluc.vue";
import Loai from "../../dulieudoitruong/loai.vue";
import HeSinhThai from '../../dulieudoitruong/hesinhthai.vue'

export default {
  props: [
    "value",
    "year",
    "dataset",
    "orga",
    "province",
    "region",
    "listspecies",
    "tabdefault",
  ],
  components: {
    Header,
    TabVertical,
    Geographic,
    OtherInfo,
    ListSpecies,
    MapDetail,
    NguonGen,
    ApLuc,
    Loai,
    HeSinhThai
  },
  data: function () {
    return {
      hrefOrga: "/search/publisher?",
      tab: 1,
      idDetail: null,
    };
  },
  created() {
    this.tab = this.tabdefault;
    this.getHrefOrga();
    var params = window.location.pathname.split("/");
    this.idDetail = params[params.length - 1];
  },
  methods: {
    getHrefOrga() {
      this.orga.forEach((element) => {
        this.hrefOrga += `publisher[]=${element.id}&`;
      });
      this.hrefOrga = this.hrefOrga.substring(0, this.hrefOrga.length - 1);
    },
  },
};
</script>
<style scoped>
.site-content__page {
  min-height: calc(100vh - 18.542857142848571428571428571429rem);
}
</style>
