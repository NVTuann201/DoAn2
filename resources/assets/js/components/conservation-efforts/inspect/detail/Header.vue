<template>
  <div class="horizontal-stripe article-header white-background">
    <div class="container--desktop">
      <div class="row">
        <header class="col-xs-12 text-center">
          <nav
            class="article-header__category article-header__category--deep"
            style="text-align: center !important"
          >
            <span class="article-header__category__upper" style="width: unset">
              <a href="#">{{
                type === "tinh"
                  ? "Kết quả thanh tra tỉnh"
                  : "Kết quả thanh tra cơ sở"
              }}</a>
            </span>
          </nav>
          <h1 class="text-center">
            <span>{{ data.so_van_ban }}</span>
          </h1>
          <ol style="padding: 0">
            <li>
              <p v-if="type === 'tinh'" class="inherit">
                {{ $t("common.tinh_thanh") + ": " }}
                <span v-if="!(data.tinh_thanh instanceof Array)">
                  {{
                    data.tinh_thanh
                      ? data.tinh_thanh.name_vietnamese
                      : $t("common.unknown")
                  }}
                </span>
                <template v-else>
                  <span v-if="data.tinh_thanh.length === 0">{{
                    $t("common.unknown")
                  }}</span>
                  <span v-else>
                    {{
                      data.tinh_thanh
                        .map((item) => item.name_vietnamese)
                        .join(", ")
                    }}
                  </span>
                </template>
              </p>
              <p v-else class="inherit">
                {{ data.co_so | coSo(data.co_so_type, coSoTypes) }}
              </p>
            </li>
          </ol>
        </header>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "type"],
  filters: {
    coSo(item, coSoType, coSoTypes) {
      if (!item || !coSoType) return "";

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
