<template>
  <div class="humberger__menu__wrapper" :class="activeHumberger">
    <humberger-menu-logo></humberger-menu-logo>
    <humberger-menu-nav></humberger-menu-nav>
    <div id="mobile-menu-wrap"></div>
    <humberger-menu-about></humberger-menu-about>
    <humberger-menu-subscribe></humberger-menu-subscribe>
  </div>
</template>

<script>
import HumbergerMenuLogo from "./HumbergerMenuLogo.vue";
import HumbergerMenuNav from "./HumbergerMenuNav.vue";
import HumbergerMenuAbout from "./HumbergerMenuAbout.vue";
import HumbergerMenuSubscribe from "./HumbergerMenuSubscribe.vue";
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      
    }
  },
  components: {
    HumbergerMenuAbout,
    HumbergerMenuSubscribe,
    HumbergerMenuLogo,
    HumbergerMenuNav,
  },
  computed: {
    ...mapGetters([
      'activeHumberger'
    ])
  }
};
</script>

<style scoped>
</style>
