<template>
  <div class="horizontal-stripe article-header white-background">
    <div class="container--desktop">
      <div class="row">
        <header class="col-xs-12 text-center">
          <nav
            class="article-header__category"
            style="display: flex; justify-content: center"
          >
            <span class="">
              <a href="#">{{ dataset_type }}</a>
            </span>
            <span class="article-header__category__lower"
              >{{
                $t("nbds_lang.dataset_resources.publication_date") +
                ": " +
                publication_date
              }}
            </span>
          </nav>
          <h1 class="text-center">
            <span class="ng-binding">{{ title }}</span>
          </h1>
          <p class="source">
            <span>{{ $t("nbds_lang.published_by") }}</span>
            <a
              class="inherit underline"
              :href="'/detail/publisher/' + organization.id"
              >{{ organization.name }}
            </a>
          </p>
          <ol class="inline-bullet-list">
            <li v-if="organization.email">
              <a class="inherit ng-binding" v-if="has_contacts_info">
                <i class="gb-icon-mail"></i>{{ organization.contacts }}
              </a>
              <a class="inherit ng-binding" v-else>
                <i class="gb-icon-mail"></i>{{ organization.email }}
              </a>
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
      dataset_type: "---",
      title: "---",
      publication_date: null,
      organization: {
        name: null,
        organization_type: null,
        email: null,
        contacts: null,
      },
    };
  },
  mounted() {
    if (this.value.dataset_type) {
      this.dataset_type = this.value.dataset_type;
    }
    if (this.value.title) {
      this.title = this.value.title;
    }
    if (this.value.publication_date) {
      this.publication_date = this.$moment(this.value.publication_date).format(
        "LL"
      );
    }
    if (this.value.organization) {
      this.organization = this.value.organization;
    }
  },
};
</script>
