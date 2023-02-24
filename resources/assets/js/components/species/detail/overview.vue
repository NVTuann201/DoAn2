<template>
  <div class="container--desktop m-t-2">
    <div v-if="loadingMedia && !checkTaxon">
      <section
        style="
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
        "
      >
        <Loading :width="65" :height="65"></Loading>
        <div style="padding-bottom: 20px">Đang tải hình ảnh từ Gbif</div>
      </section>
    </div>
    <div v-else-if="!loadingMedia && !checkTaxon">
      <div
        v-if="totalMedia > 0 && !checkTaxon"
        style="
          width: 100%;
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >
        <div style="flex: 1; display: flex; align-items: start">
          <div style="font-size: 18px; font-weight: bold; padding-right: 20px">
            Hơn {{ Intl.NumberFormat().format(totalMedia) }} Hình ảnh (Từ Gbif)
          </div>
          <div>
            <!-- <img :src="gbifLogo" height="40px" /> -->
          </div>
        </div>
        <div>
          <div style="padding-bottom: 20px">
            <button
              type="button"
              title="Trước đó"
              class="btn btn-info btn-sm"
              @click="showOtherMedia('back')"
              v-if="page > 0"
            >
              <i class="fas fa-angle-double-left"></i>
            </button>
            <button
              type="button"
              title="Tiếp theo"
              class="btn btn-info btn-sm"
              @click="showOtherMedia('next')"
              v-if="page < totalPage"
            >
              <i class="fas fa-angle-double-right"></i>
            </button>
          </div>
        </div>
      </div>
      <div
        v-if="totalMedia > 0 && !checkTaxon"
        class="d-flex"
        style="flex-wrap: wrap; padding-bottom: 20px; padding-top: 20px"
      >
        <section
          v-if="loadingMedia"
          style="
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
          "
        >
          <Loading :width="65" :height="65"></Loading>
          <div style="padding-bottom: 20px">Đang tải hình ảnh từ Gbif</div>
        </section>

        <div
          v-else
          v-for="(media, index) in medias"
          :key="index"
          style="padding-right: 15px; padding-bottom: 15px"
        >
          <a :href="media.identifier" target="_blank">
            <img :src="media.thumbnailLink" style="height: 170px; width: auto" />
          </a>
        </div>
      </div>
      <div style="float: right">
        <button
          type="button"
          title="Trước đó"
          class="btn btn-info btn-sm"
          @click="showOtherMedia('back')"
          v-if="page > 0"
        >
          <i class="fas fa-angle-double-left"></i>
        </button>
        <button
          type="button"
          title="Tiếp theo"
          class="btn btn-info btn-sm"
          @click="showOtherMedia('next')"
          v-if="page < totalPage"
        >
          <i class="fas fa-angle-double-right"></i>
        </button>
      </div>
    </div>
    <DataTable v-if="checkTaxon" :data="arrTaxon[0]"></DataTable>
    <table v-else class="table table--compact">
      <thead>
        <tr>
          <th style="width: 30%">
            <h4>
              {{ $t("nbds_lang.darwin_core_taxon.information.science_name") }}
            </h4>
          </th>
          <th style="width: 30%">
            <h4>
              {{
                $t("nbds_lang.darwin_core_taxon.information.vernacular_name")
              }}
            </h4>
          </th>
          <th>
            <h4>{{ $t("nbds_lang.darwin_core_occurrences.dataset_name") }}</h4>
          </th>
          <th style="width: 70px">
            <h4>{{ $t("nbds_lang.action") }}</h4>
          </th>
        </tr>
      </thead>
      <tbody id="class-body">
        <tr v-if="pagingTaxon.loading">
          <td colspan="4" :rowspan="arrTaxon.length">
            <section
              :style="`display: flex;
                        justify-content: center;align-items: center;${
                          last_length ? `height:${last_length}` : ``
                        }`"
            >
              <Loading :width="45" :height="45"></Loading>
            </section>
          </td>
        </tr>
        <template v-else>
          <tr v-for="item in arrTaxon" :key="item.id" class="isDifferent">
            <td>{{ item.scientific_name }}</td>
            <td>{{ item.vernacular_name }}</td>
            <td>{{ item.data_resource.title }}</td>
            <td>
              <a :href="`/detail/taxon/${item.id}`">{{
                $t("nbds_lang.detail")
              }}</a>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
    <paginate
      v-if="pagingTaxon.page_count > 1"
      v-model="pagingTaxon.current_page"
      :page-count="pagingTaxon.page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="clickCallback"
      :prev-text="'‹‹'"
      :next-text="'››'"
      :container-class="'pagination'"
      :page-class="'page-item'"
    ></paginate>
  </div>
</template>
<script>
import * as env from "../../../env.js";
import Loading from "../../loading.vue";
import DataTable from "../../taxon/detail/dataTable";
// import gbifLogo from "./gbif.png";
export default {
  props: ["item"],
  components: {
    Loading,
    DataTable,
  },
  data: () => ({
    checkTaxon: false,
    arrDataset: [],
    arrTaxon: [],
    last_length: null,
    paging: {
      currunt_page: 1,
      page_count: 1,
    },
    listSpecies: [],
    pagingDataset: {
      currunt_page: 1,
      page_count: 1,
      loading: false,
    },
    pagingTaxon: {
      currunt_page: 1,
      page_count: 1,
      loading: false,
    },
    loadingMedia: false,
    medias: [],
    totalMedia: 0,
    page: 0,
    per_page: 10,
    totalPage: 0,
    thumbnailLink: 'https://api.gbif.org/v1/image/unsafe/x260/'
  }),
  created() {
    this.getListTaxon();
    this.getMedia();
    // this.getListDataset();
    // this.getListSpecies();
  },
  watch: {
    // datasets: {
    //   handler(val) {
    //     this.arrDataset = [...new Set(val.map((item) => item.id))].map((id) => {
    //       return {
    //         id: id,
    //         title: val.find((x) => x.id == id).title,
    //       };
    //     });
    //     this.pagingDataset.page_count = Math.ceil(this.arrDataset.length / 10);
    //     this.arrDataset = this.paginator(10, this.arrDataset);
    //   },
    //   immediate: true,
    // },
    // taxon: {
    //   handler(val) {
    //     this.pagingTaxon.page_count = Math.ceil(val.length / 10);
    //     this.arrTaxon = this.paginator(10, val);
    //   },
    //   immediate: true,
    // },
  },
  methods: {
    getListTaxon() {
      this.pagingTaxon.loading = true;
      axios
        .get(
          `${env.endpointhttp}detail/indexspecies/getTaxon/${this.item.id}`,
          { params: { page: this.pagingTaxon.currunt_page } }
        )
        .then((res) => {
          this.arrTaxon = res.data.result.data;
          this.pagingTaxon.currunt_page = res.data.result.current_page;
          this.pagingTaxon.page_count = res.data.result.last_page;
          this.pagingTaxon.loading = false;
          if (this.pagingTaxon.page_count == 1 && this.arrTaxon.length == 1) {
            this.checkTaxon = true;
          }
        });
    },
    getListDataset() {
      this.pagingDataset.loading = true;
      axios
        .get(
          `${env.endpointhttp}detail/indexspecies/getDataset/${this.item.id}`,
          { params: { page: this.pagingTaxon.currunt_page } }
        )
        .then((res) => {
          this.arrDataset = res.data.result.data;
          this.pagingDataset.currunt_page = res.data.result.current_page;
          this.pagingDataset.page_count = res.data.result.last_page;
          this.pagingDataset.loading = false;
        });
    },
    getMedia() {
      this.loadingMedia = true;
      axios
        .get(`${env.endpointhttp}gbifimages`, {
          params: {
            page: this.page,
            name: this.item.name,
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
    getListSpecies() {
      let params = {
        searchSpecies: "species",
        page: this.paging.currunt_page,
      };
      params[`${this.item.rank}_id`] = this.item[`${this.item.rank}_id`];
      axios
        .get(env.endpointhttp + "searchspecies", {
          params,
        })
        .then((res) => {
          this.paging.currunt_page =
            res.data.result.result_species.current_page;
          this.paging.page_count = res.data.result.result_species.last_page;
          this.listSpecies = res.data.result.result_species.data;
        });
    },
    paginator(pergape = 10, arr) {
      let numPage = Math.ceil(arr.length / 10);
      let newArr = [];
      let to = 0;
      let from = pergape;
      for (let i = 0; i < numPage; i++) {
        newArr.push(arr.slice(to, from));
        to = from;
        from += 10;
      }
      return newArr;
    },
    goPaging(type, action) {
      switch (type) {
        case "dataset":
          if (action == "next") {
            this.pagingDataset.currunt_page++;
          } else if ((action = "previous")) {
            this.pagingDataset.currunt_page--;
          }
          this.getListDataset();
          break;
        case "taxon":
          if (action == "next") {
            this.pagingTaxon.currunt_page++;
          } else if ((action = "previous")) {
            this.pagingTaxon.currunt_page--;
          }
          this.getListTaxon();
          break;
        default:
          break;
      }
    },
    goDetailTaxon(id) {
      window.location.href = `/detail/taxon/${id}`;
    },
    gotoDataset(id) {
      window.location.href = `/detail/dataset/${id}`;
    },
    clickCallback(n) {
      this.last_length = `${
        document.getElementById("class-body").clientHeight
      }px`;
      this.pagingTaxon.currunt_page = n;
      this.getListTaxon();
    },
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
  },
};
</script>
<style scoped lang="scss">
.isDifferent {
  td {
    padding-top: 0.357142857142rem !important;
    padding-bottom: 0.357142857142rem !important;
  }
}
.ml-auto {
  margin-left: auto;
}
.next--box {
  display: flex;
  justify-content: space-between;
  .next--box__button {
    box-shadow: 0 3px 8px 0 rgba(0, 0, 0, 0.16);
    font-size: 12px;
    text-transform: uppercase;
    line-height: 1.42857142857rem;
    text-align: center;
    padding: 3px 10px;
    display: inline-block;
    &:hover {
      text-decoration: none;
      background: #ddd;
      color: #666;
    }
  }
}
.list-container {
  .card--item {
    &:not(:first-child) {
      border-top: none;
    }
  }
}
.card--item {
  border: 1px solid #dbe3e7;
  padding: 20px 10px;

  a {
    color: #777;
    &:hover {
      text-decoration: underline;
    }
    font-size: 1rem;
  }
}
</style>
