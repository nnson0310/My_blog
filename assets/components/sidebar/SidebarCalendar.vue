<template>
  <div>
    <div class="widget__title">
      <h6>Calendar</h6>
    </div>
    <FunctionalCalendar></FunctionalCalendar>
  </div>
</template>

<script>
import { FunctionalCalendar } from "vue-functional-calendar";

export default {
  data() {
    return {};
  },
  components: {
    FunctionalCalendar,
  },
};
</script>

<style scoped>
</style>