<template>
  <page-filter
    titleFilter="Loài ngoại lai"
    titleSearch="Tìm kiếm loài ngoại lai"
    :count_result="count_result"
    :page.sync="page"
    :page_count="count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
    @click:phantrang="clickCallback"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <filter-search
          title="Quận huyện"
          :route_suggest="gettopsubloc"
          field_name="sub_loc"
          :route_search="getsublogs"
          :params="paramsProvince"
          v-model="quan_huyen"
        >
        </filter-search>

        <filter-choose
          title="Phân loại"
          v-model="phan_loai"
          :options="[...searchdata.phan_loai]"
        >
          <template v-slot:name-filter="{ item }">
            <div style="display: flex">
              <div style="flex: 1">
                {{ item.name }}
              </div>
              <div>{{ item.so_luong }}</div>
            </div>
          </template>
        </filter-choose>

        <filter-choose
          title="Nguy cơ"
          v-model="nguy_co"
          :options="[...searchdata.nguy_co]"
        >
          <template v-slot:name-filter="{ item }">
            <div style="display: flex">
              <div style="flex: 1">
                {{ item.name }}
              </div>
              <div>{{ item.so_luong }}</div>
            </div>
          </template>
        </filter-choose>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <a
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ item.phan_loai ? item.phan_loai.name : "" }}
            </a>

            <h3 class="searchCard__headline" dir="auto">
              <a :name="item.name" class="ng-isolate-scope">
                <div v-if="item.ten && item.ten_khoa_hoc">
                  <span>{{ item.ten }}</span>
                  <span style="font-style: italic">
                    - {{ item.ten_khoa_hoc }}</span
                  >
                </div>
                <div v-else>
                  {{ item.ten || item.ten_khoa_hoc }}
                </div>
              </a>
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div class="discreet classification-list ng-binding ng-scope">
              <div v-if="item.nguon_goc">Nguồn gốc: {{ item.nguon_goc }}</div>
              <div>
                Nguy cơ xâm hại:
                <span v-if="item.nguy_co" style="font-weight: bold">{{
                  item.nguy_co.name
                }}</span>
                <span v-else> -- </span>
              </div>
              <div>
                Diện tích phân bố: {{ item.dien_tich_phan_bo }} (ha)
                <span style="padding-left: 20px"
                  >Mật độ: {{ item.mat_do }}/{{ item.don_vi_tinh_mat_do }}</span
                >
              </div>
              <div v-if="item.dac_diem_hinh_thai">
                Đặc điểm hình thái: {{ item.dac_diem_hinh_thai }}
              </div>
              <div v-if="item.dac_diem_sinh_thai">
                Đặc điểm sinh thái: {{ item.dac_diem_sinh_thai }}
              </div>
              <div v-if="item.phan_bo_viet_nam">
                Phân bố Việt Nam: {{ item.phan_bo_viet_nam }}
              </div>
              <div v-if="item.ghi_nhan_the_gioi">
                Ghi nhận thế giới: {{ item.ghi_nhan_the_gioi }}
              </div>
            </div>
            <br />
            <ul class="list-chips">
              <li
                v-if="item.dia_diem && item.dia_diem.length"
                style="margin-right: 20px"
              >
                <a class="uppercase-first ng-scope">
                  Địa điểm:
                  <span
                    style="text-transform: capitalize; padding-left: 5px"
                    v-for="diadiem in item.dia_diem"
                    :key="diadiem.id"
                    >{{
                      diadiem.orig_name || diadiem.name || diadiem.ten
                    }},</span
                  >
                </a>
              </li>
              <li v-if="item.quan_huyen && JSON.parse(item.quan_huyen.length)">
                <a class="uppercase-first ng-scope">
                  Quận huyện:
                  <span
                    style="text-transform: capitalize; padding-left: 5px"
                    v-for="quanhuyen in item.list_quan_huyen"
                    :key="quanhuyen.id"
                    >{{ quanhuyen.name_vietnamese }},</span
                  >
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import { debounce } from "lodash";
import * as env from "../../env.js";
import * as routes from "../../routes.js";
import Multiselect from "vue-multiselect";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import PageFilter from "../layouts/page-filter.vue";
import DataTree from "@/modules/filter-group/datatree.vue";
export default {
  props: ["searchdata"],
  components: {
    Multiselect,
    TaxonTree,
    DataTree,
    FilterContainer,
    FilterSearch,
    PageFilter,
    FilterChoose,
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
      paramsProvince: {},
      quan_huyen: [],
      phan_loai: [],
      nguy_co: [],
    };
  },
  computed: {
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
  },
  mounted() {},
  watch: {
    quan_huyen: function () {
      this.search();
    },
    phan_loai: function () {
      this.search();
    },
    nguy_co: function () {
      this.search();
    },
  },
  methods: {
    search: debounce(function () {
      this.page = 1;
      this.searchWithRank();
    }, 200),
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback() {
      this.searchWithRank();
    },
    searchWithRank(page = 1) {
      let params = {
        quan_huyen: this.quan_huyen.map((x) => x.id),
        page: this.page,
        search: this.keyword,
        phan_loai: this.phan_loai.map((x) => x.id),
        nguy_co: this.nguy_co.map((x) => x.id),
      };
      axios
        .get(env.endpointhttp + "alien-organisms", {
          params,
        })
        .then((response) => {
          console.log(response);
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
      window.location.href = "/detail/indexspecies/" + id;
    },
    clearAllFilter: function () {
      this.keyword = null;
      this.quan_huyen = [];
      this.phan_loai = [];
      this.nguy_co = [];
      this.searchWithRank();
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    distributionAddItem(item) {
      this.filterDistribution = [item];
    },
    setRankHigherSearch(x) {
      let item = Object.assign({}, x, {
        filter: { child_id: x[`${x.rank}_id`], rank: x.rank },
        id: x[`${x.rank}_id`],
      });
      if (
        !this.taxonFilter.find(
          (x) =>
            x.filter.child_id == item.filter.child_id &&
            x.filter.rank == item.filter.rank
        )
      ) {
        this.taxonFilter = [item];
        this.setRankSearch("species");
      }
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
