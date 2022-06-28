<template>
  <div class="col-lg-2 col-md-1 col-6 order-md-1 order-1">
    <div class="header__humberger">
      <i class="fa fa-bars humberger__open" @click="toggleHumberger"></i>
    </div>
  </div>
</template>

<script>
import { mapMutations } from "vuex";

export default {
  data() {
    return {
      humbergerClass: 'show__humberger__menu__wrapper',
      overlayClass: 'active'
    };
  },
  methods: {
    ...mapMutations([
      'TOGGLE_HUMBERGER',
      'TOGGLE_OVERLAY'
    ]),
    toggleHumberger() {
      this.TOGGLE_HUMBERGER(this.humbergerClass);
      this.TOGGLE_OVERLAY(this.overlayClass);
    }
  },
};
</script>

<style scoped>
</style>