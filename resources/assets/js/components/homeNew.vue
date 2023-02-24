<template>
  <article class="card--spaced card--flexible article-box hoverBox">
    <a
      :href="item.link"
      class="card__stripe article-box__image"
      target="_blank"
    >
      <img :src="featuredmedia.source_url" alt="" class="article-thumb" />
      <span class="article-box__type">{{ newType }}</span>
    </a>
    <div class="card__stripe--expandable inherit">
      <div class="card__content">
        <h4 class="article-box__title">
          <a
            :href="item.link"
            class="inherit"
            target="_blank"
            v-if="item.title.rendered.length <= 60"
            >{{ item.title.rendered }}</a
          >
          <a :href="item.link" class="inherit" target="_blank" v-else>{{
            item.title.rendered.substring(0, 60) + "..."
          }}</a>
        </h4>
        <span class="article-box__footer">{{ item.date | formatDate }}</span>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: { item: {} },
  computed: {
    featuredmedia: function () {
      return this.item._embedded["wp:featuredmedia"][0];
    },
    newType: function () {
      if ((this.item.type = "post")) {
        return "News";
      } else {
        return "Other";
      }
    },
  },
};
</script>
