<template>
  <div class="anchor-block--tabs">
    <section class="horizontal-stripe light-background seperator--b">
      <div class="container--desktop">
        <div class="row">
          <div class="col-xs-12">
            <div class="container-fluid card rtl-bootstrap">
              <div class="row card__content" v-if="data.mo_ta">
                <div
                  class="col-xs-12 col-sm-6 col-md-8"
                  v-if="data.mo_ta.length >= 750"
                >
                  <p>
                    {{ data.mo_ta.substring(0, 750) + "..." }}
                  </p>
                  <a class="m-l-1" href="#description">
                    {{ $t("nbds_lang.more") }}
                  </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-8" v-else>
                  <p>{{ data.mo_ta }}</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <section class="term-block">
                  <div class="term-block__header">
                    <h3>
                      {{ $t("nbds_lang.table_category_info") }}
                    </h3>
                  </div>
                  <div class="term-block__terms">
                    <table class="table table--compact">
                      <colgroup>
                        <col span="1" style="width: 50%" />
                        <col span="1" style="width: 50%" />
                      </colgroup>
                      <tbody>
                        <tr v-for="item in info" :key="item.key">
                          <td dir="auto">
                            {{ item.label }}
                          </td>
                          <td dir="auto">
                            {{ data[item.key] || $t("common.unknown") }}
                          </td>
                        </tr>
                        <slot name="additional-info"></slot>
                      </tbody>
                    </table>
                  </div>
                </section>
              </div>
              <div class="col-xs-12 col-sm-6">
                <section class="term-block">
                  <div class="term-block__header">
                    <h3>{{ $t("nbds_lang.table_category_others") }}</h3>
                  </div>
                  <div class="term-block__terms">
                    <table class="table table--compact">
                      <colgroup>
                        <col span="1" style="width: 50%" />
                        <col span="1" style="width: 50%" />
                      </colgroup>
                      <tbody>
                        <tr v-for="item in other" :key="item.key">
                          <td dir="auto">
                            {{ item.label }}
                          </td>
                          <td dir="auto">
                            {{ data[item.key] || $t("common.unknown") }}
                          </td>
                        </tr>
                        <slot name="additional-other"></slot>
                      </tbody>
                    </table>
                  </div>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
export default {
  props: ["other", "info", "data"],
};
</script>