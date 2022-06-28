<template>
  <footer class="footer">
    <div class="container-fluid">
      <footer-avatar></footer-avatar>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="footer__text">
            <footer-logo></footer-logo>
            <footer-description></footer-description>
            <footer-social></footer-social>
          </div>
          <footer-copyright></footer-copyright>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import FooterCopyright from './FooterCopyright.vue';
import FooterLogo from './FooterLogo.vue';
import FooterDescription from './FooterDesciption.vue';
import FooterSocial from './FooterSocial.vue';
import FooterAvatar from './FooterAvatar.vue';

export default {
  data() {
    return {

    }
  },
  components: {
    FooterCopyright,
    FooterLogo,
    FooterDescription,
    FooterSocial,
    FooterAvatar
  }
};
</script>

<style scoped>
</style>
