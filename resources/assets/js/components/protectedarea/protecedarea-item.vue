<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a
          :href="
            item.desig_type == 'National'
              ? '/search/protectedarea?type=' + item.desig_type
              : '#'
          "
          class="searchCard__type ng-scope"
        >
          {{
            item.desig_type
              ? $t("nbds_lang.protected_areas.biodiversity_level.label") +
                ": " +
                $t("nbds_lang." + item.desig_type)
              : ""
          }}
        </a>
        <h3 class="searchCard__headline">
          <a :href="'/detail/protectedarea/' + item.id">
            {{ item.orig_name ||  item.name}}
            <template v-if="item.name && item.orig_name">
             <span style="font-style: italic; font-size: 15px">{{ ' - ' + item.name  }}</span> 
              </template
            >
          </a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <div
          class="discreet"
          v-if="item.gov_type && item.gov_type != 'Not Reported'"
        >
          {{ $t("nbds_lang.protected_areas.gov_type") + ": " }}
          <span class="cursor-pointer" @click="seachByGovType(item.gov_type)">
            {{ item.gov_type }}
          </span>
          <span style="float: right; color: #0E6655; font-weight: bold;">{{ item.status == 'Proposed' ? 'Đang đề xuất' : 'Được công nhận'}}</span>
        </div>
        <p v-if="item.description != null && item.description.length >= 200">
          {{ item.description.substring(0, 200) + "..." }}
        </p>
        <p v-else>{{ item.description }}</p>
        <div class="d-flex" style="font-size: 13px; padding-bottom: 10px">
          <div>Diện tích: {{ item.rep_area }} (ha)</div>
          <div>Diện tích vùng lõi: {{ item.dien_tich_vung_loi ? item.dien_tich_vung_loi : '--' }} (ha)</div>
          <div>Diện tích vùng đệm: {{ item.dien_tich_vung_dem ? item.dien_tich_vung_dem : '--'}} (ha)</div>
        </div>
        <ul class="list-chips">
          <li v-if="item.dataset_resources_count > 0">
            <a
              class="cursor-pointer"
              :href="'/search/dataset?protected_area=' + item.id"
            >
              {{
                `${$t("nbds_lang.submenu_dataset")}: ${$n(
                  item.dataset_resources_count
                )}`
              }}
            </a>
          </li>
          <li v-if="item.count_species > 0">
            <a
              :href="`/detail/protectedarea/${item.id}?tab=3`"
              class="cursor-pointer"
            >
              {{
                `${$t("nbds_lang.submenu_species")}: ${$n(item.count_species)}`
              }}
            </a>
          </li>
          <li v-if="item.ten_quan_huyen">
            <a>
              {{ "Quận huyện: " + item.ten_quan_huyen }}
            </a>
          </li>
          <li v-if="item.desig">
            <a @click="seachByDesig(item.desig)" class="cursor-pointer">
              {{ $t("nbds_lang.protected_areas.desig") + ": " + item.desig }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: { item: {} },
  methods: {
    seachByGovType(search) {
      this.$emit("click:seachByGovType", search);
    },
    seachBySubLoc(search) {
      this.$emit("click:seachBySubLoc", search);
    },
    seachByDesig(search) {
      this.$emit("click:seachByDesig", search);
    },
    seachByStatus(search) {
      this.$emit("click:seachByStatus", search);
    },
  },
};
</script>
<style scoped>
.searchCard__type {
  background-color: #74b274;
  color: #fff;
}
</style>
