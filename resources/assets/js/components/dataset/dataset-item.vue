<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <a
          :href="'/search/dataset?type=' + item.dataset_type"
          class="inherit searchCard__type hoverBox"
          >{{ item.dataset_type | datasetTypeName }}</a
        >
        <h3 class="searchCard__headline">
          <a :href="'/detail/dataset/' + item.id">
            {{ item.title }}
          </a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <p>{{ item.additional_info }}</p>
        <p class="discreet--very smaller">
          <span>
            {{ $t("nbds_lang.published_by") }}
          </span>
          <span>{{ item.organization.name }}</span>
        </p>

        <ul class="list-chips">
          <li
            class="loaded"
            v-if="
              item.dataset_type == 'Occurrence' &&
              item.darwin_core_occurrences_count > 0
            "
          >
            <a :href="'/search/occurrence?dataset=' + item.id">
              <span class="loaded">
                {{ $n(item.darwin_core_occurrences_count) }}
                {{ $t("nbds_lang.occurrences") }}
              </span>
            </a>
          </li>
          <li
            class="loaded"
            v-if="
              item.dataset_type == 'Taxon' && item.darwin_core_taxons_count > 0
            "
          >
            <a @click="searchTaxon(item.id)">
              <span class="loaded">
                {{ $n(item.darwin_core_taxons_count) }}
                {{ $t("nbds_lang.table_darwin_core_taxons") }}
              </span>
            </a>
          </li>
          <li class="loaded" v-if="item.dataset_references_count > 0">
            <a>
              <span class="loaded">
                {{ $n(item.dataset_references_count) }}
                {{ $t("nbds_lang.table_dataset_references") }}
              </span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: { item: {} },
  methods: {
    searchTaxon(value) {
      this.$emit("click:searchTaxon", value);
    },
  },
};
</script>

<style>
</style>
