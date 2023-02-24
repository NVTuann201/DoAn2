<template>
  <page-filter :titleFilter="$t('nbds_lang.submenu_protected_area')"
    :titleSearch="$t('nbds_lang.submenu_protected_area_desc')" :count_result="count_result"
    :number_of_filter="number_of_filter" :page.sync="current_page" :page_count="page_count" :items="data"
    :loading="loading" :search.sync="keyword" @click:clearAllFilter="clearAllFilter" @click:search="search"
    @update:page="updatePage" :showOther="isShowMap" @click:phantrang="clickCallback">
    <template v-slot:extra-filter>
      <filter-search title="Quận huyện" :route_suggest="gettopsubloc" field_name="sub_loc" :route_search="getsublogs"
        :params="paramsProvince" v-model="filter_subloc">
      </filter-search>
      <filter-search :title="$t('nbds_lang.protected_areas.desig')" :route_suggest="gettopdesig" field_name="desig"
        :route_search="getdesigs" v-model="filter_desig">
      </filter-search>
      <filter-choose :title="$t('nbds_lang.protected_areas.gov_type')" :route_suggest="gettopgovtypesofprotectedareas"
        field_name="gov_type" v-model="filter_gov_types"></filter-choose>

      <filter-choose title="Trạng thái công nhận" v-model="trang_thai" :options="trangThais">
        <template v-slot:name-filter="{ item }">
          {{ item.name }}
        </template>
      </filter-choose>

    </template>

    <template v-slot:item="{ item }">
      <protecdarea-item :item="item" @click:seachByGovType="seachByGovType" @click:seachBySubLoc="seachBySubLoc"
        @click:seachByDesig="seachByDesig" @click:seachByStatus="seachByStatus"></protecdarea-item>
    </template>
    <template v-slot:tabs>
      <ul>
        <li class="tab cursor-pointer" @click="clickTab()" :class="{
          isActive:
            filter_desig_type == null && !isShowMap && !isInternational,
        }">
          <a>{{ $t("nbds_lang.all") }}</a>
        </li>
        <li class="tab cursor-pointer" @click="clickTab('Local')" :class="{
          isActive: filter_desig_type == 'Local',
        }">
          <a>{{ $t("nbds_lang.local") }}</a>
        </li>
        <li class="tab cursor-pointer" @click="clickTab('National')"
          :class="{ isActive: filter_desig_type === 'National' }">
          <a>{{ $t("nbds_lang.national") }}</a>
        </li>
        <li class="tab cursor-pointer" @click="clickTab('Map')" :class="{ isActive: isShowMap }">
          <a>{{ $t("nbds_lang.map") }}</a>
        </li>
        <li class="tab cursor-pointer">
          <a class="download">
            <download-protectedarea :value="fullData" v-if="!loading && user"></download-protectedarea>
          </a>
        </li>
      </ul>
    </template>
    <template v-slot:other-tab>
      <MapList ref="map" :mapListGetters="mapListGetters" mapUrl="/protectedareas/map"
        detailUrl="/detail/protectedarea/" />
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../env.js";
import MapList from '../../components/conservation-area/components/MapList/Map.vue'
import Multiselect from "vue-multiselect";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import * as routes from "../../routes.js";
import ProtecdareaItem from "./protecedarea-item.vue";
export default {
  props: ["desig_type"],
  components: {
    MapList,
    Multiselect,
    FilterChoose,
    FilterSearch,
    ProtecdareaItem,
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
      filter_region: [],
      filter_subloc: [],
      filter_desig: [],
      filter_status: [],
      filter_gov_types: [],
      filter_desig_type: null,
      isInternational: 0,
      suggest: [],
      fullData: [],
      user: null,
      isShowMap: false,
      map: null,
      paramsProvince: {},
      statusOptions: [
        { id: 1, name: "Đã được công nhận" },
        { id: 2, name: "Chưa được công nhận" },
      ],
      trangThais: [
        { name: 'Được công nhận', code: 'Designated' },
        { name: 'Đang đề xuất', code: 'Proposed' }
      ],
      trang_thai: [],
      mapListGetters: {
        legends: null,
        data: (data) =>
          data.map((item) => ({
            id: item.id,
            geometry: item.geometry,
            name: item.orig_name,
            type: item.desig || "Khác",
          })),
      },
    };
  },
  mounted() {
    if (this.desig_type == "National") {
      this.filter_desig_type = this.desig_type;
    }
    if (this.$store.state.keySearch) {
      this.keyword = this.$store.state.keySearch;
      this.search();
      this.$store.commit("setKeySearch", { amount: null });
    } else {
      this.search();
    }
    if (this.$store.state.valueSelect && this.$store.state.keyType) {
      let value_select = this.$store.state.valueSelect;
      let key_type = this.$store.state.keyType;

      if (key_type == "sub_loc") {
        this.filter_subloc = [{ sub_loc: value_select }];
      }
      if (key_type == "desig") {
        this.filter_desig = [{ desig: value_select }];
      }
      if (key_type == "status") {
        this.filter_status = [{ status: value_select, checked: true }];
      }
      this.search();
      this.$store.commit("setValueSelect", { amount: null, amount1: null });
    } else {
      this.search();
    }
  },
  methods: {
    updatePage(page) {
      this.nextPage = true;
      this.current_page = page;
    },
    search(page = 1) {
      this.loading = true;
      let url = env.endpointhttp + routes.getprotectedareas;
      let filter_sub_locs = [];
      this.filter_subloc.forEach(function (item, index) {
        filter_sub_locs.push(item.id);
      });
      let filter_sub_region = [];
      this.filter_region.forEach(function (item, index) {
        filter_sub_region.push(item.id);
      });
      this.paramsProvince = { region_ids: filter_sub_region };

      let filter_desigs = [];
      this.filter_desig.forEach(function (item, index) {
        filter_desigs.push(item.desig);
      });
      let filter_status = [];
      this.filter_status.forEach(function (item, index) {
        filter_status.push(item.status);
      });
      let filter_gov_types = [];
      this.filter_gov_types.forEach(function (item, index) {
        filter_gov_types.push(item.gov_type);
      });
      let params = {
        page,
        search: this.keyword,
        desig_type: this.filter_desig_type,
        sub_locs: filter_sub_locs,
        desigs: filter_desigs,
        status: filter_status,
        gov_type: filter_gov_types,
        isInternational: this.isInternational,
        region_ids: filter_sub_region,
        trang_thai: this.trang_thai.map(x => x.code)
      };
      axios
        .get(url, {
          params,
        })
        .then((response) => {
          let res = response.data.result;
          this.data = res.data.map((obj) => {
            return Object.assign(obj, {
              count_species: response.data.count_species[obj.id],
            });
          });
          this.count_result = res.total;
          this.page_count = res.last_page;
          this.fullData = response.data.fullData;
          this.user = response.data.user;
          this.current_page = res.current_page;
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    clearAllFilter: function () {
      if (this.filter_subloc.length > 0) this.filter_subloc = [];
      if (this.filter_desig.length > 0) this.filter_desig = [];
      if (this.filter_status.length > 0) this.filter_status = [];
      if (this.filter_gov_types.length > 0) this.filter_gov_types = [];
      this.current_page = 1;
    },
    clickTab(type) {
      this.current_page = 1;
      this.isShowMap = type == "Map";
      this.isInternational = type == "International" ? 1 : 0;
      if (type == "Map" || type == "International") {
        this.filter_desig_type = null;
        if (this.$refs.map) {
          setTimeout(() => this.$refs.map.map.resize(), 0);

        }
        return;
      }
      this.filter_desig_type = type;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.search(pageNum);
    },
    seachBySubLoc(sub_loc) {
      this.filter_subloc.forEach(function (value, index, arr) {
        if (value.sub_loc == this) {
          arr.splice(index, 1);
          return;
        }
      }, sub_loc);
      this.filter_subloc.push({ sub_loc: sub_loc, count: 0 });
    },
    seachByDesig(desig) {
      this.filter_desig.forEach(function (value, index, arr) {
        if (value.desig == this) {
          arr.splice(index, 1);
          return;
        }
      }, desig);
      this.filter_desig.push({ desig: desig, count: 0 });
    },
    seachByStatus(status) {
      this.filter_status.forEach(function (value, index, arr) {
        if (value.status == this) {
          arr.splice(index, 1);
          return;
        }
      }, status);
      this.filter_status.push({ status: status, count: 0, checked: true });
    },
    seachByGovType(gov_type) {
      this.filter_gov_types.forEach(function (value, index, arr) {
        if (value.gov_type == this) {
          arr.splice(index, 1);
          return;
        }
      }, gov_type);
      this.filter_gov_types.push({
        gov_type: gov_type,
        count: 0,
        checked: true,
      });
    },
  },
  computed: {
    getregion: function () {
      return env.endpointhttp + routes.getregion;
    },
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
    gettopdesig: function () {
      return env.endpointhttp + routes.gettopdesig;
    },
    getdesigs: function () {
      return env.endpointhttp + routes.getdesigs;
    },
    gettopstatusofprotectedareas: function () {
      return env.endpointhttp + routes.gettopstatusofprotectedareas;
    },
    gettopgovtypesofprotectedareas: function () {
      return env.endpointhttp + routes.gettopgovtypesofprotectedareas;
    },
    number_of_filter: function () {
      var numberOfFiler = 0;
      if (this.filter_subloc.length > 0) {
        numberOfFiler++;
      }
      if (this.filter_desig.length > 0) {
        numberOfFiler++;
      }
      if (this.filter_status.length > 0) {
        numberOfFiler++;
      }
      if (this.filter_gov_types.length > 0) {
        numberOfFiler++;
      }
      return numberOfFiler;
    },
  },
  watch: {
    trang_thai: function () {
      this.search();
    },
    filter_region: function () {
      this.search();
    },
    filter_subloc: function () {
      this.search();
    },
    filter_desig: function () {
      this.search();
    },
    filter_desig_type: function () {
      this.search();
    },
    filter_status: function () {
      this.search();
    },
    filter_gov_types: function () {
      this.search();
    },
    isInternational: function () {
      this.search();
    },
  },
};
</script>

<style scoped>
.fill-height {
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
}
</style>
