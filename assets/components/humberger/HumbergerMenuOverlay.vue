<template>
  <div class="humberger__menu__overlay" :class="activeOverlay" @click="toggleHumberger"></div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";

export default {
  data() {
    return {
      humbergerClass: '',
      overlayClass: ''
    }
  },
  computed: {
    ...mapGetters([
      'activeOverlay'
    ])
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
  }
};
</script>

<style scoped>
</style>