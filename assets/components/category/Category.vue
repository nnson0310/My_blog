<template>
  <section class="categories spad">
    <!-- Category Item -->
    <category-item></category-item>
    <!-- End Category Item -->

    <!-- Category Post -->
    <category-post></category-post>
    <!-- End Category Post -->

  </section>
</template>

<script>
import CategoryItem from './CategoryItem.vue';
import CategoryPost from './CategoryPost.vue';

export default {
  data() {
    return {

    }
  },
  components: {
    CategoryItem,
    CategoryPost
  },
};
</script>

<style scoped>
</style>
