<template>
  <div>
    <div class="tabs">
      <ul>
        <li
          v-for="tab in tabs"
          :class="{ 'is-active': tab.isActive }"
          :key="tab"
        >
          <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
        </li>
      </ul>
    </div>


  </div>
</template>

<script>
import TabVertical from "../../tabVertical.vue";

export default {
    components: {TabVertical},
  data: () => ({
    tabs: [],
  }),
  created() {
    this.tabs = this.$children;
  },
  methods: {
    selectTab(selectedTab) {
      this.tabs.forEach((tab) => {
        tab.isActive = tab.name == selectedTab.name;
      });
    },
  },
};
</script>

<style>
</style>