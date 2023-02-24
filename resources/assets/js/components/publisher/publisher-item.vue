<template>
  <article class="card m-b-05 searchCard rtl-supported">
    <div class="card__stripe">
      <div class="card__content">
        <span
          class="searchCard__type cursor-pointer"
          v-if="item.organization_type"
          @click="$emit('click:searchOrganizationType', item.organization_type)"
        >
          {{ item.organization_type }}</span
        >
        <h3 class="searchCard__headline">
          <a :href="'/detail/publisher/' + item.id">{{ item.name }}</a>
        </h3>
      </div>
    </div>
    <div class="card__stripe">
      <div class="card__content searchCard__content clearfix">
        <div class="searchCard__img loaded" v-if="item.mediable.media_id">
          <img
            :src="
              '/storage/' +
              item.mediable.media_id +
              '/' +
              item.mediable.media.file_name
            "
            class="publisher_logo"
          />
        </div>
        <div class="searchCard__meta">
          <span
            >{{ $t("nbds_lang.joined") + " " }}
            {{ item.created_at | moment("from", "now") }}</span
          >
        </div>
        <p v-if="item.description != null && item.description.length >= 200">
          {{ item.description.substring(0, 200) + "..." }}
        </p>
        <p v-else>{{ item.description }}</p>

        <ul class="list-chips">
          <li v-if="item.parent">
            <a :href="'/detail/publisher/' + item.parent.id">{{
              $t("nbds_lang.organizations.parent_organization_id") +
              ": " +
              item.parent.name
            }}</a>
          </li>
          <li v-if="item.dataset_resources_count > 0">
            <a :href="'/search/dataset?publisher=' + item.id">{{
              $n(item.dataset_resources_count) +
              " " +
              $t("nbds_lang.submenu_dataset")
            }}</a>
          </li>
        </ul>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: { item: {} },
};
</script>

<style scoped>
</style>
