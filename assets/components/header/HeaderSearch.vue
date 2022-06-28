<template>
  <div class="col-lg-2 col-md-1 col-6 order-md-3 order-2">
    <div class="header__search" >
      <i class="fa fa-search search-switch" @click="showSearch"></i>
    </div>
  </div>
</template>

<script>
import { mapMutations } from "vuex";

export default {
  data() {
    return {
      
    }
  },
  methods: {
    ...mapMutations([
      'SHOW_SEARCH',
    ]),
    showSearch() {
      this.SHOW_SEARCH("block");
    }
  }
};
</script>

<style scoped>

</style>
