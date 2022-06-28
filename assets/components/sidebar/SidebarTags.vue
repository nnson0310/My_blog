<template>
  <div class="sidebar__item__categories">
    <div class="widget__title">
      <h6>Tags</h6>
    </div>
    <div class="widget_tag_cloud">
      <a
        @click="loadPostsByTag(tag.name, tag.slug)"
        v-for="(tag, index) in tags"
        :key="index"
        >{{ tag.name }}</a
      >
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  async mounted() {
    this.$store.dispatch("getTags");
  },
  computed: {
    tags() {
      return this.$store.state.tags;
    },
  },
  methods: {
    loadPostsByTag(tagName, tagSlug) {
      this.$store.commit('SET_TAG_NAME', tagName);
      this.$router.push({
        path: `/tag/${tagSlug}`,
        query: { page: 1 },
      });
    },
  },
};
</script>

<style scoped>
.widget_tag_cloud a {
  font-size: 15px !important;
  letter-spacing: 1px;
  line-height: inherit;
  background: #f5f5f5;
  padding: 3px 10px;
  color: #616161;
  display: inline-block;
  margin: 0 6px 6px 0;
  text-decoration: none !important;
  text-rendering: auto;
  transition: all 0.2s linear;
}
</style>