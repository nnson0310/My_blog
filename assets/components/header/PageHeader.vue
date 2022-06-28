<template>
  <header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row">
          <header-humberger></header-humberger>
          <header-menu></header-menu>
          <header-search></header-search>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <header-subscribe></header-subscribe>
        <header-logo></header-logo>
        <header-social></header-social>
      </div>
    </div>
  </header>
</template>

<script>
import HeaderHumberger from "./HeaderHumberger.vue";
import HeaderMenu from "./HeaderMenu.vue";
import HeaderSearch from "./HeaderSearch.vue";
import HeaderSubscribe from "./HeaderSubscribe.vue";
import HeaderLogo from "./HeaderLogo.vue";
import HeaderSocial from "./HeaderSocial.vue";

export default {
  data() {
    return {};
  },
  components: {
    HeaderMenu,
    HeaderSearch,
    HeaderSubscribe,
    HeaderLogo,
    HeaderSocial,
    HeaderHumberger
  },
};
</script>

<style scoped>
</style>
