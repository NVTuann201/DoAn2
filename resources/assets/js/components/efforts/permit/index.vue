<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_biodiversity_permit')"
    :titleSearch="$t('nbds_lang.submenu_biodiversity_permit_desc')"
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
      <div class="filter-group ng-isolate-scope">
        <filter-container title="Năm ban hành" v-model="filterNgayCap">
          <template v-slot:defail-filter="{ open }">
            <div class="is-padded mt-2" v-show="open">
              <div class="d-flex">
                <div style="width: 10px">Từ</div>
                <input
                  placeholder="  Nhập giá trị"
                  class="inputcustom"
                  v-model="filter.ngay_cap.tu"
                  type="number"
                />
              </div>
              <br />
              <div class="d-flex">
                <div style="width: 10px">Đến</div>
                <input
                  placeholder="  Nhập giá trị"
                  class="inputcustom"
                  v-model="filter.ngay_cap.den"
                  type="number"
                />
              </div>
              <div style="padding-top: 10px; padding-bottom: 10px">
                <b-button variant="success" @click="search()" size="sm"
                  >Lọc</b-button
                >
              </div>
            </div>
          </template>
        </filter-container>
        <filter-choose
          title="Loại giấy phép"
          v-model="filter.loai_giay_phep"
          :options="loaiGiay"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>
        <filter-choose
          title="Loại cấp phép"
          v-model="filter.loai_cap_phep"
          :options="danhmucs.loai_cap"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <div
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ item.loai_cap ? item.loai_cap.name : "" }}
            </div>
            <a @click="detail(item.id)">
              <h3 class="searchCard__headline" dir="auto">
                {{ item.loai_giay_phep ? item.loai_giay_phep.name : "" }}
              </h3>
            </a>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div style="padding-bottom: 15px" class="d-flex">
              <div>
                Ngày ban hành: <b>{{ item.ngay_cap }}</b>
              </div>
              <div>
                Ngày hiệu lực: <b>{{ item.ngay_hieu_luc }}</b>
              </div>
            </div>
            <div>
              <div>
                Cơ quan cấp: <b>{{ item.co_quan_cap }}</b>
              </div>
              <div>
                Đơn vị được cấp:
                <b
                  >{{ item.don_vi_duoc_cap }} -
                  {{ item.doi_tuong ? item.doi_tuong.name : "" }}</b
                >
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
  props: ["danhmucs"],
  data: function () {
    return {
      tab: "table",
      page: 1,
      count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
      filterNgayCap: [],
      loaiGiay: [],
      filter: {
        loai_giay_phep: [],
        loai_cap_phep: [],
        ngay_cap: {
          tu: null,
          den: null,
        },
      },
    };
  },
  mounted() {
    this.loaiGiay = [...this.danhmucs.loai_giay];
    this.searchWithRank();
  },
  watch: {
    "filter.loai_giay_phep": function () {
      this.search();
    },
    "filter.loai_cap_phep": function () {
      this.search();
    },
    keyword: function () {
      this.search();
    },
  },
  methods: {
    search() {
      this.searchWithRank(1);
    },

    searchWithRank(page = 1) {
      let params = {
        loai_giay: this.filter.loai_giay_phep.map((el) => el.id),
        loai_cap: this.filter.loai_cap_phep.map((el) => el.id),
        ngay_cap_tu: this.filter.ngay_cap.tu,
        ngay_cap_den: this.filter.ngay_cap.den,
        search: this.keyword,
      };
      this.loading = true;
      axios
        .get(env.endpointhttp + "biodiversity-permit", {
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
    detail(id) {
      window.location.href = "/biodiversitypermit/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    taiTapTin(item) {
      window.location.href = "/" + item.disk;
    },
    clearAllFilter: function () {
      this.filter = {
        loai_giay_phep: [],
        loai_cap_phep: [],
        ngay_cap: {
          tu: null,
          den: null,
        },
      };
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
.inputcustom {
  background: white;
  height: 28px;
  border: 1px solid #cacfd2;
  border-radius: 6px;
}
</style>
