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
              <a href="#">{{ data.type }}</a>
            </span>
          </nav>
          <h1 class="text-center">
            <span>{{ data.name }}</span>
          </h1>
          <ol style="padding: 0">
            <li>
              <p class="inherit" v-if="data.quan_huyen !== undefined">
                {{ $t("common.quan_huyen") + ": " }}
                <span v-if="!(data.quan_huyen instanceof Array)">
                  {{
                    data.quan_huyen
                      ? data.quan_huyen.name_vietnamese
                      : $t("common.unknown")
                  }}
                </span>
                <template v-else>
                  <span v-if="data.quan_huyen.length === 0">{{
                    $t("common.unknown")
                  }}</span>
                  <span v-else>
                    {{
                      data.quan_huyen
                        .map((item) => item.name_vietnamese)
                        .join(", ")
                    }}
                  </span>
                </template>
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
  props: ["data"],
};
</script>
