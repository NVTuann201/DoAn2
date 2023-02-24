<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a
          class="inherit searchCard__type hoverBox"
          @click="
            $emit('categoryChanged', {
              key: aFilter.key,
              value: item[aFilter.itemKey],
            })
          "
        >
          {{
            item[aFilter.itemKey]
              ? item[aFilter.itemKey].name
              : "Không xác định"
          }}
        </a>
        <h3 class="searchCard__headline">
          <a>
            {{ item.ten || $t("common.unknown") }}
          </a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <p style="margin-bottom: 10px">
          {{ item.noi_dung }}
        </p>

        <p class="discreet" style="margin-bottom: 7px">
          {{
            $t(
              "conservation-efforts.biodiversity-conservation-initiative.item.co_quan_to_chuc"
            )
          }}: {{ item.co_quan_to_chuc || $t("common.unknown") }}
        </p>
        <p class="discreet" style="margin-bottom: 7px">
          {{
            $t(
              "conservation-efforts.biodiversity-conservation-initiative.item.dia_diem_to_chuc"
            )
          }}: {{ item.dia_diem_to_chuc || $t("common.unknown") }}
        </p>
        <p class="discreet" style="margin-bottom: 7px">
          {{ $t("common.ghi_chu") }}:
          {{ item.ghi_chu || $t("common.unknown") }}
        </p>

        <ul class="list-chips">
          <li
            class="mr-2"
            v-for="f in liFilter"
            :key="f.key"
            @click="
              $emit('categoryChanged', {
                key: f.key,
                value: item[f.itemKey],
              })
            "
          >
            <a class="cursor-pointer">
              {{ f.label }}:
              {{
                item[f.itemKey]
                  ? item[f.itemKey][f.nameKey || "name"]
                  : $t("common.unknown")
              }}
            </a>
          </li>
          <li
            @click="
              $emit('yearChanged', {
                key: 'nam_thuc_hien',
                value: item.nam_thuc_hien,
              })
            "
            class="mr-2"
          >
            <a class="cursor-pointer">
              {{
                $t(
                  "conservation-efforts.biodiversity-conservation-initiative.item.nam"
                )
              }}:
              {{ item.nam_thuc_hien || $t("common.unknown") }}
            </a>
          </li>
          <li class="mr-2" v-for="f in item.dinh_kem" :key="f.id">
            <a :href="f.disk">{{ f.name }}</a>
          </li>
        </ul>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    item: Object,
    detailUrl: String,
    aFilter: Object,
    liFilter: Array,
    pFilter: Array,
  },
};
</script>
<style scoped>
.searchCard__type {
  background-color: #74b274;
  color: #fff;
}
</style>
