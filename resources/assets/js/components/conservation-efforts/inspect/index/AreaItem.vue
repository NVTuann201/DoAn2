<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a
          v-if="item.thoi_gian_ky || item.ngay_thong_bao"
          class="inherit searchCard__type hoverBox"
        >
          {{
            (type === "tinh" ? item.ngay_thong_bao : item.thoi_gian_ky)
              | moment("LL")
          }}
        </a>
        <h3 class="searchCard__headline">
          <a :href="detailUrl + item.id + `?type=${type}`">
            {{ item.so_van_ban || $t("common.unknown") }}
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

        <p class="discreet">
          <span
            >{{ $t("conservation-efforts.inspect.item.so_quyet_dinh") }}:</span
          >
          <span>{{
            item.doan_thanh_tra.so_quyet_dinh || $t("common.unknown")
          }}</span>
        </p>
        <p>
          <span>{{ $t("conservation-efforts.inspect.filter.vi_pham") }}: </span>
          <span v-if="item.vi_pham === null">{{ $t("common.unknown") }}</span>
          <span
            style="cursor: pointer"
            v-else
            @click="
              $emit('categoryChanged', {
                key: 'vi_pham',
                value: item.vi_pham
                  ? {
                      id: 1,
                      name: $t('conservation-efforts.inspect.filter.yes'),
                    }
                  : {
                      id: 0,
                      name: $t('conservation-efforts.inspect.filter.no'),
                    },
              })
            "
            >{{
              item.vi_pham
                ? $t("conservation-efforts.inspect.filter.yes")
                : $t("conservation-efforts.inspect.filter.no")
            }}</span
          >
        </p>
        <p>
          <span
            >{{ $t("conservation-efforts.inspect.filter.qd_xu_phat") }}:
          </span>
          <span v-if="item.qd_xu_phat === null">{{
            $t("common.unknown")
          }}</span>
          <span
            style="cursor: pointer"
            v-else
            @click="
              $emit('categoryChanged', {
                key: 'qd_xu_phat',
                value: item.qd_xu_phat
                  ? {
                      id: 1,
                      name: $t('conservation-efforts.inspect.filter.yes'),
                    }
                  : {
                      id: 0,
                      name: $t('conservation-efforts.inspect.filter.no'),
                    },
              })
            "
            >{{
              item.qd_xu_phat
                ? $t("conservation-efforts.inspect.filter.yes")
                : $t("conservation-efforts.inspect.filter.no")
            }}</span
          >
        </p>
        <ul class="list-chips">
          <li
            v-if="type === 'tinh'"
            class="mr-2"
            @click="
              $emit('categoryChanged', {
                key: 'tinh_thanh_ids',
                value: item.tinh_thanh,
              })
            "
          >
            <a class="cursor-pointer">
              {{ $t("conservation-efforts.inspect.filter.tinh_thanh") }}:
              {{
                item["tinh_thanh"]
                  ? item.tinh_thanh.name_vietnamese
                  : $t("common.unknown")
              }}
            </a>
          </li>
          <li v-if="type === 'co_so'" class="mr-2">
            <a class="cursor-pointer">
              {{ item.co_so | coSo(item.co_so_type, coSoTypes) }}
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
    type: String,
  },
  filters: {
    coSo(item, coSoType, coSoTypes) {
      if (!item || !coSoType) return "";
      console.log(item, coSoType, coSoTypes);
      const name = item[coSoTypes[coSoType]["key"]];
      const type = coSoTypes[coSoType]["label"];
      return `${type}: ${name}`;
    },
  },
  computed: {
    coSoTypes() {
      return {
        "App\\ProtectedArea": {
          key: "orig_name",
          label: this.$t("conservation-efforts.inspect.vuon_quoc_gia"),
        },
        "App\\CoSoBaoTon": {
          key: "ten",
          label: this.$t("conservation-efforts.inspect.co_so_bao_ton"),
        },
      };
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
