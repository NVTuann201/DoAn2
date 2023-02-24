<template>
  <div class="full-width gallery-container" @resize="updateSize()">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    >
    </components-notifications>
    <div v-if="items.length == 0"></div>
    <div
      class="gallery-item-container"
      v-for="item in items"
      :key="item.id"
      :class="{
        'gallery-item-deleted': item.is_delete,
        'gallery-item-new': item.is_new,
      }"
      :style="styleGalleryItem"
    >
      <div class="gallery-item">
        <div v-if="isImage(item)">
          <img :src="item.thumbnail" alt="" />
        </div>
        <template v-else>
          <div>{{ item.name }}</div>
        </template>
        <div class="gallery-hover">
          <div class="text-truncate title">{{ item.name }}</div>
          <div>
            <a class="text-light p-10 btn-lg" @click.prevent="onPreview(item)">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </a>
            <a
              v-if="item.is_delete"
              class="text-danger p-10 btn-lg"
              @click.prevent="onResore(item)"
            >
              <i class="fa fa-history" aria-hidden="true"></i>
            </a>
            <a
              v-else
              class="text-danger p-10 btn-lg"
              @click.prevent="onDelete(item)"
            >
              <i class="fa fa-trash" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
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
            <slot name="item-detail" :value="itemPreview" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    items: { type: Array, require: true, default: () => [] },
    minSize: { default: 150 },
  },
  data: function () {
    return {
      styleGalleryItem: {},
      isShowPreivew: false,
      itemPreview: null,
      typeNotification: null,
      textNotification: null,
    };
  },
  mounted() {
    this.updateSize();
  },
  methods: {
    isImage(item) {
      return item.file_type == "image";
    },
    updateSize() {
      const sizeItem = this.getSize(this.$el.clientWidth);
      this.$set(this, "styleGalleryItem", {
        width: sizeItem + "px",
        height: sizeItem + "px",
      });
    },
    getSize(widthContainer, maxColumn = 4) {
      if (widthContainer < 1 || widthContainer < this.minSize) {
        return this.minSize;
      }
      if (widthContainer > 900)
        return widthContainer / maxColumn - 4 * 2 * maxColumn;
      if (widthContainer > 300) return widthContainer / 2 - 4 * 2 * 2;
      return widthContainer;
    },
    onClosePreview() {
      this.isShowPreivew = false;
      this.itemPreview = null;
    },
    onDelete(item) {
      item.is_delete = true;
      if (item.is_new) {
        this.$emit(
          "update:items",
          this.items.filter((x) => !x.is_new && x.id != item.id)
        );
      }
      this.$emit("click:delete", item);
    },
    onResore(item) {
      item.is_delete = false;
    },
    onPreview(item) {
      this.isShowPreivew = true;
      this.itemPreview = item;
    },
  },
};
</script>

<style lang="scss">
.gallery-preview {
  background-color: rgba(0, 0, 0, 0.5);
}
.gallery-container {
  display: flex;
  flex-wrap: wrap;
  margin-left: -4px;
  margin-right: -4px;
}
.gallery-hover {
  .title {
    font-weight: bold;
    width: 100%;
    font-size: 1.2em;
    text-align: center;
  }
  flex-direction: column;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #00000095;
  color: #fff;
  visibility: hidden;
  padding: 4px;
}
.gallery-item:hover > .gallery-hover {
  visibility: visible;
}
.gallery-item-container {
  height: 100%;
  width: 100%;
  padding: 4px;
}
.gallery-item {
  height: 100%;
  width: 100%;
  display: flex;
  position: relative;
  align-items: center;
  justify-self: center;
  & > div {
    height: 100%;
    width: 100%;
  }
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  iframe {
    height: 100%;
    width: 100%;
  }
}
.smop-gallery-container {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-wrap: wrap;
  margin: 0 -12px;
  padding-top: 12px 0;
}
.smop-gallery-item-container {
  flex-grow: 0;
  padding: 12px;
}
.smop-gallery-item {
  text-align: center;
  padding: 4px;
  border: 1px solid #ebebeb;
}

.smop-gallery-item > a {
  height: 100%;
  width: 100%;
  display: block;
}
.smop-gallery-item img {
  border-radius: 0;
  display: block;
  max-width: 100%;
  height: 100%;
  margin: auto;
  padding: 0;
  object-fit: cover;
}
video {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: black;
}

div.video-container {
  position: relative;
  height: 100%;
  width: 100%;
}
#smop_image_pagination {
  padding: 0 12px 12px;
}
#smop_image_load_more {
  color: #fff;
  height: 30px;
  padding: 0 12px;
  font-size: 1rem;
}
.random-list {
  width: 100%;
}

.random-list {
  width: 100%;
}

.random-list .smop-gallery-item-container {
  width: unset !important;
}
#smop_image_preview,
.smop_image_preview {
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
}
#smop_image_preview .video-container,
.smop_image_preview .video-container {
  height: 60vh;
  width: 60vw;
}
#smop_image_preview img,
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
.preview-image .go-top {
  z-index: 10 !important;
  opacity: 0 !important;
  display: none !important;
}
.preview-image .fb_dialog {
  z-index: 10 !important;
  opacity: 0 !important;
  display: none !important;
}
.gallery-item-container {
  border: 1px solid transparent;
}
.gallery-item-deleted {
  border-color: red;
}
.gallery-item-new {
  border-color: #408c5b;
}
</style>
