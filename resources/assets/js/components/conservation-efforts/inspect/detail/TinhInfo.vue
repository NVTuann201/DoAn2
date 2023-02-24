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
                        <tr>
                          <td dir="auto">Số quyết định</td>
                          <td dir="auto">
                            {{ data.doan_thanh_tra.so_quyet_dinh }}
                          </td>
                        </tr>
                        <tr>
                          <td dir="auto">Tỉnh thành</td>
                          <td dir="auto">
                            {{
                              data.tinh_thanh
                                ? data.tinh_thanh.name_vietnamese
                                : ""
                            }}
                          </td>
                        </tr>
                        <tr v-for="item in info" :key="item.field">
                          <td dir="auto">{{ item.label }}</td>
                          <td dir="auto">{{ data[item.field] }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </section>
              </div>
              <div class="col-xs-12 col-sm-6">
                <section class="term-block">
                  <div class="term-block__header">
                    <h3>Thành phần đoàn</h3>
                  </div>

                  <div class="term-block__terms">
                    <table class="table table--compact">
                      <thead>
                        <tr>
                          <th style="width: 60px">STT</th>
                          <th style="display: table-cell; min-width: 70px">
                            Tên
                          </th>
                          <th style="display: table-cell">Chức vụ</th>
                          <th style="display: table-cell; min-width: 70px">
                            Đơn vị công tác
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(item, idx) in data.thanh_phan_doan"
                          :key="idx"
                        >
                          <td>{{ idx + 1 }}</td>
                          <td>{{ item.ten }}</td>
                          <td>{{ item.chuc_vu }}</td>
                          <td>{{ item.don_vi_cong_tac }}</td>
                        </tr>
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
import { info } from "./tinh";
export default {
  props: ["data"],
  data() {
    return {
      info,
    };
  },
};
</script>