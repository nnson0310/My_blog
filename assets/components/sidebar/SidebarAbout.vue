<template>
  <div class="sidebar__about__item">
    <div class="widget__title">
      <h6>About me</h6>
    </div>
    <img src="../../img/sidebar/sidebar-about.jpg" alt="" />
    <h6>Hi every one! I,m <span>Lena Mollein.</span></h6>
    <p>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua.
    </p>
    <router-link @click.native="scrollToTop" to="/about_me" class="primary-btn">Read more</router-link>
  </div>
</template>

<script>
export default {
  data() {
    return {
      
    }
  },
  methods: {
    
  },
};
</script>

<style scoped>
  img {
    border-radius: 10px;
  }
</style>
