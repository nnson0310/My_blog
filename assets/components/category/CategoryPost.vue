<template>
  <div class="categories__post">
    <div class="container">
      <div class="row">
        <category-post-item></category-post-item>
        <sidebar></sidebar>
      </div>
    </div>
  </div>
</template>

<script>
import CategoryPostItem from './CategoryPostItem.vue';
import Sidebar from '../sidebar/Sidebar.vue';

export default {
  data() {
    return {
      styles: {
        "background-color": "red",
      },
    };
  },
  components: {
    CategoryPostItem,
    Sidebar
  }
};
</script>

<style scoped>
</style>
