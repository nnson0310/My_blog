<template>
  <div class="sidebar__item__categories">
    <div class="widget__title">
      <h6>Topics</h6>
    </div>
    <ul>
      <li v-for="(topic, index) in topics" :key="index">
        <a href="#">{{ topic.name }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
    data() {
        return {
            
        }
    },
    computed: {
      topics() {
        return this.$store.state.topics;
      }
    },
    async mounted() {
      try {
        await this.$store.dispatch('getTopics');
      } catch (error) {
        console.log(error);
      }
    },
};
</script>

<style scoped>
</style>