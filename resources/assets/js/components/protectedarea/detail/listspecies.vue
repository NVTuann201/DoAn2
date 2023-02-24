<template>
  <div class="occurrence-search__table__area rtl-bootstrap">
    <div class="scrollable-y">
      <div class="table-container">
        <table class="table search-table smaller">
          <thead>
            <tr>
              <th>{{$t('nbds_lang.darwin_core_taxons.scientific_name')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.kingdom_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.phylum_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.class_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.order_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.family_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.genus_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.species_id')}}</th>
              <th>{{$t('nbds_lang.darwin_core_taxons.dataset_resource_id')}}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in listItem"
              :key="`${item.name}${item.id}`"
              style="cursor:pointer"
              @click="goDetailTaxon(item.id)"
            >
              <td class="table-cell--wide">
                <a>
                  <i>{{item.scientific_name}}</i>
                </a>
              </td>
              <td class>
                <a v-if="item.king_dom">{{item.king_dom.name}}</a>
              </td>
              <td class>
                <a v-if="item.phylum">{{item.phylum.name}}</a>
              </td>
              <td class>
                <a v-if="item.class">{{item.class.name}}</a>
              </td>
              <td class>
                <a v-if="item.order">{{item.order.name}}</a>
              </td>
              <td class>
                <a v-if="item.family">{{item.family.name}}</a>
              </td>
              <td class>
                <a v-if="item.genus">{{item.genus.name}}</a>
              </td>
              <td class>
                <a v-if="item.species">{{item.species.name}}</a>
              </td>
              <td class>
                <a v-if="item.data_resource">{{item.data_resource.title}}</a>
              </td>
            </tr>
            <tr v-if="listItem.length == 0">
              <td class="text-center" colspan="9">{{$t('admin.error.no_data')}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <paginate
            v-if="paging.page_count>1"
            v-model="paging.current_page"
            :page-count="this.paging.page_count"
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
</template>
<script>
import * as env from "../../../env.js";

export default {
  props: ["listspecies", "id"],
  data: () => ({
    listItem: [],
    paging: {
      page_count: 1,
      current_page: 1,
    },
  }),
  created() {
    this.listItem = this.listspecies.data;
    this.paging.page_count = this.listspecies.last_page;
    this.paging.current_page = this.listspecies.current_page;
  },
  methods: {
    goDetailTaxon(value) {
      window.location.href = "/detail/taxon/" + value;
    },
    clickCallback(pageNum) {
      this.paging.current_page = pageNum;
      this.goNextPage();
    },
    goNextPage() {
      axios
        .get(`${env.endpointhttp}detail/protectedarealistspecies/${this.id}`, {
          params: {
            page: this.paging.current_page,
          },
        })
        .then((res) => {
          this.listItem = res.data.data;
          this.paging.page_count = res.data.last_page;
          this.paging.current_page = res.data.current_page;
        })
        .catch((err) => {});
    },
  },
};
</script>