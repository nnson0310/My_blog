<template>
  <div class="col-lg-8 col-md-8">
    <div
      class="categories__post__item categories__post__item--large"
      v-if="blogs.length"
    >
      <div
        class="categories__post__item__pic set-bg"
        :style="setThumbnail(blogs[0].thumbnail)"
      >
        <div class="post__meta">
          <h4>{{ getDate(blogs[0].created_at.date) }}</h4>
          <span>{{ getMonth(blogs[0].created_at.date) }}</span>
        </div>
      </div>
      <div class="categories__post__item__text">
        <ul class="post__label--large">
          <li v-for="(tag, index) in tags[blogs[0].id]" :key="index">
            {{ tag }}
          </li>
        </ul>
        <h3>
          <a href="#">{{ blogs[0].title }}</a>
        </h3>
        <ul class="post__widget">
          <li>{{ blogs[0].min_read }} min read</li>
          <li>20 Comment</li>
        </ul>
        <p v-html="blogs[0].description"></p>
        <a href="#" class="primary-btn">Read more</a>
        <social-sharing
          :url="domain + '' + blogs[0].slug"
          :title="blogs[0].title"
          :description="blogs[0].description"
          :quote="quote"
          :hashtags="tags[blogs[0].id].join()"
        ></social-sharing>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6">
        <div
          class="categories__post__item"
          v-for="(blog, index) in oddIndexBlog"
          :key="index"
        >
          <div
            class="categories__post__item__pic set-bg"
            :class="
              index == 0
                ? 'small__item'
                : index % 2 == 0
                ? ''
                : 'smaller__large'
            "
            :style="setThumbnail(blog.thumbnail)"
          >
            <div class="post__meta">
              <h4>{{ getDate(blog.created_at.date) }}</h4>
              <span>{{ getMonth(blog.created_at.date) }}</span>
            </div>
          </div>
          <div class="categories__post__item__text">
            <ul class="post__label--large">
              <li v-for="(tag, index) in tags[blog.id]" :key="index">
                {{ tag }}
              </li>
            </ul>
            <h3>
              <a href="#">{{ blog.title }}</a>
            </h3>
            <ul class="post__widget">
              <li>
                by
                <!-- <span>{{ blog.createdBy.username }}</span> -->
              </li>
              <li>{{ blog.min_read }} min read</li>
              <li>20 Comment</li>
            </ul>
            <p v-html="blog.description">
              <!-- Blog's short description -->
            </p>
            <a @click="postDetails(blog.slug, blog.id)" class="primary-btn"
              >Read more</a
            >
            <social-sharing
              :url="domain + '' + blog.slug"
              :title="blog.title"
              :description="blog.description"
              :quote="quote"
              :hashtags="tags[blog.id].join()"
            ></social-sharing>
          </div>
        </div>
        <div class="categories__post__item__small">
          <img src="img/categories/categories-post/quote.png" alt="" />
          <p>
            Lorem ipsum dolor amet, consectetur adipiscing elit, sed do eiusmod
            tempor incididunt labore et dolore magna aliqua gravida.
          </p>
          <div class="posted__by">Elena T.Jaivy</div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
        <div
          class="categories__post__item"
          v-for="(blog, index) in evenIndexBlog"
          :key="index"
        >
          <div
            class="categories__post__item__pic set-bg"
            :class="index % 2 != 0 ? '' : 'smaller__large'"
            :style="setThumbnail(blog.thumbnail)"
          >
            <div class="post__meta">
              <h4>{{ getDate(blog.created_at.date) }}</h4>
              <span>{{ getMonth(blog.created_at.date) }}</span>
            </div>
          </div>
          <div class="categories__post__item__text">
            <span class="post__label--large">
              <ul>
                <li v-for="(tag, index) in tags[blog.id]" :key="index">
                  {{ tag }}
                </li>
              </ul>
            </span>
            <h3>
              <a href="#">{{ blog.title }}</a>
            </h3>
            <ul class="post__widget">
              <li>by <span>Admin</span></li>
              <li>{{ blog.min_read }} min read</li>
              <li>20 Comment</li>
            </ul>
            <p>
              {{ blog.description }}
            </p>
            <a @click="postDetails(blog.slug, blog.id)" class="primary-btn"
              >Read more</a
            >
            <social-sharing
              :url="domain + '' + blog.slug"
              :title="blog.title"
              :description="blog.description"
              :quote="quote"
              :hashtags="tags[blog.id].join()"
            ></social-sharing>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 text-center">
      <div class="load__more__btn">
        <a @click="loadMorePosts">Load more</a>
      </div>
    </div>
  </div>
</template>

<script>
import SocialSharing from "../social_sharing/SocialSharing.vue";
import { facebookQuote, baseDomain } from '../../plugins/constant';

export default {
  data() {
    return {
      blogs: [],
      tags: [],
      oddIndexBlog: [],
      evenIndexBlog: [],
      quote: facebookQuote,
      domain: baseDomain
    };
  },
  props: {

  },
  components: {
    SocialSharing,
  },
  async mounted() {
    await this.fetchData();
  },
  methods: {
    getDate(date) {
      return this.formatDate(date);
    },
    getMonth(month) {
      return this.formatMonth(month);
    },
    setThumbnail(thumbnail) {
      return {
        "background-image":
          "url(/assets/files/blog_thumbnails/" + thumbnail + ")",
      };
    },
    postDetails(slug, id) {
      this.$store.dispatch("getBlogId", id);
      this.$router.push({ name: "PostDetails", params: { slug: slug } });
    },
    loadMorePosts() {
      this.$router.push({ path: "/posts/load_more", query: { page: 1 } });
    },
    async fetchData() {
      try {
        const url = "/blog/latest";
        const result = await this.getLatestBlogs(url);
        this.blogs = result.data.blogs;
        this.tags = result.data.tags;
        /* filter blog to indentify size */
        this.oddIndexBlog = this.blogs.filter((blog, index) => index % 2 === 0);
        this.evenIndexBlog = this.blogs.filter(
          (blog, index) => index % 2 !== 0
        );
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style scoped>
.categories__post__item__pic {
  border-radius: 5px;
}
</style>
