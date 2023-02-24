<template>
  <section v-if="loading" style="display: flex;
                        justify-content: center;">
    <Loading :width="65" :height="65"></Loading>
  </section>
  <div v-else>
    <div class="container--narrow">
      <div class="row">
        <div class="col-xs-12">
          <div class="card__stripe" v-if="data.length > 0">
            <div class="card__content clearfix">
              <article
                class="card m-b-05 searchCard rtl-supported"
                v-for="(item, i) in data"
                :key="i"
              >
                <div class="card__stripe">
                  <div class="card__content">
                    <a
                      :href="
                                    '/search/dataset?type=' + item.dataset_type
                                  "
                      class="inherit searchCard__type hoverBox"
                    >{{ item.dataset_type }}</a>
                    <h3 class="searchCard__headline">
                      <a :href="'/detail/dataset/' + item.id">
                        {{
                        item.title
                        }}
                      </a>
                    </h3>
                  </div>
                </div>
                <div class="card__stripe">
                  <div class="card__content searchCard__content clearfix">
                    <p>{{ item.additional_info }}</p>
                    <p class="discreet--very smaller">
                      <span>
                        {{
                        $t("nbds_lang.published_by")
                        }}
                      </span>
                      <span>{{ item.organization.name }}</span>
                    </p>

                    <ul class="list-chips">
                      <li
                        class="loaded"
                        v-if="
                                      item.dataset_type == 'Occurrence' &&
                                      item.darwin_core_occurrences_count > 0
                                    "
                      >
                        <a
                          :href="
                                        '/search/occurrence?dataset=' + item.id
                                      "
                        >
                          <span class="loaded">
                            {{
                            item.darwin_core_occurrences_count
                            }}
                            {{ $t("nbds_lang.occurrences") }}
                          </span>
                        </a>
                      </li>
                      <li
                        class="loaded"
                        v-if="
                                      item.dataset_type == 'Taxon' &&
                                      item.darwin_core_taxons_count > 0
                                    "
                      >
                        <a @click="searchTaxon(item.id)">
                          <span class="loaded">
                            {{ item.darwin_core_taxons_count }}
                            {{
                            $t(
                            "nbds_lang.table_darwin_core_taxons"
                            )
                            }}
                          </span>
                        </a>
                      </li>
                      <li class="loaded" v-if="item.dataset_references_count > 0">
                        <a>
                          <span class="loaded">
                            {{ item.dataset_references_count }}
                            {{
                            $t(
                            "nbds_lang.table_dataset_references"
                            )
                            }}
                          </span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </article>
            </div>
            <paginate
              v-if="paging.page_count > 1"
              v-model="paging.current_page"
              :page-count="paging.page_count"
              :page-range="3"
              :margin-pages="2"
              :click-handler="clickCallback"
              :prev-text="'‹‹'"
              :next-text="'››'"
              :container-class="'pagination'"
              :page-class="'page-item'"
            ></paginate>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as routes from "../../../routes.js";
import * as env from "../../../env.js";
import Loading from "../../loading.vue";
export default {
  props: ["id"],
  components: {
    Loading,
  },
  data: () => ({
    data: [],
    loading: false,
    paging: {
      page_count: 1,
      current_page: 1,
    },
  }),
  created() {
    this.getData();
  },
  methods: {
    getData() {
      this.loading = true;
      let url = env.endpointhttp + routes.getdatasetresources;
      axios
        .get(url, {
          params: {
            page: this.current_page,
            publishers: [this.id],
            filter_dataset_inheritance_type: ["Public"],
          },
        })
        .then((response) => {
          let res = response.data.result;
          this.data = res.data;
          this.paging.page_count = res.last_page;
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      this.getData();
    },
  },
};
</script>
