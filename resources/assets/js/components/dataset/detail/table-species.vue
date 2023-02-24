<template>
  <div>
    <section v-if="loading" style="display: flex; justify-content: center;">
      <Loading :width="65" :height="65"></Loading>
    </section>

    <section class="horizontal-stripe--paddingless lighter-background" v-else>
      <div class="occurrence-search__table__area rtl-bootstrap">
        <div class="scrollable-y">
          <div class="table-container">
            <table class="table search-table smaller">
              <thead>
                <tr>
                  <th>
                    {{ $t("nbds_lang.darwin_core_taxons.scientific_name") }}
                  </th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.kingdom_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.phylum_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.class_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.order_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.family_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.genus_id") }}</th>
                  <th>{{ $t("nbds_lang.darwin_core_taxons.species_id") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="lastCellFlexible rtl-supported"
                  v-for="item in items"
                  style="cursor: pointer;"
                  @click="goDetailTaxon(item.id)"
                  :key="item.id"
                >
                  <td class="table-cell--wide">
                    <a
                      ><i>{{ item.scientific_name }}</i></a
                    >
                  </td>
                  <td class="">
                    <a v-if="item.king_dom">{{ item.king_dom.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.phylum">{{ item.phylum.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.class">{{ item.class.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.order">{{ item.order.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.family">{{ item.family.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.genus">{{ item.genus.name }}</a>
                  </td>
                  <td class="">
                    <a v-if="item.species"
                      >{{ item.species.name }}
                      <template v-if="item.species.name_vietnamese"
                        >({{ item.species.name_vietnamese }})</template
                      >
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-xs-12">
              <paginate
                v-if="page_count > 1"
                v-model="current_page"
                :page-count="page_count"
                :page-range="3"
                :margin-pages="2"
                :click-handler="clickCallback"
                :prev-text="'‹‹'"
                :next-text="'››'"
                :container-class="'pagination'"
                :page-class="'page-item'"
              >
              </paginate>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="emptyInfo" v-if="items.length <= 0 && loading == false">
      <h3>{{ $t("nbds_lang.no_information") }}</h3>
    </section>
  </div>
</template>

<script>
import * as env from "../../../env.js";
import * as routes from "../../../routes.js";
import Loading from "../../loading.vue";
export default {
  props: ["id"],
  components: { Loading },
  data: () => ({
    items: [],
    loading: false,
    page_count: 0,
    current_page: 1,
    nextPage: false,
  }),
  created() {
    this.getData();
  },
  methods: {
    getData() {
      this.loading = true;
      let url = "/" + routes.gettaxon;
      axios
        .get(url, {
          params: {
            page: this.current_page,
            datasets: [this.id],
          },
        })
        .then((response) => {
          let res = response.data.result;
          this.items = res.data;
          this.page_count = res.last_page;
        })
        .catch(function (error) {})
        .finally(() => {
          this.loading = false;
        });
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      this.nextPage = true;
      this.getData();
    },
  },
};
</script>

<style>
</style>
