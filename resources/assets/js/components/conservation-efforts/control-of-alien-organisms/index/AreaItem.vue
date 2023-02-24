<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a
          v-if="item.thoi_gian_bat_dau && item.thoi_gian_ket_thuc"
          class="inherit searchCard__type hoverBox"
        >
          {{ item.thoi_gian_bat_dau | moment("LL") }} -
          {{ item.thoi_gian_ket_thuc | moment("LL") }}
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
              "conservation-efforts.control-of-alien-organisms.item.don_vi_thuc_hien"
            )
          }}: {{ item.don_vi_thuc_hien || $t("common.unknown") }}
        </p>
        <p class="discreet" style="margin-bottom: 7px">
          {{
            $t(
              "conservation-efforts.control-of-alien-organisms.item.san_pham_chinh"
            )
          }}: {{ item.san_pham_chinh || $t("common.unknown") }}
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
