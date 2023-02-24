<template>
  <span>
    <a href id="distribution"></a>
    <div class="anchor-block--tabs">
      <div v-if="distribution">
        <h3>{{$t('nbds_lang.dataset_resources.distribution')}}</h3>
        <p v-if="this.distribution">{{this.distribution}}</p>
        <p v-else>{{$t('nbds_lang.no_information')}}</p>
      </div>
      <div class="" v-if="this.area&&this.area.protected_area">
        <h4>{{$t('nbds_lang.button_site_protected_areas')}}</h4>
        <div class="dataset-key__taxonomicCoverages__columns">
          <a href="#">
            <span>{{area.protected_area.name + '(' + area.protected_area.orig_name + ')'}}</span>
          </a>
        </div>
      </div>
    </div>
  </span>
</template>

<script>
export default {
  props: ["area", "distribution"],
  data: function () {
    return {};
  },
  created() {},
};
</script>

