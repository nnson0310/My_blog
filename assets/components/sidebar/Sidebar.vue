<template>
  <div class="col-lg-4 col-md-4">
    <div class="sidebar__item">
      <sidebar-about></sidebar-about>
      <sidebar-follow></sidebar-follow>
      <sidebar-feature></sidebar-feature>
      <sidebar-banner></sidebar-banner>
      <sidebar-category></sidebar-category>
      <sidebar-subscribe></sidebar-subscribe>
      <sidebar-tags></sidebar-tags>
      <sidebar-calendar></sidebar-calendar>
    </div>
  </div>
</template>

<script>
import SidebarAbout from './SidebarAbout.vue';
import SidebarFeature from './SidebarFeature.vue';
import SidebarFollow from './SidebarFollow.vue';
import SidebarBanner from './SidebarBanner.vue';
import SidebarCategory from './SidebarCategory.vue';
import SidebarSubscribe from './SidebarSubscribe.vue';
import SidebarTags from './SidebarTags.vue';
import SidebarCalendar from './SidebarCalendar.vue';

export default {
  data() {
    return {

    }
  },
  components: {
    SidebarAbout,
    SidebarFollow,
    SidebarFeature,
    SidebarBanner,
    SidebarCategory,
    SidebarSubscribe,
    SidebarTags,
    SidebarCalendar
  }
};
</script>

<style scoped>
</style>
