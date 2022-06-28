<template>
       <div class="sidebar__feature__item">
        <div class="widget__title">
          <h6>Feature Posts</h6>
        </div>
        <div
          class="sidebar__feature__item__large set-bg"
          data-setbg="img/sidebar/feature-post.jpg"
          :style="styles"
        >
          <div class="sidebar__feature__item__large--text">
            <span>Dinner</span>
            <h5>
              <a href="#"
                >This Japanese Way of Making Iced Coffee Is a Game...</a
              >
            </h5>
          </div>
        </div>
        <div class="sidebar__feature__item__list">
          <div class="sidebar__feature__item__list__single">
            <div class="post__meta">
              <h4>08</h4>
              <span>Aug</span>
            </div>
            <div class="post__text">
              <span>Dinner</span>
              <h5>
                <a href="#">Grilled Potato and Green Bean Salad</a>
              </h5>
            </div>
          </div>
          <div class="sidebar__feature__item__list__single">
            <div class="post__meta">
              <h4>05</h4>
              <span>Aug</span>
            </div>
            <div class="post__text">
              <span>Smoothie</span>
              <h5>
                <a href="#">The $8 French Rosé I Buy in Bulk Every Summer</a>
              </h5>
            </div>
          </div>
          <div class="sidebar__feature__item__list__single">
            <div class="post__meta">
              <h4>26</h4>
              <span>jul</span>
            </div>
            <div class="post__text">
              <span>Desert</span>
              <h5>
                <a href="#">Ina Garten's Skillet-Roasted Lemon Chicken</a>
              </h5>
            </div>
          </div>
          <div class="sidebar__feature__item__list__single">
            <div class="post__meta">
              <h4>16</h4>
              <span>jul</span>
            </div>
            <div class="post__text">
              <span>Vegan</span>
              <h5>
                <a href="#"
                  >The Best Weeknight Baked Potatoes, 3 Creative Ways</a
                >
              </h5>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
export default {
  data() {
    return {
      styles: {
        'background-color': 'red'
      }
    }
  },
  computed: {
    featurePosts() {
      return this.$store.state.featurePosts;
    }
  }
}
</script>

<style scoped>

</style>
