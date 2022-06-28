<template>
      <div class="container">
      <div class="row">
        <div v-for="categoryItem, index in categoryItems" :key="index" class="col-lg-3 col-md-6 col-sm-6">
          <div
            class="categories__item set-bg"
            :class="['categoryItem-' + index]"
          >
            <div class="categories__hover__text" @click="showCategoryPosts(categoryItem.slug, categoryItem.id)">
              <h5>{{ categoryItem.name }}</h5>
              <p>{{ categoryItem.posts }} articles</p>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
  computed: {
    categoryItems() {
      return this.$store.state.categoryItems;
    }
  },
  methods: {
    showCategoryPosts(catSlug, catId) {
      this.$store.dispatch('getCategoryId', catId);
      this.$router.push({
        path: `/category/${catSlug}`,
        query: {page: 1},
      });
    }
  },
  async mounted() {
      try {
        await this.$store.dispatch('getCategoryItems');
      } catch (error) {
        console.log(error);
      }
    },
}
</script>

<style scoped>
.categories__hover__text {
  text-align: center !important;
  display: table-row;
  max-width: 160px;
  word-break: break-word;
}

.categories__hover__text:hover {
  cursor: pointer;
}

.categoryItem-0 {
  background-image: url("../../img/categories/cat-1.jpg");
}

.categoryItem-1 {
  background-image: url("../../img/categories/cat-2.jpg");
}

.categoryItem-2 {
  background-image: url("../../img/categories/cat-3.jpg");
}

.categoryItem-3 {
  background-image: url("../../img/categories/cat-4.jpg");
}
</style>
