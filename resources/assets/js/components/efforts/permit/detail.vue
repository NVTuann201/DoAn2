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
                            Ngày cấp: {{ data.ngay_cap }}
                          </span>
                          <span class="article-header__category__lower"
                            >Hiệu lực: {{ data.ngay_hieu_luc }}
                          </span>
                        </nav>
                        <h2 class="text-center">
                          <span>{{
                            data.loai_giay_phep ? data.loai_giay_phep.name : ""
                          }}</span>
                        </h2>
                        <div style="color: #2471a3; font-weight: bold">
                          Số hiệu: {{ data.so_hieu }}
                          <span
                            v-if="data.loai_cap"
                            style="font-size: 18px; color: #196f3d"
                          >
                            - {{ data.loai_cap.name }}</span
                          >
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
                      <div
                        class="d-flex"
                        style="justify-content: space-between"
                      >
                        <div style="width: 33%">
                          Ngày cấp: <b>{{ data.ngay_cap }}</b>
                        </div>
                        <div style="width: 33%">
                          Ngày hiệu lực: <b>{{ data.ngay_hieu_luc }}</b>
                        </div>
                        <div style="width: 33%">
                          Ngày hết hạn: <b>{{ data.ngay_het_han }}</b>
                        </div>
                      </div>
                      <br />
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
                      <div style="font-size: 16px; padding-top: 15px">
                        <div>
                          Cơ quan cấp phép:
                          <b>{{ data.co_quan_cap }}</b>
                        </div>
                        <div>
                          Đơn vị được cấp:
                          <b>{{ data.don_vi_duoc_cap }}</b>
                          <b
                            >-
                            {{ data.doi_tuong ? data.doi_tuong.name : "" }}</b
                          >
                        </div>
                        <div>
                          Mục đích:
                          <b>{{ data.muc_dich ? data.muc_dich.name : "" }}</b>
                        </div>
                      </div>
                      <div
                        style="
                          fomt-size: 18px;
                          font-weight: bold;
                          padding-top: 15px;
                        "
                      >
                        Thông tin
                      </div>

                      <div style="padding-top: 8px" class="d-flex">
                        <div style="flex: 1">
                          <div v-if="data.mau_nguon_gen">
                            Mẫu nguồn gen: {{ data.mau_nguon_gen }}
                          </div>
                          <div v-if="data.ten">
                            Tên thông thường: {{ data.ten }}
                          </div>
                          <div v-if="data.chung_loai">
                            Chủng loại: {{ data.chung_loai }}
                          </div>
                          <div v-if="data.don_vi_cung_cap">
                            Đơn vị cung cấp: {{ data.don_vi_cung_cap }}
                          </div>
                          <div v-if="data.dac_diem">
                            Đặc điểm: {{ data.dac_diem }}
                          </div>
                          <div v-if="data.su_kien_chuyen_gen">
                            Sự kiện chuyển gen: {{ data.su_kien_chuyen_gen }}
                          </div>
                          <div v-if="data.ma">Mã: {{ data.ma }}</div>
                          <div v-if="data.phuong_tien_khai_thac">
                            Phương tiện khai thác:
                            {{ data.phuong_tien_khai_thac }}
                          </div>
                        </div>
                        <div style="flex: 1">
                          <div v-if="data.ten_khoa_hoc">
                            Tên khoa học: {{ data.ten_khoa_hoc }}
                          </div>
                          <div v-if="data.dia_chi">
                            Địa chỉ: {{ data.dia_chi }}
                          </div>
                          <div v-if="data.so_luong">
                            Số lượng: {{ data.so_luong }}
                          </div>
                          <div v-if="data.kho_luong">
                            Khối lượng: {{ data.kho_luong }}
                          </div>
                          <div v-if="data.cach_tiep_can">
                            Cách tiếp cận: {{ data.cach_tiep_can }}
                          </div>
                          <div v-if="data.tinh_trang_lien_quan">
                            Tình trạng liên quan:
                            {{ data.tinh_trang_lien_quan }}
                          </div>
                          <div v-if="data.dac_diem_khai_thac">
                            Đặc điểm khai thác: {{ data.dac_diem_khai_thac }}
                          </div>
                          <div v-if="data.hinh_thuc_khai_thac">
                            Hình thức khai thác: {{ data.hinh_thuc_khai_thac }}
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