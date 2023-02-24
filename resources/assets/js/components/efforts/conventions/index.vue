<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_international_conventions')"
    :titleSearch="$t('nbds_lang.submenu_international_conventions_desc')"
    :count_result="count_result"
    :page.sync="page"
    :page_count="count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope"></div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <div
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              Hiệu lực: {{ item.ngay_hieu_luc }}
            </div>
            <a @click="detail(item.id)">
              <h3 class="searchCard__headline" dir="auto">
                {{ item.ten }}
              </h3>
            </a>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div style="padding-bottom: 15px" class="d-flex">
              <div>
                Ngày ban hành: <b>{{ item.ngay_ban_hanh }}</b>
              </div>
              <div>
                Ngày Việt Nam tham gia: <b>{{ item.ngay_viet_nam_tham_gia }}</b>
              </div>
            </div>
            <div>
              <b>Nội dung chính</b>
              <div>
                {{
                  item.noi_dung_chinh && item.noi_dung_chinh.length > 90
                    ? item.noi_dung_chinh.substring(0, 90)
                    : item.noi_dung_chinh
                }}
              </div>
            </div>
            <div style="padding-top: 10px">
              <b>Đính kèm</b>
              <div class="c-flex">
                <div
                  v-for="it in item.fileList"
                  :key="it.id"
                  @click="taiTapTin(it)"
                  style="cursor: pointer"
                >
                  <i class="fas fa-file-alt" style="padding-right: 10px"></i>
                  {{ it.name }} - {{ Math.round(it.size / 1024) + "Kb" }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../../env.js";
import * as routes from "../../../routes.js";
import Multiselect from "vue-multiselect";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import PageFilter from "../../layouts/page-filter.vue";
import DataTree from "@/modules/filter-group/datatree.vue";
import FilterDay from "../../filterDay.vue";

export default {
  components: {
    Multiselect,
    TaxonTree,
    DataTree,
    FilterContainer,
    FilterSearch,
    PageFilter,
    FilterChoose,
    FilterDay,
  },
  data: function () {
    return {
      tab: "table",
      page: 1,
      count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
    };
  },

  mounted() {
    this.searchWithRank();
  },
  watch: {},
  methods: {
    search() {
      this.searchWithRank(1);
    },
    clickTab(tab) {
      this.tab = tab;
    },
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    searchWithRank(page = 1) {
      let params = {};

      this.loading = true;
      axios
        .get(env.endpointhttp + "international-conventions", {
          params,
        })
        .then((response) => {
          this.data = response.data.data;
          this.count = response.data.last_page;
          this.count_result = response.data.total;
          this.loading = false;
          this.page = response.data.current_page;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        })
        .finally(() => {
          $(".stickyNav").addClass("hasOffset");
          this.loading = false;
        });
    },
    taiTapTin(item) {
      window.location.href = "/" + item.disk;
    },
    detail(id) {
      window.location.href = "/internationalconventions/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    clearAllFilter: function () {
      this.keyword = null;
      this.searchWithRank();
    },
  },
};
</script>
<style>
.multiselect__tags {
  border-radius: 0px !important;
}

.multiselect {
  margin-left: 10px !important;
  width: 90% !important;
}

a:hover {
  text-decoration: none !important;
}

.circular {
  width: 100px !important;
}
</style>
