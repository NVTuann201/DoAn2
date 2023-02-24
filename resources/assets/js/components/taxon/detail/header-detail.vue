<template>
  <div>
    <section class="horizontal-stripe article-header white-background">
      <div class="container--desktop">
        <div class="row">
          <div class="col-xs-12 text-center">
            <nav class="article-header__category" style="text-align: center;">
              <span
                ><a href="/search/taxon">{{
                  $t("dataset_type.taxon")
                }}</a></span
              >
              <span
                class="article-header__category__lower"
                style="width: unset;"
                >{{ data.created_at | moment("dddd, MMMM Do YYYY") }}</span
              >
            </nav>
            <h1 class="text-center">
              <span>
                <span>
                  <i>{{ data.scientific_name }}</i>
                </span>
              </span>
            </h1>
            <div v-if="data.vernacular_name">
              <span class="vernacular ng-binding">
                {{ data.vernacular_name }}
              </span>
              {{ $t("nbds_lang.in_vietnam") }}
            </div>
            <div
              class="classification-list discreet rtl-supported inline-block"
            >
              <span
                ><a class="kingdom">{{ data.king_dom.name }}</a></span
              >
              <span
                ><a class="phylum">{{ data.phylum.name }}</a></span
              >
              <span
                ><a class="class">{{ data.class.name }}</a></span
              >
              <span
                ><a class="order">{{ data.order.name }}</a></span
              >
              <span
                ><a class="family">{{ data.family.name }}</a></span
              >
              <span
                ><a class="genus">{{ data.genus.name }}</a></span
              >
            </div>
          </div>
        </div>
        <div class="stripe-comment">
          <div>
            Synced
            <span>{{ data.created_at | moment("from", "now") }}</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
export default {
  name: "header-detail",
  props: ["value"],
  data: function () {
    return {
      data: null,
      popUpSearch: -1,
    };
  },
  created() {
    if (this.value) {
      this.data = this.value;
    }
  },
  methods: {},
};
</script>
