<template>
  <article class="card--spaced card--flexible article-box hoverBox">
    <Skeleton :loading="true" width="100%" height="151px"> </Skeleton>
    <div class="card__stripe--expandable inherit">
      <div class="card__content">
        <h4 class="article-box__title">
          <Skeleton :count="2" :loading="true"></Skeleton>
          <Skeleton :loading="true" width="70px"></Skeleton>
        </h4>
      </div>
    </div>
  </article>
</template>

<script>
import { Skeleton } from "vue-loading-skeleton";
export default {
  components: { Skeleton },
};
</script>