<template>
  <footer class="site-footer wrapper-horizontal-stripes">
    <div class="horizontal-stripe light-background site-footer__upper">
      <div class="container--normal">
        <nav class="site-footer__menu">
          <div>
            <span>
              <span>
                <span>
                  Copyright {{ date }} - Chi cục Bảo vệ môi trường Hà Nội,
                  <br />Sở TNMT thành phố Hà Nội, Việt Nam
                </span>
              </span>
            </span>
          </div>
        </nav>
      </div>
    </div>
    <div class="horizontal-stripe site-footer__lower">
      <div class="container--normal text-center">
        <img src="/images/logo/full_logo.png" />
        <div class="site-footer__social">
          <a
            href="https://www.youtube.com/channel/UCsmoNFV9OOGzdCSysl5s2HQ"
            class="btn btn-lg btn-circle"
            ><i class="gb-icon-youtube"></i
          ></a>
          <a
            href="https://www.facebook.com/disanthiennhienvietnam/"
            class="btn btn-lg btn-circle"
            ><i class="gb-icon-facebook"></i
          ></a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  data: function () {
    return {
      date: null,
    };
  },
  created() {
    this.date = new Date().getFullYear();
  },
};
</script>
<style scoped>
@media (max-width: 320px) {
  .horizontal-stripe {
    padding: 1.142857142855rem 0;
  }
}
.site-footer__social .btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
</style>
