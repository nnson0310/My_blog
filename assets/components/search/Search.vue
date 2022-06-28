<template>
  <div class="search-model" :style="{ display: showSearch }">
    <div class="h-100 d-flex align-items-center justify-content-center">
      <div class="search-close-switch" @click="hideSearch">✕</div>
      <form @submit.prevent="submitForm" class="search-model-form">
        <input
          v-model="keyword"
          type="text"
          id="search-input"
          placeholder="Enter your keywords here....."
        />
      </form>
    </div>
  </div>
</template>

<script>
import { mapMutations } from "vuex";
import axios from "axios";

export default {
  data() {
    return {
      keyword: "",
      config: {
        msg: "",
        title: "",
      },
    };
  },
  computed: {
    showSearch() {
      return this.$store.state.showSearch;
    },
  },
  methods: {
    ...mapMutations(["SHOW_SEARCH"]),
    hideSearch() {
      this.SHOW_SEARCH("none");
    },
    async submitForm() {
      this.hideSearch();
      this.$router.push({
        name: "SearchResult",
        query: {
          s: this.keyword,
          page: 1
        },
      }).catch(()=>{});
    },
  },
};
</script>

<style scoped>
.search-close-switch {
  transform: rotate(0deg) !important;
  -webkit-transform: rotate(0deg) !important;
  font-size: 18px;
}
</style>