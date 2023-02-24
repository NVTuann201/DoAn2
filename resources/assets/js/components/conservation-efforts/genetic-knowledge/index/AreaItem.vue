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
          Cấp giấy chứng nhận:
          {{ item.cap_giay_chung_nhan || $t("common.unknown") }}
        </p>
        <p class="discreet">
          Ghi chú:
          {{ item.ghi_chu || $t("common.unknown") }}
        </p>

        <ul class="list-chips">
          <li>
            Nguồn gen:
            <a
              v-for="subItem in item['nguon_gen']"
              :key="subItem.id"
              class="mr-2"
              @click="
                $emit('categoryChanged', {
                  key: 'nguon_gen_ids',
                  value: subItem,
                })
              "
            >
              {{ subItem["ten"] }}
            </a>
            <a v-if="item['nguon_gen'].length === 0">Không xác định</a>
          </li>

          <li class="mr-2">
            <a>
              Cấp giấy chứng nhận:
              {{ item.cap_giay_chung_nhan || $t("common.unknown") }}</a
            >
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
