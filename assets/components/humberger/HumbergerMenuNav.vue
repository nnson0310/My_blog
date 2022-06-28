<template>
  <nav class="humberger__menu__nav mobile-menu">
    <ul>
      <li><a @click="navigateTo('/home')">Home</a></li>
      <li v-for="category, index in categories" :key="index" class="dropdown">
        <a href="#"
          >{{ category.name }}
          <span @click="active = true" v-if="!active" class="slicknav_arrow"
            >►</span
          >
          <span @click="active = false" v-if="active" class="slicknav_arrow"
            >▼</span
          >
        </a>
        <slide-up-down :active="active" :duration="500">
          <ul class="dropdown__menu">
            <li><a href="./categories-grid.html">Categories Grid</a></li>
            <li><a href="./categories-list.html">Categories List</a></li>
          </ul>
        </slide-up-down>
      </li>
      <li><a @click="navigateTo('/about_me')">About</a></li>
      <li><a @click="navigateTo('/contact')">Contact</a></li>
    </ul>
  </nav>
</template>

<script>
import { baseDomain } from "../../plugins/constant";
import { mapMutations } from "vuex";
import SlideUpDown from "vue-slide-up-down";

export default {
  data() {
    return {
      humbergerClass: "",
      overlayClass: "",
      active: false
    };
  },
  components: {
    SlideUpDown,
  },
  methods: {
    ...mapMutations(["TOGGLE_HUMBERGER", "TOGGLE_OVERLAY"]),
    navigateTo(url) {
      const path = baseDomain + "/#" + url;
      if (this.$route.path != url) {
        this.TOGGLE_HUMBERGER(this.humbergerClass);
        this.TOGGLE_OVERLAY(this.overlayClass);
        this.$router.push({
          path: url,
        });
      }
    }
  },
  computed: {
    categories() {
      return this.$store.state.categoryItems;
    }
  }
};
</script>

<style scoped>
.humberger__menu__nav {
  display: block;
  margin-bottom: 35px;
}

.humberger__menu__nav.mobile-menu ul {
  list-style-type: none;
}

.humberger__menu__nav.mobile-menu ul li {
  margin: 10px 0px;
}

.humberger__menu__nav.mobile-menu ul li a {
  font-size: 15px;
  color: #ffffff;
  font-family: "Unna", serif;
  text-transform: uppercase;
  padding: 10px 0px;
}

.humberger__menu__nav.mobile-menu ul li a:hover {
  background: transparent;
  color: #f4952f;
  text-decoration: underline;
}

.slicknav_arrow {
  font-size: 0.8em;
  margin: 0 0 0 0.4em;
}
</style>