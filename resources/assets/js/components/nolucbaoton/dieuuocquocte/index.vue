<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_international_conventions')"
    :titleSearch="$t('nbds_lang.submenu_international_conventions')"
    :count_result="count_result"
    :page.sync="page"
    :page_count="page_count"
    :items="data"
    :loading="loading"
    :search.sync="filter.search"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <FilterYear
          v-model="filter.nam_ban_hanh"
          :label="$t('dieu_uoc_quoc_te.filter.nam_ban_hanh')"
        />
        <FilterYear
          v-model="filter.nam_co_hieu_luc"
          :label="$t('dieu_uoc_quoc_te.filter.nam_hieu_luc')"
        />
        <FilterYear
          v-model="filter.nam_viet_nam_tham_gia"
          :label="$t('dieu_uoc_quoc_te.filter.nam_viet_nam_tham_gia')"
        />

        <filter-choose
          :title="$t('dieu_uoc_quoc_te.item.trang_thai')"
          v-model="effectFilter"
          :options="effectOptions"
          :multiple="false"
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
            <a
              style="cursor: pointer"
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
              @click="setEffectFilter(item.hieu_luc)"
            >
              {{ $t("dieu_uoc_quoc_te.item.trang_thai") + ":" }}
              {{
                item.hieu_luc
                  ? $t("dieu_uoc_quoc_te.item.con_hieu_luc")
                  : $t("dieu_uoc_quoc_te.item.het_hieu_luc")
              }}
            </a>
            <h3 class="searchCard__headline" dir="auto">
              <a
                :href="`/search/international-conventions/${item.id}`"
                class="ng-isolate-scope"
              >
                {{ item.ten }}
              </a>
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <p class="discreet classification-list ng-binding ng-scope">
              {{ $t("dieu_uoc_quoc_te.item.ngay_ban_hanh") }}:
              <span>{{ item.ngay_ban_hanh }}</span>
            </p>
            <p class="discreet classification-list ng-binding ng-scope">
              {{ $t("dieu_uoc_quoc_te.item.ngay_hieu_luc") }}:
              <span>{{ item.ngay_hieu_luc }}</span>
            </p>
            <p class="discreet classification-list ng-binding ng-scope">
              {{ $t("dieu_uoc_quoc_te.item.ngay_viet_nam_tham_gia") }}:
              <span>{{ item.ngay_viet_nam_tham_gia }}</span>
            </p>
            <ul class="list-chips">
              <li>
                <a class="uppercase-first ng-scope">
                  {{ $t("dieu_uoc_quoc_te.item.so_nuoc_tham_gia") }}:
                  <span style="text-transform: capitalize">{{
                    item.so_quoc_gia_tham_gia
                  }}</span></a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../../env.js";
import PageFilter from "../../layouts/page-filter.vue";
import FilterYear from "../FilterYear.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";

export default {
  components: {
    PageFilter,
    FilterYear,
    FilterChoose,
  },
  computed: {
    effectOptions() {
      return [
        { id: 1, name: this.$t("dieu_uoc_quoc_te.item.con_hieu_luc") },
        { id: 0, name: this.$t("dieu_uoc_quoc_te.item.het_hieu_luc") },
      ];
    },
  },

  data: function () {
    return {
      page: 1,
      page_count: 1,
      data: [],
      filterYear: null,
      count_result: 0,
      loading: false,

      filter: {
        nam_ban_hanh: ["", ""],
        nam_viet_nam_tham_gia: ["", ""],
        nam_co_hieu_luc: ["", ""],
        search: "",
      },
      effectFilter: [],
    };
  },
  watch: {
    effectFilter() {
      this.search(1);
    },
    "filter.nam_ban_hanh"(val) {
      console.log(val);
      this.search(1);
    },
    "filter.nam_viet_nam_tham_gia"() {
      this.search(1);
    },
    "filter.nam_co_hieu_luc"() {
      this.search(1);
    },
  },
  methods: {
    clearAllFilter() {},
    search(page = null) {
      if (page) this.page = page;
      const params = Object.assign(
        {
          hieu_luc:
            this.effectFilter && this.effectFilter.length > 0
              ? this.effectFilter[0].id
              : null,
          page,
        },
        this.filter
      );

      this.loading = true;
      axios
        .get(env.endpointhttp + "search-international-conventions", {
          params,
        })
        .then((response) => {
          this.data = response.data.data;
          this.page_count = response.data.last_page;
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
    setEffectFilter(val) {
      this.effectFilter = [this.effectOptions.find((i) => i.id === +val)];
    },
  },
  mounted() {
    this.search();
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
