<template>
  <div>
      <!-- Page Preloder -->
   <!--  <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Hero Section Begin -->
    <hero></hero>
    <!-- Hero Section End -->

    <!-- Categories Section Begin -->
    <category></category>
    <!-- Categories Section End -->
    
  </div>
</template>

<script>
import Hero from '../hero/Hero';
import Category from '../category/Category';

export default {
    data() {
        return {

        }
    },
    components: {
        Hero,
        Category
    }
}
</script>

<style>

</style>
