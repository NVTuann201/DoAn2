<template>
  <div class="horizontal-stripe article-header white-background">
    <div class="container--desktop">
      <div class="row">
        <header class="col-xs-12 text-center">
          <nav
            class="article-header__category article-header__category--deep"
            style="text-align: center !important"
          >
            <span class="article-header__category__upper" style="width: unset">
              <a href="#">{{ value.desig }}</a>
            </span>
          </nav>
          <h1 class="text-center">
            <span>{{ orig_name + " (" + name + ")" }}</span>
          </h1>
          <ol style="padding: 0">
            <li>
              <p class="inherit">
                {{
                  $t("nbds_lang.protected_areas.sub_loc") + ": " + value.sub_loc
                }}
              </p>
            </li>
          </ol>
        </header>
      </div>
    </div>
  </div>
</template>

<script>
import VueMoment from "vue-moment";

export default {
  props: ["value"],
  data: function () {
    return {
      orig_name: "---",
      name: "---",
      status_year: null,
      iucncat: null,
      desig_type: null,
    };
  },
  mounted() {
    if (this.value.status_year) {
      this.status_year = this.value.status_year;
    }
    if (this.value.iucncat) {
      this.iucncat = this.value.iucncat;
    }
    if (this.value.desig_type) {
      this.desig_type = this.value.desig_type;
    }
    if (this.value.orig_name) {
      this.orig_name = this.value.orig_name;
    }
    if (this.value.name) {
      this.name = this.value.name;
    }
  },
};
</script>
