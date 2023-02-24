<template>
  <div>
    <div class="image-gallery">
      <template v-for="item in images">
        <a
          :key="item.id"
          :style="`background-image: url('${item.thumbnail}')`"
          @click="onView(item)"
        >
          <img :alt="item.name" :src="`${item.thumbnail}`" v-show="false" />
          <div>{{ item.name }}</div>
        </a>
      </template>
      <a
        class="image-gallery--loadmore"
        @click="loadMore"
        v-if="!loading && !noMoreItem"
      >
        <span>{{ $t("nbds_lang.more") }}</span>
      </a>
      <a class="image-gallery--loading" v-if="loading">
        <components-loading :width="65" :height="65"></components-loading>
      </a>
      <section v-else-if="images.length == 0" style="">
        {{ $t("nbds_lang.no_information") }}
      </section>
    </div>
    <div class="modal smop_image_preview" :class="{ show: isShowPreivew }">
      <!-- The Close Button -->
      <span class="close" @click="onClosePreview">&times;</span>

      <div class="modal-content smop_image_preview-content" v-if="itemPreview">
        <div class="file-container">
          <div>
            <img :src="itemPreview.link" class="img-responsive" />
          </div>
        </div>
        <div class="file-info-container">
          <div class="smop_image_preview-info">
            <slot name="item-detail" :value="itemPreview">
              <div class="p-20">
                <dl
                  v-for="(item, i) in valuesShow"
                  :key="i"
                  class="smop_image_preview-info-other"
                >
                  <dt>{{ item.text }}</dt>
                  <dd>{{ item.value }}</dd>
                </dl>
              </div>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { image_url } from "@/routes";
import { field_show } from "./upload/field.js";
export default {
  props: {
    url: {
      type: String,
      default: image_url,
    },
    params: { type: Object, default: () => ({}) },
  },
  components: {},
  data: () => ({
    loading: false,
    loadmore: false,
    images: [],
    paginate: {
      page: 1,
      perpage: 10,
    },
    noMoreItem: true,
    isShowPreivew: false,
    itemPreview: null,
    valuesShow: [],
  }),
  watch: {},
  created() {
    this.getData();
  },
  methods: {
    loadMore() {
      this.getData(this.paginate.page + 1);
    },
    onClosePreview() {
      this.isShowPreivew = false;
      this.itemPreview = null;
      this.valuesShow = [];
    },
    getData(page = 1) {
      this.loading = true;
      axios
        .get(this.url, {
          params: Object.assign(
            {
              page: page,
              perpage: this.paginate.perpage,
              paginate: true,
            },
            this.params
          ),
        })
        .then((res) => {
          let files = res.data.data.map((x) => ({
            id: x.id,
            name: x.title,
            file_type: "image",
            link: x.media && x.media[0] ? x.media[0].link : "",
            thumbnail: x.media && x.media[0] ? x.media[0].thumbnail : "",
            properties: x,
          }));
          this.paginate.page = res.data.current_page;
          this.noMoreItem =
            files.length < res.data.per_page ||
            this.images.length + files.length >= res.data.total;

          if (this.paginate.page == 1) {
            this.images = files;
          } else {
            this.images = this.images.concat(files);
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onView(item) {
      if (this.$listeners["click:view"]) {
        this.$emit("click:view", item);
      } else {
        this.isShowPreivew = true;
        this.itemPreview = item;
        field_show.forEach((x) => {
          if (this.itemPreview.properties[x.field]) {
            this.valuesShow.push({
              text: x.label,
              value: this.itemPreview.properties[x.field],
            });
          }
        });
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.image-gallery {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin: 0.714285714285rem;
  min-height: calc(100vh - 39.117142857129285714285714285714rem);

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
    &:not(.image-gallery--loading) > div {
      background: rgba(0, 0, 0, 0.3);
      color: #fff;
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 5px;
    }
    &.image-gallery--loading > div {
      color: #fff;
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 5px;
    }
  }
}

.image-gallery--loadmore,
.image-gallery--loading {
  color: #888;
  cursor: pointer;
  border: 1px solid #888;
  padding: 1.42857142857rem;
  display: flex !important;
  justify-content: center;
  align-items: center;
}
.image-gallery--loadmore:hover {
  background: #ccc;
}
@media screen and (min-width: 993px) {
  .image-gallery {
    a {
      max-width: 180px;
      height: 180px;
      width: 180px;
    }
  }
}

.smop_image_preview {
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
}
.smop_image_preview img {
  object-fit: contain;
}
.smop_image_preview .image-container > *:first-child {
  text-align: center;
}

/* Modal Content (Image) */
.modal-content {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  min-width: 400px;
  min-height: 300px;
  box-shadow: unset;
}

/* The Close Button */
.close,
.arrow-btn {
  position: absolute;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
  z-index: 20;
  opacity: 1;
}
.close {
  top: 15px;
  right: 35px;
}
.next-image {
  top: 50%;
  right: 15px;
}
.pre-image {
  top: 50%;
  left: 15px;
}
.close:hover,
.close:focus,
.pre-image:hover,
.pre-image:focus,
.next-image:hover,
.next-image:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

.smop_image_preview-info {
  overflow: hidden;
  font-size: 12px;
  font-style: italic;
  width: 100%;
  display: flex;
  flex-direction: column;
  max-height: 100%;
}
.modal-content {
  background-color: transparent;
  border: 0 solid #000;
}

.smop_form_group {
  display: flex;
  flex-wrap: wrap;
}

.smop_input_container {
  flex-grow: 1;
}

.smop_input_container .smop_form_control {
  width: 100% !important;
}

.smop_form_group .smop_image_upload_title {
  flex-grow: 0;
  flex-basis: 150px;
}
.file-container {
  background-color: #000;
  min-width: 335px;
  height: 100%;
  margin-right: 335px;
}
.file-container * {
  width: 100%;
  height: 100%;
}
.smop-gallery-container.random-list {
  margin: 0 !important;
}
.random-list .smop-gallery-item-container {
  padding: 0px;
}
.file-info-container {
  position: absolute;
  top: 0;
  right: 0;
  height: 100%;
  background-color: #fff;
  width: 335px;
  color: rgb(38, 38, 38);
  display: flex;
  align-items: center;
}
.smop_image_preview-name {
  flex-grow: 0;
}
.smop_image_preview-info-other {
  flex-grow: 1;
  overflow: auto;
}
.smop_image_preview-content {
  width: 75vw;
  height: 75vh;
  max-width: 900px;
  min-width: 400px;
  min-height: 300px;
  max-height: 700px;
}
.smop_image_preview-title {
  font-weight: bold;
  color: black;
  font-style: normal;
}
.smop_image_preview-list-item {
  padding-bottom: 4px;
}
.smop_image_preview-info-other {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.smop_image_preview-info-other::-webkit-scrollbar {
  display: none;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 900px) {
  .smop_image_preview-content {
    width: 100%;
    height: 100%;
  }
  .smop_image_preview-info {
    height: 100%;
  }
  .file-info-container {
    position: relative !important;
    width: 100%;
    display: block;
    max-height: 30%;
  }
  .file-container {
    margin: 0;
    height: 70% !important;
  }
  .arrow-btn {
    top: 20px;
  }
  .next-image {
    right: 70px;
  }
  .pre-image {
    right: 70px;
    right: 100px;
    left: unset;
  }
}
.preview-image {
  overflow: hidden;
}
.p-20 {
  padding: 20px;
}
</style>
