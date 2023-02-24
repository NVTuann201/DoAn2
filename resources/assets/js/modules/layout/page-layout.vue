<template>
  <div class="white-box p-0" style="min-height: calc(100vh - 220px)">
    <!-- .left-right-aside-column-->
    <div class="page-aside">
      <!-- .left-aside-column-->
      <div class="left-aside">
        <div class="scrollable">
          <slot name="left-aside" />
        </div>
      </div>
      <!-- /.left-aside-column-->
      <div class="right-aside">
        <div class="right-page-header">
          <slot name="header-right-aside" />
        </div>
        <div class="clearfix"></div>
        <div class="scrollable">
          <slot name="right-aside" />
        </div>
      </div>
      <!-- .left-aside-column-->
    </div>
    <!-- /.left-right-aside-column-->
    <slot />
  </div>
</template>

<script>
export default {};
</script>

<style></style>
