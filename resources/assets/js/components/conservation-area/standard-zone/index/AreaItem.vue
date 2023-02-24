<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a class="inherit searchCard__type hoverBox">
          {{ $t("conservation-area.standard-zone.item.khu_sinh_thai") }}:
          {{ item.khu_sinh_thai }}
        </a>
        <h3 class="searchCard__headline">
          <a>
            {{ item.ten }}
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

        <p class="discreet">
          {{ $t("conservation-area.standard-zone.item.hinh_dang") }}:
          {{ item.hinh_dang }}
        </p>
        <p class="discreet">
          {{ $t("conservation-area.standard-zone.item.vi_tri") }}:
          {{ item.vi_tri }}
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
          <li class="mr-2">
            <a class="cursor-pointer">
              {{ $t("conservation-area.standard-zone.item.do_cao_do_sau") }}:
              {{ item.do_cao }}
            </a>
          </li>
          <li class="mr-2">
            <a class="cursor-pointer">
              {{ $t("conservation-area.standard-zone.item.kich_thuoc") }}:
              {{ item.kich_thuoc }}
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
