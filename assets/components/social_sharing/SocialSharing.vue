<template>
  <div class="post__social">
    <span>Share</span>
    <ul>
      <li v-for="(social, index) in socialGroup" :key="index">
        <ShareNetwork
          :network="social.name"
          :url="url"
          :title="title"
          :description="description"
          :quote="quote"
          :hashtags="hashtags"
        >
          <i class="fa" :class="'fa-' + social.icon"></i>
        </ShareNetwork>
      </li>
    </ul>
  </div>
</template>

<script>
import { baseDomain, social } from '../../plugins/constant';

export default {
    data() {
        return {
            socialGroup: social
        }
    },
    props: {
        url: {
            type: String,
            required: false,
            default: baseDomain
        },
        title: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: false
        },
        quote: {
            type: String,
            required: false
        },
        hashtags: {
            type: String,
            required: false
        }
    }
};
</script>

<style scoped>
.post__social {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 25px;
}
.post__social span {
  font-size: 14px;
  color: #888888;
  font-weight: 600;
  text-transform: uppercase;
  margin-right: 25px;
}
.post__social ul {
  list-style-type: none;
  display: flex;
  margin-bottom: 0px;
}
.post__social ul li {
  padding-right: 25px;
}
.post__social a i {
  color: #0056b3 !important;
}
@media only screen and (max-width: 768px) {
  .post__social ul li {
    padding-right: 10px;
  }
}
</style>