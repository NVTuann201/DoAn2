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
          <a :href="detailUrl + item.id">
            {{ item.ten || $t("common.unknown") }}
          </a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <p
          style="margin-bottom: 5px"
          v-if="
            item.noi_dung_chinh != null && item.noi_dung_chinh.length >= 200
          "
        >
          {{ item.noi_dung_chinh.substring(0, 200) + "..." }}
        </p>
        <p v-else>{{ item.noi_dung_chinh }}</p>
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
