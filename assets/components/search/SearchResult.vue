<template>
  <section class="categories categories-grid spad">
    <div class="categories__post">
      <div class="container">
        <div class="categories__grid__post">
          <div class="row">
            <div class="col-lg-8 col-md-8">
              <div class="breadcrumb__text">
                <h2>Search result for: <span>{{ this.$route.query.s }}</span></h2>
              </div>
              <div
                v-for="(blog, index) in listItems"
                :key="index"
                class="categories__list__post__item"
              >
                <div class="row">
                  <div class="col-lg-6 col-md-6">
                    <div
                      class="categories__post__item__pic set-bg"
                      :style="setThumbnail(blog.thumbnail)"
                    >
                      <div class="post__meta">
                        <h4>{{ getDate(blog.created_at.date) }}</h4>
                        <span>{{ getMonth(blog.created_at.date) }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6">
                    <div class="categories__post__item__text">
                      <span class="post__label">Recipe</span>
                      <h3>
                        <a href="#">{{ blog.title }}</a>
                      </h3>
                      <ul class="post__widget">
                        <li>by <span>Admin</span></li>
                        <li>{{ blog.min_read }} min read</li>
                        <li>20 Comment</li>
                      </ul>
                      <p>{{ blog.description }}...</p>
                      <router-link
                        class="primary-btn"
                        :to="{ name: 'PostDetails', params: { id: blog.id } }"
                        >Read more</router-link
                      >
                    </div>
                  </div>
                </div>
                <hr v-if="index != listItems.length - 1" class="type-3" />
              </div>
              <div v-if="listItems.length == 0" class="no-record">
                <span>We are sorry. There is no record found.</span>
              </div>
              <!-- Pagination -->
              <pagination
                v-if="listItems"
                :total-pages="totalPages"
                :per-page="recordsPerPage"
                :current-page="page"
                @pagechanged="onPageChange"
                @gotopage="goToPage"
              ></pagination>
            </div>
            <!-- Sidebar -->
            <sidebar></sidebar>
            <!-- End Sidebar -->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Sidebar from "../sidebar/Sidebar.vue";
import Pagination from "../pagination/Pagination.vue";
import baseApiUrl from "../../config/env";
import axios from "axios";

export default {
  data() {
    return {
      listItems: [],
      page: 1,
      totalPages: 0,
      totalRecords: 0,
      recordsPerPage: 8,
    };
  },
  components: {
    Sidebar,
    Pagination,
  },
  mounted() {
    this.loadListItems();
    this.$watch(
      () => this.$route.params,
      (toParams, previousParams) => {
        //react to router params change
        this.loadListItems();
      }
    )
  },
  methods: {
    setThumbnail(thumbnail) {
      return {
        "background-image":
          "url(/assets/files/blog_thumbnails/" + thumbnail + ")",
      };
    },
    getDate(date) {
      return this.formatDate(date);
    },
    getMonth(month) {
      return this.formatMonth(month);
    },
    loadListItems() {
      const url = "/search";
      axios
        .get(url, {
            params: {
                s: this.$route.query.s,
                page: this.page
            }
        })
        .then((response) => {
          /* console.log(response.data); */
          this.listItems = response.data.currentItems;
          this.totalPages = response.data.totalPages;
        });
    },
    onPageChange(page) {
      this.page = page;
      this.$router.push({
        name: "SearchResult",
        query: { s: this.$route.query.s, page: this.page },
      });
      this.loadListItems();
    },
    goToPage(enterpageno) {
      if (!isNaN(parseInt(enterpageno))) {
        this.$router.push({
          name: 'SearchResult',
          query: { s: this.$route.query.s, page: enterpageno },
        });
        this.page = parseInt(enterpageno);
        this.loadListItems();
      }
    }
  },
};
</script>

<style scoped>
hr.type-3 {
  border: 0;
  height: 25px;
  background-image: url('../../img/hr_style.png');
  background-repeat: no-repeat;
}
.categories__list__post__item {
  margin-bottom: 30px !important;
}

.no-record span {
  background-color: #f4952f;
  color: #fff;
  display: flex;
  justify-content: center;
  padding: 15px;
  font-size: 20px;
}

</style>