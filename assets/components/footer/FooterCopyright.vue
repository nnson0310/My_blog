<template>
  <div class="footer__copyright">
    <p>
      All rights reserved | Made with <i
        class="fa fa-heart"
        aria-hidden="true"
      ></i>
      by <b>Nguyễn Ngọc Sơn</b> | Powered by <a :href="link"><b>Vuejs</b></a>
    </p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      link: "https://vuejs.org/index.html",
    };
  },
};
</script>

<style scoped>
</style>
