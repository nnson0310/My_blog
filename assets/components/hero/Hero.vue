<template>
  <section class="hero">
    <div class="hero__slider owl-carousel">
      <div class="hero__item">
        <div class="container-fluid">
          <div class="row">
            <!-- Carousel 3d begin -->
            <carousel-3d
              :autoplay="true"
              :autoplayTimeout="5000"
              :display="5"
              :width="650"
              :height="600"
              :controls-visible="true"
              :controls-width="60"
              :controls-height="100"
              :controls-prev-html="'&#10092;'"
              :controls-next-html="'&#10093;'"
              id="carousel"
            >
              <slide
                v-for="(slider, index) in sliders"
                :key="index"
                :class="['slider-' + index]"
                :index="index"
              >
              </slide>
            </carousel-3d>
            <!-- Carousel 3d end -->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Carousel3d, Slide } from "vue-carousel-3d";

export default {
  data() {
    return {
      blogs: [],
      tags: [],
      sliders: [
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
        {
          date: "08",
          month: "Aug",
          desc: "At vero eos et accusamus et iusto odio dignissimos ducimus molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. ",
        },
      ],
    };
  },
  components: {
    Carousel3d,
    Slide,
  },
  async mounted() {
    this.blogs = this.$store.state.blogs;
  }
};
</script>

<style lang="scss">
.owl-carousel {
  display: inline-block !important;
}

.widget li {
  list-style: none;
  display: inline-block;
  margin-right: 25px;
  font-size: 12px;
  color: #c4c4c4;
  position: relative;
}

//styling for control arrows of sliders (PC, laptop)
@media screen and (min-width: 1025px) {
  .carousel-3d-container {
    .carousel-3d-controls {
      .next {
        right: 50px;
      }
      .prev {
        left: 50px;
      }
    }
  }
}

// styling for control arrows of sliders (mobiles)
@media screen and (max-width: 768px) {
  .carousel-3d-container {
    .carousel-3d-controls {
      .prev {
        left: 0px;
        color: #c8c8c8;
      }
      .next {
        color: #c8c8c8;
      }
    }
  }
}

.carousel-3d-container {
  .slider-0 {
    background-image: url("../../img/hero/hero-1.jpg");
  }
  .slider-1 {
    background-image: url("../../img/hero/hero-2.jpg");
  }
  .slider-2 {
    background-image: url("../../img/hero/hero-3.jpg");
  }
  .slider-3 {
    background-image: url("../../img/hero/hero-4.jpg");
  }
  .slider-4 {
    background-image: url("../../img/hero/hero-4.jpg");
  }
  .slider-5 {
    background-image: url("../../img/hero/hero-2.jpg");
  }
  .slider-6 {
    background-image: url("../../img/hero/hero-3.jpg");
  }
}
</style>
