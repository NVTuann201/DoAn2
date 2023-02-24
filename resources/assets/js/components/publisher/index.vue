<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_publisher')"
    :titleSearch="$t('nbds_lang.submenu_publisher')"
    :count_result="count_result"
    :number_of_filter="number_of_filter"
    :page.sync="current_page"
    :page_count="page_count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
  >
    <template v-slot:extra-filter>
      <filter-choose
        :title="$t('nbds_lang.organizations.organization_type')"
        field_name="organization_type"
        :route_suggest="getorganization_type"
        v-model="filter_organization_type"
      >
      </filter-choose>
    </template>

    <template v-slot:tabs>
      <ul>
        <li class="tab cursor-pointer isActive">
          <a>{{ $t("nbds_lang.all") }}</a>
        </li>
      </ul>
    </template>
    <template v-slot:item="{ item }">
      <publisher-item
        :item="item"
        @click:searchOrganizationType="searchOrganizationType"
      ></publisher-item>
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import * as routes from "../../routes.js";
import Loading from "../loading.vue";
import PublisherItem from "./publisher-item.vue";
export default {
  props: ["publisher"],
  components: {
    Multiselect,
    FilterChoose,
    Loading,
    PublisherItem,
  },
  data: function () {
    return {
      activeFillter: false,
      current_page: 1,
      page_count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
      filter_organization_type: [],
    };
  },
  mounted() {
    if (this.$store.state.keySearch) {
      this.keyword = this.$store.state.keySearch;
      this.search();
      this.$store.commit("setKeySearch", { amount: null });
    } else {
      this.search();
    }
  },
  methods: {
    search() {
      this.loading = true;
      let url = env.endpointhttp + routes.getpublishers;
      axios
        .get(url, {
          params: {
            publisher: this.publisher,
            page: this.current_page,
            search: this.keyword,
            select_fields: [
              "id",
              "name_vietnamese",
              "name",
              "acronym",
              "acronym_vietnamese",
              "organization_type",
              "description",
              "created_at",
            ],
            withs: ["parent"],
            with_counts: ["datasetResources"],
            organization_type: this.filter_organization_type.map(
              (x) => x.organization_type
            ),
          },
        })
        .then((response) => {
          this.nextPage = false;
          let res = response.data.result;
          this.data = res.data;
          this.count_result = res.total;
          this.page_count = res.last_page;
          this.current_page = res.current_page;
        })
        .catch(function (error) {
          this.nextPage = false;
          console.log(error);
        })
        .finally(() => {
          this.nextPage = false;
          this.loading = false;
        });
    },
    clearAllFilter() {
      this.current_page = 1;
      this.filter_organization_type = [];
    },
    clickCallback(pageNum) {
      this.nextPage = true;
      this.current_page = pageNum;
      this.search();
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    searchOrganizationType(organization_type) {
      this.filter_organization_type.forEach(function (value, index, arr) {
        if (value.organization_type == this) {
          arr.splice(index, 1);
          return;
        }
      }, organization_type);
      this.filter_organization_type.push({
        organization_type: organization_type,
        count: 0,
        checked: true,
      });
    },
  },
  watch: {
    filter_organization_type() {
      this.current_page = 1;
      this.search();
    },
  },
  computed: {
    getorganization_type() {
      return env.endpointhttp + routes.getorganization_type;
    },
    number_of_filter() {
      return this.filter_organization_type.length;
    },
  },
};
</script>

<style scoped>
.searchCard__type {
  background-color: #ff479c;
  color: #fff;
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16);
  transition: box-shadow 0.2s ease;
}
.searchCard__img {
  position: relative;
}
.publisher_logo {
  width: 80px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
