<template>
  <div class="white-box p-0 quan-trac">
    <tabs
      @clicked="tabClicked"
      @changed="tabChanged"
    >
      <tab name="Loại hình"> 
        <loai-hinh></loai-hinh>
      </tab>
      <tab name="Thông số"> <thong-so></thong-so> </tab>
      <tab name="Điểm quan trắc"> <DiemQuanTrac/> </tab>
       <tab name="Kết quả quan trắc"> <KetQuaQuanTrac/> </tab>
      <!-- <tab name="Disabled tab" :is-disabled="true">
        This content will be unavailable while :is-disabled prop set to true
      </tab>
      <tab id="oh-hi-mark" name="Custom fragment">
        The fragment that is appended to the url can be customized
      </tab> -->
    </tabs>
  </div>
</template>

<script>
import LoaiHinh from './loaihinh.vue'
import ThongSo from './thongso.vue'
import DiemQuanTrac from './diemquantrac.vue'
import KetQuaQuanTrac from './ketqua.vue'

export default {
  components: {LoaiHinh, ThongSo, DiemQuanTrac, KetQuaQuanTrac},
  data: () => ({}),
  methods: {
    tabClicked(){

    },
    tabChanged(){

    }
  }
};
</script>

<style >
.quan-trac .tabs-component {
  /* margin: 4em 0; */
}
.quan-trac .tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
}

@media (min-width: 700px) {
  .quan-trac .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
    margin-bottom: -1px;
  }
}

.quan-trac .tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.quan-trac .tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
  color: #666;
}

.quan-trac .tabs-component-tab.is-active {
  color: #000;
}

.quan-trac .tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .quan-trac .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: 0.5em;
    transform: translateY(2px);
    transition: transform 0.3s ease;
  }

  .quan-trac .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.quan-trac .tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  padding: 0.75em 1em;
  text-decoration: none;
}

.quan-trac .tabs-component-panels {
  padding: 4em 0;
}

@media (min-width: 700px) {
  .quan-trac .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    padding: 2em 2em;
  }
}
</style>