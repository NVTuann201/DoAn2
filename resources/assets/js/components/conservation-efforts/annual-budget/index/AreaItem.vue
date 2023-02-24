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
            {{ item.muc_dich_su_dung || $t("common.unknown") }}
          </a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <p
          style="margin-bottom: 5px"
          v-if="item.mo_ta != null && item.mo_ta.length >= 200"
        >
          {{ item.mo_ta.substring(0, 200) + "..." }}
        </p>
        <p v-else>{{ item.mo_ta }}</p>
        <p
          v-for="f in pFilter"
          :key="f.key"
          class="discreet"
          @click="
            $emit('categoryChanged', {
              key: f.key,
              value: item[f.itemKey],
            })
          "
        >
          {{ f.label + ": " }}
          <span class="cursor-pointer">
            {{
              item[f.itemKey]
                ? item[f.itemKey][f.nameKey || "name"]
                : $t("common.unknown")
            }}
          </span>
        </p>

        <p class="discreet">
          {{ $t("conservation-efforts.annual-budget.item.ty_le_ngan_sach") }}:
          {{ item.ty_le_ngan_sach || $t("common.unknown") }}
        </p>
        <p class="discreet">
          {{ $t("conservation-efforts.annual-budget.item.total") }}:
          {{
            item.tong_kinh_phi !== null
              ? `${item.tong_kinh_phi
                  .toString()
                  .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")} (VND)`
              : $t("common.unknown")
          }}
        </p>
        <p class="discreet">
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
                key: 'nam_thu_nhap',
                value: item.thoi_gian,
              })
            "
            class="mr-2"
          >
            <a class="cursor-pointer">
              {{
                $t("conservation-efforts.annual-budget.filter.nam_thu_nhap")
              }}:
              {{ item.thoi_gian || $t("common.unknown") }}
            </a>
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
