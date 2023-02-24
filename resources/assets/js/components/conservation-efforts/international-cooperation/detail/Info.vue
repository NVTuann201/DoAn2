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
                        <tr>
                          <td dir="auto">
                            {{
                              $t(
                                "conservation-efforts.international-cooperation.item.hieu_luc"
                              )
                            }}
                          </td>
                          <td dir="auto">
                            <span v-if="data.hieu_luc">
                              <i class="fa fa-check"></i>
                            </span>
                            <span v-else>
                              <i class="fa fa-close"></i>
                            </span>
                          </td>
                        </tr>
                        <tr>
                          <td dir="auto">
                            {{
                              $t(
                                "conservation-efforts.international-cooperation.item.dinh_kem"
                              )
                            }}
                          </td>
                          <td dir="auto">
                            <a
                              v-for="f in data.dinh_kem"
                              :href="f.disk"
                              :key="f.id"
                              >{{ f.name }}</a
                            >
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
                    <h4>Nội dung chính</h4>
                    <p>{{ data.noi_dung_chinh }}</p>
                    <h4>Nội dung toàn văn</h4>
                    <p>{{ data.noi_dung_toan_van }}</p>
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