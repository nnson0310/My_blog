<template>
  <!-- Single Post Section Begin -->
  <section class="single-post spad">
    <bread-cumb-area></bread-cumb-area>
    <div class="container-fluid">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
          <nav class="nav-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/home"><i class="fa fa-home" aria-hidden="true"></i> Home</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Single Post Blog
              </li>
            </ol>
          </nav>
          <div class="single-post__title">
            <div class="single-post__title__meta">
              <h2>{{ getDate(this.blog.createdAt.date) }}</h2>
              <span>{{ getMonth(this.blog.createdAt.date) }}</span>
            </div>
            <div class="single-post__title__text">
              <ul class="label">
                <li v-for="(tag, index) in blog.tags" :key="index">
                  {{ tag }}
                </li>
              </ul>
              <h2 id="title">
                {{ this.blog.title }}
              </h2>
              <ul class="widget">
                <li>by Admin</li>
                <li>{{ this.blog.minRead }} min read</li>
                <li>20 Comment</li>
              </ul>
            </div>
          </div>
          <div class="single-post__social__item">
            <ul>
              <li>
                <a href="#"><i class="fa fa-facebook"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-twitter"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-instagram"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-youtube-play"></i></a>
              </li>
            </ul>
          </div>
          <div class="single-post__recipe__details">
            <div class="single-post__recipe__details__option text-center">
              <ul>
                <li>
                  <h5><i class="fa fa-user-o"></i> SERVES</h5>
                  <span>2 as a main, 4 as a side</span>
                </li>
                <li>
                  <h5><i class="fa fa-clock-o"></i> PREP TIME</h5>
                  <span>15 minute</span>
                </li>
                <li>
                  <h5><i class="fa fa-clock-o"></i> Cook TIME</h5>
                  <span>15 minute</span>
                </li>
                <li>
                  <a href="#" class="primary-btn"
                    ><i class="fa fa-print"></i> Read more</a
                  >
                </li>
              </ul>
            </div>
            <div
              class="single-post__recipe__details__indegradients"
              v-html="this.blog.content"
            >
              <!-- Content -->
            </div>
          </div>
          <div class="single-post__tags">
            <a href="#" v-for="(tag, index) in blog.tags" :key="index">{{
              tag
            }}</a>
          </div>
          <!-- Author Profile -->
          <author-profile></author-profile>
          <!-- Comment -->
          <comment></comment>
          <!-- Leave comment -->
          <comment-post></comment-post>
        </div>
      </div>
    </div>
  </section>
  <!-- Single Post Section End -->
</template>

<script>
import BreadCumbArea from "../post_details/BreadCumbArea.vue";
import AuthorProfile from "../post_details/AuthorProfile.vue";
import Comment from "../post_details/Comment.vue";
import CommentPost from "../post_details/CommentPost.vue";

export default {
  data() {
    return {
      styles: {
        "background-color": "red",
      },
      blog: {
        title: "",
        content: "",
        tags: [],
        minRead: "",
        createdAt: "",
      },
    };
  },
  components: {
    AuthorProfile,
    Comment,
    CommentPost,
    BreadCumbArea,
  },
  async mounted() {
    await this.fetchBlogDetails();
  },
  methods: {
    async fetchBlogDetails() {
      try {
        const url = "/blog/details/" + this.$store.state.blogId;
        const result = await this.getBlogDetails(url);
        this.blog = result.data;
      } catch (error) {
        console.log(error);
      }
    },
    getDate(date) {
      return this.formatDate(date);
    },
    getMonth(month) {
      return this.formatMonth(month);
    },
  },
};
</script>

<style scoped>
#title {
  font-size: 32px;
  line-height: 150%;
  font-family: "Merriweather", serif;
  font-weight: bold;
  margin-bottom: 15px;
}

.nav-breadcrumb {
  margin-bottom: 120px;
}

.breadcrumb {
  padding-left: 0px;
  background-color: transparent;
}

.breadcumb-area {
  width: 100%;
  height: 200px;
  background-position: center center;
  background-size: cover;
  position: relative;
  z-index: 1;
}

.breadcumb-area:after {
  width: 100%;
  height: 100%;
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  background-color: rgba(35, 45, 55, 0.7);
  z-index: -1;
}

.breadcumb-area .bradcumb-title h2 {
  font-size: 48px;
  color: #fff;
}

.breadcumb-nav .breadcrumb {
  padding: 0;
  margin-bottom: 0;
  list-style: none;
  background-color: transparent;
  border-radius: 0.25rem;
  margin-top: 30px;
}

.breadcrumb .breadcrumb-item a {
  color: #232d37;
  font-size: 16px;
  text-decoration: none;
}

.breadcrumb-item + .breadcrumb-item:before {
  content: "\f105";
  font-family: "FontAwesome";
}

.breadcumb-area {
  margin-bottom: 50px;
}
</style>