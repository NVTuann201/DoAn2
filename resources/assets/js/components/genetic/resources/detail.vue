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
                        <nav class="
                            article-header__category
                            article-header__category--deep
                            text-center
                          ">
                          <span class="article-header__category__upper">
                            {{ data.nhom_gen ? data.nhom_gen.ten : "" }}
                          </span>
                          <span class="article-header__category__lower">{{ data.loai_gen ? data.loai_gen.ten : "" }}
                          </span>
                        </nav>
                        <br />
                        <h1 class="text-center">
                          <span>{{
                            data.ten ||
                            data.ten_khoa_hoc ||
                            data.ten_thong_thuong ||
                            data.ten_dan_toc
                          }}</span>
                        </h1>
                      </header>
                    </div>
                    <hr />
                  </div>
                </div>
                <section class="
                    horizontal-stripe--paddingless
                    white-background
                    seperator--b
                    ">
                  <div class="d-flex"
                    style="justify-content: center; padding-bottom: 50px; padding-left: 50px; padding-right: 50px;">
                    <div style="width: 50%; justify-content: center; display: flex">
                      <div>
                        <div class="title-group">Th??ng tin</div>
                        <div>T??n khoa h???c: {{ data.ten_khoa_hoc }}</div>
                        <div>T??n th??ng th?????ng: {{ data.ten_thong_thuong }}</div>
                        <div>T??n t??n d??n t???c: {{ data.ten_dan_toc }}</div>
                        <div>?????c ??i???m: {{ data.dac_diem }}</div>
                        <div>
                          Gen qu?? hi???m:
                          {{ data.gen_quy_hiem ? data.gen_quy_hiem.name : "" }}
                        </div>
                        <br />
                        <div class="title-group">N??i luu tr???</div>
                        <div v-if="data.noi_luu_gius && data.noi_luu_gius.length">
                          <div v-for="item in data.noi_luu_gius" :key="item.id">
                            <div>{{ item.ten }}</div>
                          </div>
                        </div>
                        <br />
                        <div>
                          <div class="title-group">T??nh tr???ng</div>
                          <div>
                            {{ data.tinh_trang ? data.tinh_trang.name : "" }}
                          </div>
                          <div>
                            {{
    data.tinh_trang_luu_tru
      ? data.tinh_trang_luu_tru.name
      : ""
}}
                          </div>
                          <div>
                            {{
    data.tinh_trang_nghien_cuu
      ? data.tinh_trang_nghien_cuu.name
      : ""
}}
                          </div>
                          <div>
                            {{
    data.tinh_trang_su_dung
      ? data.tinh_trang_su_dung.name
      : ""
}}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div style="width: 50%; justify-content: center; display: flex">
                      <div>
                        <div class="title-group">Ngu???n g???c</div>
                        <div>
                          Ngu???n g???c c?? s???:
                          {{
    data.nguon_goc_co_so
      ? data.nguon_goc_co_so.name
      : ""
}}
                        </div>
                        <div>
                          Ngu???n g???c c?? s???:
                          {{
    data.nguon_goc_dia_phuong
      ? data.nguon_goc_dia_phuong.name
      : ""
}}
                        </div>
                        <div>
                          Ngu???n g???c Vi???t Nam:
                          {{
    data.nguon_goc_viet_nam
      ? data.nguon_goc_viet_nam.name
      : ""
}}
                        </div>
                        <br />
                        <div class="title-group">Th??ng tin kh??c</div>
                        <div>
                          Gen c?? ??i???u ki???n:
                          {{
    data.gen_co_dieu_kien
      ? data.gen_co_dieu_kien.name
      : ""
}}
                        </div>
                        <div>
                          Gen q??y hi???m:
                          {{ data.gen_quy_hiem ? data.gen_quy_hiem.name : "" }}
                        </div>
                        <div>
                          H??nh th???c b???o qu???n: {{ data.hinh_thuc_bao_quan }}
                        </div>
                        <div>M?? s??? qu???c gia: {{ data.ma_so_quoc_gia }}</div>
                        <div>M?? s??? t???nh: {{ data.ma_so_tinh }}</div>
                        <div>
                          Di???n t??ch l??u tr???: {{ data.dien_tich_luu_giu }}
                        </div>
                        <div>
                          Bi???n ph??p b???o t???n: {{ data.bien_phap_bao_ton }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex" style="justify-content: center; padding-left: 50px; padding-right: 50px;">
                    <div v-if="loadingMedia && !checkTaxon">
                      <section style="
                          display: flex;
                          justify-content: center;
                          align-items: center;
                          flex-direction: column;
                        ">
                        <Loading :width="65" :height="65"></Loading>
                        <div style="padding-bottom: 20px">??ang t???i h??nh ???nh t??? Gbif</div>
                      </section>
                    </div>
                    <div v-else-if="!loadingMedia && !checkTaxon">
                      <div v-if="totalMedia > 0 && !checkTaxon" style="
                          width: 100%;
                          display: flex;
                          /* justify-content: space-between; */
                          align-items: center;
                        ">
                        <div style="flex: 1; display: flex; align-items: start">
                          <div style="font-size: 18px; font-weight: bold; padding-right: 20px">
                            H??n {{ Intl.NumberFormat().format(totalMedia) }} H??nh ???nh (T??? Gbif)
                          </div>
                          <div>
                            <!-- <img :src="gbifLogo" height="40px" /> -->
                          </div>
                        </div>
                        <div>
                          <div style="padding-bottom: 20px">
                            <button type="button" title="Tr?????c ????" class="btn btn-info btn-sm"
                              @click="showOtherMedia('back')" v-if="page > 0">
                              <i class="fas fa-angle-double-left"></i>
                            </button>
                            <button type="button" title="Ti???p theo" class="btn btn-info btn-sm"
                              @click="showOtherMedia('next')" v-if="page < totalPage">
                              <i class="fas fa-angle-double-right"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div v-if="totalMedia > 0 && !checkTaxon" class="d-flex"
                        style="flex-wrap: wrap; padding-bottom: 20px; padding-top: 20px">
                        <section v-if="loadingMedia" style="
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            flex-direction: column;
                          ">
                          <Loading :width="65" :height="65"></Loading>
                          <div style="padding-bottom: 20px">??ang t???i h??nh ???nh t??? Gbif</div>
                        </section>

                        <div v-else v-for="(media, index) in medias" :key="index"
                          style="padding-right: 15px; padding-bottom: 15px">
                          <a :href="media.identifier" target="_blank">
                            <img :src="media.thumbnailLink" style="height: 170px; width: auto" />
                          </a>
                        </div>
                      </div>
                      <div style="float: right">
                        <button type="button" title="Tr?????c ????" class="btn btn-info btn-sm"
                          @click="showOtherMedia('back')" v-if="page > 0">
                          <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button type="button" title="Ti???p theo" class="btn btn-info btn-sm"
                          @click="showOtherMedia('next')" v-if="page < totalPage">
                          <i class="fas fa-angle-double-right"></i>
                        </button>
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
import * as env from "../../../env.js";
import Loading from "../../loading.vue";
import DataTable from "../../taxon/detail/dataTable";
export default {
  props: ["data"],
  // mounted(){
  //   console.log(this.data)
  // },
  components: {
    Loading,
    DataTable,
  },
  data: () => ({
    checkTaxon: false,
    loadingMedia: false,
    medias: [],
    totalMedia: 0,
    page: 0,
    per_page: 10,
    totalPage: 0,
    thumbnailLink: 'https://api.gbif.org/v1/image/unsafe/x260/'
  }),
  created() {
    // this.getListTaxon();
    this.getMedia();
    // this.getListDataset();
    // this.getListSpecies();
  },
  methods: {
    getMedia() {
      this.loadingMedia = true;
      axios
        .get(`${env.endpointhttp}gbifimages`, {
          params: {
            page: this.page,
            name: this.data.ten_khoa_hoc,
            per_page: this.per_page,
          },
        })
        .then((res) => {
          this.loadingMedia = false;
          this.totalMedia = res.data.count;
          this.medias = [];
          res.data.results.forEach((el) => {
            this.medias = this.medias.concat(el.media);
          });
          this.totalPage = (this.totalMedia / this.per_page).toFixed();
          this.medias = this.medias.map((el) => {
            el.thumbnailLink =
              this.thumbnailLink + el.identifier;
            return el;
          });
        });
    },
    showOtherMedia(type) {
      if (type == "next") {
        this.page = this.page + 1;
      }
      if (type == "back") {
        this.page = this.page - 1;
      }
      this.getMedia();
    },
  }
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