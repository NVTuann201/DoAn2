<template>
  <div class="full-height">
    <div class="full-height site-container" style="z-index: -1">
      <iframe style="width: 100%; height: 100%"
        :src="iframeUrl" title="Bản đồ Hà Nội"></iframe>
    </div>
    <div class="lopBanDo">
      {{ nameSidebar  }}
    </div>
  </div>
</template>
<script>


export default {
  props: ["quan_huyen"],
  data() {
    return {
      iframeUrl: null,
      nameSidebar: ''
    }
  },
  mounted() {
    this.getType()
  },
  methods: {
    getType() {
      this.iframeUrl = null;
      var url = new URL(window.location.href);
      var type = url.searchParams.get("type");
      switch (type) {
        case 'he-sinh-thai':
          this.iframeUrl = 'https://dulieudialymoitruong.ceid.gov.vn/chia-se-ban-do?layer_ids=MTAsMTEsMTIsMTMsMTksMTQsMTUsMTYsMTcsMTgs&center=105.39084739818732,%2021.057971829957548&zoom=8.297175623863693'
          this.nameSidebar = 'Dữ liệu bản đồ phân bố hệ sinh thái Hà Nội'
          break;
        case 'loai-quy-hiem':
          this.iframeUrl = 'https://dulieudialymoitruong.ceid.gov.vn/chia-se-ban-do?layer_ids=NCw4LA==&center=105.39084739818732,%2021.057971829957548&zoom=8.297175623863693'
          this.nameSidebar = 'Dữ liệu bản đồ phân bố loài quý hiếm Hà Nội'
          break;
        case 'loai-ngoai-lai':
          this.iframeUrl = 'https://dulieudialymoitruong.ceid.gov.vn/chia-se-ban-do?layer_ids=NywzLA==&center=105.39084739818732,%2021.057971829957548&zoom=8.297175623863693'
          this.nameSidebar = 'Dữ liệu bản đồ phân bố loài ngoại lai Hà Nội'
          break;
        case 'dia-gioi':
          this.iframeUrl = 'https://dulieudialymoitruong.ceid.gov.vn/chia-se-ban-do?layer_ids=OSw1LDYs&center=105.39084739818732,%2021.057971829957548&zoom=8.297175623863693'
          this.nameSidebar = 'Dữ liệu bản đồ địa giới Hà Nội'
          break;
          case 'tong-hop':
          this.iframeUrl = 'https://dulieudialymoitruong.ceid.gov.vn/chia-se-ban-do?layer_ids=MTAsMTEsMTIsMTMsMTksMTQsMTUsMTYsMTcsMTgsMyw0LDUsNiw3LDksOCw=&center=105.39084739818732,%2021.057971829957548&zoom=8.297175623863693'
          this.nameSidebar = 'Dữ liệu bản đồ Hà Nội'
          break;
      }

    }
  },
  watch: {},
};
</script>
<style lang="scss">
#map {
  width: 100%;
  height: 700px;
  margin-top: 25px;
}

.lopBanDo {
  width: 400px;
  height: 52px;
  position: absolute;
  left: 0px;
  top: 50px;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #58ac88;
  border: #58ac88;
  color: rgb(255, 255, 255);
  font-size: 18px;
}
</style>
  