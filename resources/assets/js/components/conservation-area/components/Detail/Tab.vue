<template>
  <section class="horizontal-stripe--paddingless white-background seperator--b">
    <div class="container--desktop">
      <div class="row">
        <div class="col-xs-12">
          <div class="tabs__wrapper">
            <nav class="tabs tabs--noBorder">
              <ul>
                <li
                  class="tab"
                  @click="$emit('change', t.id)"
                  style="cursor: pointer"
                  :class="{ isActive: tab === t.id }"
                  v-for="t in tabs"
                  :key="t.id"
                >
                  <a>
                    {{ t.label }}
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  props: ["tabs", "tab"],
};
</script>