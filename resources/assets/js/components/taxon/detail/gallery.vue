<template>
  <div>
    <div id="mygallery" class="imageGallery">
      <a
        v-for="item in images"
        :key="item.id"
        :style="`background-image: url('/storage/resources/images/${item.identifier}')`"
      >
        <img
          class="imgGallery"
          :alt="item.darwin_core_taxon.scientific_name"
          :src="`/storage/resources/images/${item.identifier}`"
          v-show="false"
        />
        <div>{{item.darwin_core_taxon.scientific_name}}</div>
      </a>
      <a
        class="imageGallery--loadmore"
        v-if="!loading && paging.currunt_page < paging.last_page"
        @click="loadMore"
      >
        <span>{{$t('nbds_lang.more')}}</span>
      </a>
    </div>
    <!-- <div id="mygallery">
      <a v-for="item in images" :key="item.id">
        <img
          class="imgGallery"
          :alt="item.darwin_core_taxon.scientific_name"
          :src="`/storage/resources/images/${item.identifier}`"
        />
        <div>{{item.darwin_core_taxon.scientific_name}}</div>
      </a>
    </div>-->
    <section v-if="loading" style="display: flex;
                        justify-content: center;height:calc(100vh - 40.117142857129285714285714285714rem);">
      <Loading :width="65" :height="65"></Loading>
    </section>
    <section
      v-if="!loading && images.length == 0"
      style="display: flex;
                        justify-content: center;height:calc(100vh - 40.117142857129285714285714285714rem);"
    >{{$t('nbds_lang.no_information')}}</section>
  </div>
</template>
<script>
import * as routes from "../../../routes";
import * as env from "../../../env.js";
import Loading from "../../loading.vue";

export default {
  props: ['id'],
  components: {
    Loading,
  },
  data: () => ({
    images: [],
    loading: false,
    paging: {
      currunt_page: 1,
      last_page: 1,
    },
    loadmore: false,
  }),
  created() {
    this.getData();
  },
  // mounted() {
  //   $(document).ready(function () {
  //     $(".imgGallery").load(function () {
  //       $("#mygallery").justifiedGallery();
  //     });
  //   });
  // },
  watch: {
    keyword(val) {
      this.getData();
    },
    filter_datasets: {
      handler(val) {
        this.paging.currunt_page = 1;
        this.getData();
      },
      deep: true,
    },
  },
  methods: {
    getData() {
      this.loading = true;

      axios
        .get(`${env.endpointhttp}taxonImage`, {
          params: {
            page: this.paging.currunt_page,
            id: this.id
          },
        })
        .then((res) => {
          this.images = this.loadmore
            ? this.images.concat(res.data.images.data)
            : res.data.images.data;
          this.paging.currunt_page = res.data.images.current_page;
          this.paging.last_page = res.data.images.last_page;
          this.loading = false;
          this.loadmore = false;
        });
    },
    loadMore() {
      if (this.paging.currunt_page < this.paging.last_page) {
        this.loadmore = true;
        this.paging.currunt_page++;
        this.getData();
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.imageGallery {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin: 0.714285714285rem;
    min-height:calc(100vh - 39.117142857129285714285714285714rem);

  & > a {
    display: block;
    margin: 0 0 0.714285714285rem 0.714285714285rem;
    background: center no-repeat #eee;
    background-size: contain;
    position: relative;
    max-width: 100px;
    width: 100px;
    height: 100px;
    overflow: hidden;
    flex: 1 1 auto;
    background-size: contain;
    img {
      display: none;
      max-width: 100%;
      vertical-align: middle;
    }
    & > div {
      background: rgba(0, 0, 0, 0.3);
      color: #fff;
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 5px;
    }
  }
}

// #mygallery {
//   display: flex;
//   flex-wrap: wrap;
//   justify-content: flex-start;
//   margin: 0.714285714285rem;
//   a {
//     display: block;
//     margin: 0 0 0.714285714285rem 0.714285714285rem;
//     background: center no-repeat #eee;
//     position: relative;
//     :hover {
//       box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.3);
//     }
//     img {
//       height: 100px;
//       min-width: 100px;
//     }
//     & > div {
//       background: rgba(0, 0, 0, 0.3);
//       color: #fff;
//       position: absolute;
//       bottom: 0;
//       width: 100%;
//       padding: 5px;
//     }
//   }
// }

.imageGallery--loadmore {
  color: #888;
  cursor: pointer;
  border: 1px solid #888;
  padding: 1.42857142857rem;
  display: flex !important;
  justify-content: center;
  align-items: center;
  &:hover {
    background: #ccc;
  }
}
@media screen and (min-width: 993px) {
  #mygallery {
    a {
      max-width: 180px;
      height: 180px;
      width: 180px;
    }
  }
}
</style>
