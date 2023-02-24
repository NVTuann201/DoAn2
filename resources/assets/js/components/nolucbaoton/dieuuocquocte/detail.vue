<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <div class="horizontal-stripe article-header white-background">
                  <div class="container--desktop">
                    <div class="row">
                      <header class="col-xs-12 text-center">
                        <nav
                          class="
                            article-header__category
                            article-header__category--deep
                          "
                          style="text-align: center !important"
                        >
                          <span
                            class="article-header__category__upper"
                            style="width: unset"
                          >
                            <a href="#">{{
                              $t("nbds_lang.submenu_international_conventions")
                            }}</a>
                          </span>
                        </nav>
                        <h1 class="text-center">
                          <span>{{ data.ten }}</span>
                        </h1>
                      </header>
                    </div>
                  </div>
                </div>
                <div class="anchor-block--tabs">
                  <section
                    class="horizontal-stripe light-background seperator--b"
                  >
                    <div class="container--desktop">
                      <div class="row">
                        <div class="col-xs-12">
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
                                      <col span="1" style="width: 60%" />
                                      <col span="1" style="width: 40%" />
                                    </colgroup>
                                    <tbody>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "dieu_uoc_quoc_te.item.ngay_ban_hanh"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{
                                            data.ngay_ban_hanh | moment("LL")
                                          }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "dieu_uoc_quoc_te.item.ngay_hieu_luc"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{ data.ngay_hieu_luc }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "dieu_uoc_quoc_te.item.ngay_viet_nam_tham_gia"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{ data.ngay_viet_nam_tham_gia }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "dieu_uoc_quoc_te.item.so_nuoc_tham_gia"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{ data.so_quoc_gia_tham_gia }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{
                                            $t(
                                              "dieu_uoc_quoc_te.item.trang_thai"
                                            )
                                          }}
                                        </td>
                                        <td dir="auto">
                                          {{
                                            data.hieu_luc
                                              ? $t(
                                                  "dieu_uoc_quoc_te.item.con_hieu_luc"
                                                )
                                              : $t(
                                                  "dieu_uoc_quoc_te.item.het_hieu_luc"
                                                )
                                          }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td dir="auto">
                                          {{ $t("file") }}
                                        </td>
                                        <td dir="auto">
                                          <a
                                            style="display: block"
                                            v-for="item in data.files"
                                            :key="item.id"
                                            :href="item.disk"
                                            >{{ item.name }}</a
                                          >
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </section>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                              <section class="term-block">
                                <div class="term-block__header">
                                  <h3>
                                    {{
                                      $t("dieu_uoc_quoc_te.item.noi_dung_chinh")
                                    }}
                                  </h3>
                                </div>
                              </section>
                              <p>{{ data.noi_dung_chinh }}</p>
                            </div>
                            <div class="col-xs-12">
                              <section class="term-block">
                                <div class="term-block__header">
                                  <h3>
                                    {{
                                      $t(
                                        "dieu_uoc_quoc_te.item.noi_dung_toan_van"
                                      )
                                    }}
                                  </h3>
                                </div>
                              </section>
                              <p>{{ data.noi_dung_toan_van }}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  props: ["data"],
  components: {},

  methods: {},
};
</script>
<style scoped>
.site-content__page {
  min-height: calc(100vh - 18.542857142848571428571428571429rem);
}
.tabs__wrapper {
  z-index: 1;
}
</style>
