<template>
  <div class="white-box p-0 genetic">
    <tabs @clicked="tabClicked" @changed="tabChanged">
      <tab name="Nguồn gen">
        <NguonGen />
      </tab>
      <tab name="Nhóm nguồn gen"> <NhomGen :phanLoai="phanloai" /> </tab>
    </tabs>
  </div>
</template>

<script>
import NguonGen from "./nguongen.vue";
import NhomGen from "./nhomgen.vue";

export default {
  props: ['phanloai'],
  components: { NguonGen, NhomGen },
  data: () => ({}),
  methods: {
    tabClicked() {},
    tabChanged() {},
  },

};
</script>

<style >
.genetic .tabs-component {
  /* margin: 4em 0; */
}
.genetic .tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
}

@media (min-width: 700px) {
  .genetic .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
    margin-bottom: -1px;
  }
}

.genetic .tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.genetic .tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
  color: #666;
}

.genetic .tabs-component-tab.is-active {
  color: #000;
}

.genetic .tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .genetic .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: 0.5em;
    transform: translateY(2px);
    transition: transform 0.3s ease;
  }

  .genetic .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.genetic .tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  padding: 0.75em 1em;
  text-decoration: none;
}

.genetic .tabs-component-panels {
  padding: 4em 0;
}

@media (min-width: 700px) {
  .genetic .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    padding: 2em 2em;
  }
}
</style>