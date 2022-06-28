<template>
  <div class="single-post__comment">
    <div class="widget__title">
      <h4 v-if="comments">{{ comments.length }} Comments</h4>
      <h4 v-if="noComment">0 Comment</h4>
    </div>
    <div v-if="noComment"><h4>{{ noComment }}</h4></div>
    <div v-for="comment, index in comments" :key ="index" class="single-post__comment__item">
      <div class="single-post__comment__item__pic">
        <img class="thumbnail" src="/assets/images/user-thumbnail/thumbnail-1.svg" alt="" />
      </div>
      <div class="single-post__comment__item__text">
        <h5>{{ comment.name }}</h5>
        <span>{{ getTime(comment.updated_at.date) }}</span>
        <p v-html="comment.content">
        </p>
        <ul>
          <li>
            <a href="#"><i class="fa fa-heart-o"></i></a>
          </li>
          <li>
            <a href="#"><i class="fa fa-share-square-o"></i></a>
          </li>
          <li>
            <a href="#"><i class="fa fa-edit"></i></a>
          </li>
        </ul>
      </div>
    </div>
    <div class="single-post__comment__item single-post__comment__item--reply">
      <div class="single-post__comment__item__pic">
        <img src="img/categories/single-post/comment/comment-2.jpg" alt="" />
      </div>
      <div class="single-post__comment__item__text">
        <h5>Brandon Kelley</h5>
        <span>15 Aug 2017</span>
        <p>
          Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet,
          consectetur, adipisci velit, sed quia non numquam eius modi tempora
          incidunt ut labore et dolore magnam.
        </p>
        <ul>
          <li>
            <a href="#"><i class="fa fa-heart-o"></i></a>
          </li>
          <li>
            <a href="#"><i class="fa fa-share-square-o"></i></a>
          </li>
          <li>
            <a href="#"><i class="fa fa-edit"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      
    }
  },
  async mounted() {
    try {
      await this.$store.dispatch('getComments', this.$store.state.blogId);
    } catch (error) {
      console.log(error);
    }
  },
  computed: {
    comments() {
      return this.$store.state.comments.comment;
    },
    noComment() {
      return this.$store.state.comments.noComment;
    }
  },
  methods: {
    getTime(time) {
      return this.formatTime(time);
    }
  }
};
</script>

<style scoped>
.thumbnail {
  border: 1px solid #6b9bd2;
  padding: 10px;
}
</style>