<template>
  <div class="main_content">
    <humberger-menu-overlay></humberger-menu-overlay>
    <humberger-menu-wrapper></humberger-menu-wrapper>
    <page-header></page-header>
    <router-view></router-view>
    <search></search>
    <page-footer></page-footer>
  </div>
</template>

<script>
import HumbergerMenuOverlay from "./humberger/HumbergerMenuOverlay";
import HumbergerMenuWrapper from "./humberger/HumbergerMenuWrapper";
import PageHeader from "./header/PageHeader";
import PageFooter from "./footer/PageFooter";
import Search from "./search/Search";

export default {
  data() {
    return {};
  },
  components: {
    HumbergerMenuOverlay,
    HumbergerMenuWrapper,
    PageHeader,
    PageFooter,
    Search
  },
};
</script>

<style>
.widget__title h6 {
  background: #fee2d9;
  height: 40px;
  width: 100%;
  line-height: 40px;
  border-radius: 2px;
  margin-bottom: 30px;
  font-family: "Quicksand", sans-serif;
  font-weight: 700;
  color: #232d37;
  display: flex;
  justify-content: center;
  text-transform: capitalize;
}

.categories__item {
  border-radius: 20px;
}

.categories__hover__text {
  border-radius: 15px;
}

.sidebar__item__follow__links {
  display: flex;
  justify-content: space-between;
}
</style>