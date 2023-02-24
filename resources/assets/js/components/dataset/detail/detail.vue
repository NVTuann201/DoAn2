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
                  class="horizontal-stripe--paddingless white-background seperator--b"
                >
                  <div class="container--desktop">
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="tabs__wrapper">
                          <div class="tabs__actions">
                            <ul>
                              <li class="tab tab-right">
                                <span>
                                  <a
                                    class="gb-button--brand ng-binding"
                                    v-if="
                                      value.darwin_core_occurrences_count > 0
                                    "
                                    :href="
                                      '/search/occurrence?dataset=' + value.id
                                    "
                                  >
                                    {{ value.darwin_core_occurrences_count }}
                                    <span class="gb-button--brand__label">
                                      {{ $t("nbds_lang.occurrences") }}
                                    </span>
                                  </a>
                                </span>
                              </li>
                              <li
                                class="tab tab-right"
                                v-if="value.darwin_core_taxons_count > 0"
                              >
                                <span>
                                  <a
                                    class="gb-button--primary ng-binding"
                                    @click="searchTaxon(value.id)"
                                    style="cursor: pointer"
                                  >
                                    {{ value.darwin_core_taxons_count }}
                                    <span class="gb-button--primary__label">
                                      {{
                                        $t("nbds_lang.table_darwin_core_taxons")
                                      }}
                                    </span>
                                  </a>
                                </span>
                              </li>
                              <li
                                class="tab tab-right"
                                v-if="value.dataset_references_count > 0"
                              >
                                <span>
                                  <a class="gb-button--dark" href="#">
                                    {{ value.dataset_references_count }}
                                    <span class="ng-isolate-scope">
                                      {{
                                        $t("nbds_lang.table_dataset_references")
                                      }}
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
                                :class="popUpTab == 1 ? 'isActive' : ''"
                                @click="popUpTab = 1"
                              >
                                <a href="#dataset">
                                  {{ $t("nbds_lang.overview") }}
                                </a>
                              </li>
                              <li
                                class="tab"
                                :class="popUpTab == 2 ? 'isActive' : ''"
                                @click="popUpTab = 2"
                                v-if="value.dataset_type == 'Occurrence'"
                              >
                                <a href="#metrics">
                                  {{ $t("nbds_lang.metrics") }}
                                </a>
                              </li>
                              <li
                                class="tab"
                                :class="popUpTab == 3 ? 'isActive' : ''"
                                @click="popUpTab = 3"
                              >
                                <a href="#table_species">
                                  {{ $t("nbds_lang.table_species") }}
                                </a>
                              </li>
                              <li
                                v-if="countimage > 0"
                                class="tab"
                                :class="popUpTab == 4 ? 'isActive' : ''"
                                @click="popUpTab = 4"
                              >
                                <a href="#gallery">
                                  {{ $t("nbds_lang.gallery") }}
                                </a>
                              </li>
                              <li class="tab dropdown">
                                  <span translate="dataset.download" class>
                                    <download-dataset
                                      v-if="this.user"
                                      :value="value"
                                    ></download-dataset>
                                  </span>
                              </li>
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <div class="anchor-block--tabs">
                  <section
                    class="horizontal-stripe light-background"
                    v-if="popUpTab == 1"
                  >
                    <div class="container--desktop">
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="container-fluid card rtl-bootstrap">
                            <div class="row card__content">
                              <div class="col-xs-12 col-sm-6 col-md-8">
                                <Contact
                                  v-model="this.value.organization"
                                ></Contact>
                                <Distribution
                                  :distribution="this.value.distribution"
                                  :area="this.value.dataset_resource_area"
                                ></Distribution>
                                <TaxonomicScope
                                  :taxonomic_coverage="
                                    this.value.taxonomic_coverage
                                  "
                                  :king_doms="this.king_doms"
                                  :phylums="this.phylums"
                                ></TaxonomicScope>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-4">
                                <div
                                  class="logoImg ng-scope"
                                  v-if="value.logo_url"
                                >
                                  <img :src="'/' + value.logo_url" />
                                </div>
                                <div class="logoImg ng-scope" v-else>
                                  <img src="/images/logo/full_logo.png" />
                                </div>
                                <dl class="inline">
                                  <div v-if="value.updated_at != null">
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.dataset_resources.updated_at"
                                        )
                                      }}
                                    </dt>
                                    <dd>
                                      {{ value.updated_at | moment("LL") }}
                                    </dd>
                                  </div>
                                  <div v-if="value.series != null">
                                    <dt>
                                      {{
                                        $t("nbds_lang.dataset_resources.series")
                                      }}
                                    </dt>
                                    <dd>{{ value.series }}</dd>
                                  </div>
                                  <div v-if="value.abstract != null">
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.dataset_resources.abstract"
                                        )
                                      }}
                                    </dt>
                                    <dd>{{ value.abstract }}</dd>
                                  </div>
                                  <div
                                    v-if="value.begin_or_single_date != null"
                                  >
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.dataset_resources.begin_or_single_date"
                                        )
                                      }}
                                    </dt>
                                    <dd>
                                      {{
                                        value.begin_or_single_date
                                          | moment("LL")
                                      }}
                                    </dd>
                                  </div>
                                  <div v-if="value.end_date != null">
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.dataset_resources.end_date"
                                        )
                                      }}
                                    </dt>
                                    <dd>{{ value.end_date | moment("LL") }}</dd>
                                  </div>
                                  <div v-if="value.publication_date != null">
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.dataset_resources.publication_date"
                                        )
                                      }}
                                    </dt>
                                    <dd>
                                      {{
                                        value.publication_date | moment("LL")
                                      }}
                                    </dd>
                                  </div>
                                  <div>
                                    <dt>{{ $t("nbds_lang.language") }}</dt>
                                    <dd>{{ value.language }}</dd>
                                  </div>
                                  <div>
                                    <dt>
                                      {{
                                        $t(
                                          "nbds_lang.organizations.organization_type"
                                        )
                                      }}
                                    </dt>
                                    <dd>
                                      {{
                                        value.organization &&
                                        value.organization.organization_type
                                      }}
                                    </dd>
                                  </div>
                                </dl>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
                <div class="container--desktop">
                  <div class="row">
                    <div class="col-xs-12 col-md-12 body-text">
                      <table-species
                        class="horizontal-stripe light-background seperator--b"
                        v-if="popUpTab == 3"
                        :id="value.id"
                      ></table-species>
                    </div>
                  </div>
                </div>
                <div class="container--desktop">
                  <div class="row">
                    <div class="col-xs-12 col-md-12 body-text">
                      <Gallery
                        v-if="popUpTab == 4 && countimage > 0"
                        :id="value.id"
                      ></Gallery>
                    </div>
                  </div>
                </div>
                <div class="submenu-wrapper rtl-bootstrap">
                  <section
                    class="horizontal-stripe--paddingless white-background"
                    v-if="popUpTab == 1"
                  >
                    <div class="container--desktop">
                      <div class="row">
                        <!-- <div class="col-xs-12 col-md-3">
                          <TabVertical :value="this.tabs"></TabVertical>
                        </div>-->
                        <div class="col-md-12 body-text">
                          <div class>
                            <span
                              v-if="
                                value.additional_info != null &&
                                value.additional_info.length >= 750
                              "
                            >
                              <div class="anchor-block--tabs">
                                <h3>
                                  {{ $t("nbds_lang.blocks.description") }}
                                </h3>
                                <p
                                  style="
                                    margin-bottom: 2px;
                                    text-align: justify;
                                  "
                                >
                                  {{
                                    value.additional_info.substring(0, 750) +
                                    "..."
                                  }}
                                </p>
                                <a href="#description">
                                  {{ $t("nbds_lang.more") }}
                                </a>
                              </div>
                            </span>
                            <Abstract v-model="this.value.abstract"></Abstract>
                            <Geographic
                              v-model="this.value.geographic_description"
                            ></Geographic>
                            <AdditionalInfo
                              v-model="this.value.additional_info"
                            ></AdditionalInfo>
                            <Citation
                              :citation="this.value.citation"
                              :references="this.value.dataset_references"
                            ></Citation>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                  <section
                    class="horizontal-stripe--paddingless white-background"
                    v-if="false"
                  >
                    <Metrics
                      :value="metrcisdata"
                      :data="data"
                      :dataset="value"
                    ></Metrics>
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
import Abstract from "./abstract.vue";
import Geographic from "./geographic.vue";
import AdditionalInfo from "./additionalInfo.vue";
import Citation from "./citation.vue";
import Distribution from "./distribution.vue";
import TaxonomicScope from "./taxonomicScope.vue";
import Contact from "./contact.vue";
import Metrics from "./metrics";
import TableSpecies from "./table-species.vue";
import Gallery from "./gallery";
export default {
  props: [
    "value",
    "king_doms",
    "phylums",
    "occurrences",
    "metrcisdata",
    "data",
    "user",
    "countimage",
  ],
  components: {
    Header,
    TableSpecies,
    TabVertical,
    Abstract,
    Geographic,
    AdditionalInfo,
    Citation,
    Distribution,
    TaxonomicScope,
    Contact,
    Metrics,
    Gallery,
  },
  data: function () {
    return {
      popUpTab: 1,
      tabs: [
        { title: "Abstract", key: "abstract" },
        { title: "Geographic scope", key: "geographicCoverages" },
        { title: "Additional Info", key: "additionalInfo" },
        { title: "Citation", key: "citation" },
        { title: "Distribution", key: "distribution" },
        { title: "Contact", key: "organization" },
        { title: "Taxonomic scope", key: "taxonomicCoverages" },
      ],
    };
  },
  methods: {
    searchTaxon(value) {
      this.setValueSearch(value);
      window.location.href = "/search/taxon";
    },
    setValueSearch(value) {
      this.$store.commit("setValueSelect", { amount: value });
      console.log(
        "this.$store.state.valueSelect::",
        this.$store.state.valueSelect
      );
    },
  },
  created() {
    console.log("occurrences::", this.occurrences);
    console.log("data::", this.data);
  },
};
</script>
<style scoped>
.site-content__page {
  min-height: calc(100vh - 18.542857142848571428571428571429rem);
}
.tabs__wrapper{
  z-index: 1;
}
</style>
