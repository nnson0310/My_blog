<template>
  <div class="footer__instagram">
    <div class="footer__instagram__avatar">
      <div class="footer__instagram__avatar--pic">
        <img src="../../img/footer/instagram-avatar.jpg" alt="" />
      </div>
      <div class="footer__instagram__avatar--text">
        <h5>@ foodieblog</h5>
        <span>23,7k follower</span>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-1.jpg"
          :style="styles"
        ></div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-2.jpg"
          :style="styles"
        ></div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-3.jpg"
          :style="styles"
        ></div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-4.jpg"
          :style="styles"
        ></div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-5.jpg"
          :style="styles"
        ></div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-4 col-6">
        <div
          class="footer__instagram__item set-bg"
          data-setbg="img/footer/ip-6.jpg"
          :style="styles"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      image: '',
      styles: {
        
      }
    }
  },
};
</script>

<style scoped>
.footer__instagram__item {
  background-image: url('../../img/footer/ip-1.jpg');
}
</style>
