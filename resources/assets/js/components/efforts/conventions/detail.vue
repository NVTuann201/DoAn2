<template>
  <div class="site-wrapper">
    <main class="main" style="padding-top: 50px">
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
                        >
                          <span class="article-header__category__upper">
                            Ban hành: {{ data.ngay_ban_hanh }}
                          </span>
                          <span class="article-header__category__lower"
                            >Hiệu lực: {{ data.ngay_hieu_luc }}
                          </span>
                        </nav>
                        <h1 class="text-center">
                          <span>{{ data.ten }}</span>
                        </h1>
                        <div v-if="data.hieu_luc">Còn hiệu lực</div>
                        <div v-else style="color: red; font-weight: bold">
                          Hết hiệu lực
                        </div>
                      </header>
                    </div>
                    <hr />
                  </div>
                </div>
                <section
                  class="
                    horizontal-stripe--paddingless
                    white-background
                    seperator--b
                  "
                >
                  <div
                    class="d-flex"
                    style="justify-content: center; padding-bottom: 50px"
                  >
                    <div style="max-width: 1024px">
                      <div v-if="data.fileList && data.fileList.length">
                        <b>Tệp đính kèm</b>
                        <div
                          v-for="item in data.fileList"
                          :key="item.id"
                          @click="taiTapTin(it)"
                          style="
                            cursor: pointer;
                            padding-top: 5px;
                            font-size: 16px;
                          "
                        >
                          <i
                            class="fas fa-file-alt"
                            style="padding-right: 10px"
                          ></i>
                          {{ item.name }} -
                          {{ Math.round(item.size / 1024) + "Kb" }}
                        </div>
                      </div>
                      <div style="padding-top: 15px">
                        <b style="font-size: 16px">Thời gian</b>
                        <div>Ngày ban hành: {{ data.ngay_ban_hanh }}</div>
                        <div>Ngày hiệu lực: {{ data.ngay_hieu_luc }}</div>
                        <div>
                          Ngày Việt Nam tham gia:
                          {{ data.ngay_viet_nam_tham_gia }}
                        </div>
                      </div>
                      <div style="padding-top: 15px">
                        <b style="font-size: 16px">Nội dung chính</b>
                        <div>{{ data.noi_dung_chinh }}</div>
                      </div>
                      <div style="padding-top: 15px">
                        <b style="font-size: 16px">Nội dung toàn văn</b>
                        <div>{{ data.noi_dung_toan_van }}</div>
                      </div>
                      <div style="padding-top: 15px">
                        <b>Ghi chú</b>
                        <div>{{ data.ghi_chu }}</div>
                      </div>
                    </div>
                  </div>
                </section>
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
  mounted() {
    console.log(this.data);
  },
  methods: {
    taiTapTin(item) {
      window.location.href = "/" + item.disk;
    },
  },
};
</script>

<style scoped>
.c-flex {
  display: flex;
  flex-direction: column;
}
.d-flex {
  display: flex;
  flex-direction: row;
}
.title-group {
  font-size: 18px;
  font-weight: bold;
  padding-bottom: 6px;
}
</style>