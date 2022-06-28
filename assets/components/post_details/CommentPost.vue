<template>
  <div class="single-post__leave__comment">
    <div class="widget__title">
      <h4>Leave a comment</h4>
    </div>
    <form @submit.prevent="submitForm">
      <slide-up-down :active="active" :duration="1000">
        <div class="input-list">
          <span class="mr-2"
            ><i class="fa fa-user icon" aria-hidden="true"></i
          ></span>
          <div class="form-field">
            <input type="text" placeholder="Name*" v-model.trim="form.name" />
            <Alert class="alert-error" type="error" v-if="v$.name.$error">{{
              v$.name.$errors[0].$message
            }}</Alert>
          </div>
          <span class="mr-2"
            ><i class="fa fa-envelope icon" aria-hidden="true"></i
          ></span>
          <div class="form-field">
            <input
              type="email"
              placeholder="Email*"
              v-model.trim="form.email"
            />
            <Alert class="alert-error" type="error" v-if="v$.email.$error">{{
              v$.email.$errors[0].$message
            }}</Alert>
          </div>
          <span class="mr-2"
            ><i class="fa fa-university icon" aria-hidden="true"></i
          ></span>
          <div class="form-field">
            <input
              type="url"
              placeholder="Website (Optional)"
              v-model.trim="form.website"
            />
          </div>
        </div>
      </slide-up-down>
      <span class="mr-2"
        ><i class="fa fa-comments icon" aria-hidden="true"></i
      ></span>
      <div id="ckeditor">
        <ckeditor
          :editor="editor"
          :config="editorConfig"
          v-model.trim="form.comment"
          @focus="active = true"
          placeholder="Leave a comment*"
        ></ckeditor>
      </div>
      <Alert type="error" v-if="v$.comment.$error">{{
        v$.comment.$errors[0].$message
      }}</Alert>
      <button type="submit" class="site-btn">Submit</button>
    </form>
  </div>
</template>

<script>
import SlideUpDown from "vue-slide-up-down";
import { reactive, computed } from "@vue/composition-api";
import Vuelidate from "@vuelidate/core";
import { ckeditorPlugins } from '../../plugins/constant.js';
import {
  required,
  email,
  helpers,
  url,
  minLength,
} from "@vuelidate/validators";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  data() {
    return {
      active: false,
      config: {
        msg: "",
        title: "",
      },
      editor: ClassicEditor,
      editorConfig: {
        removePlugins: ckeditorPlugins
      }
    };
  },
  components: {
    SlideUpDown,
    ckeditor: CKEditor.component,
  },
  mounted() {
    /* console.log(ClassicEditor.builtinPlugins.map(plugin => plugin.pluginName)); */
  },
  setup() {
    const form = reactive({
      name: "",
      email: "",
      website: "",
      comment: "",
    });

    const rules = computed(() => {
      return {
        name: {
          required: helpers.withMessage("Name can not be null", required),
        },
        email: {
          required: helpers.withMessage("Email can not be null", required),
          email: helpers.withMessage("Email is invalid", email),
        },
        comment: {
          required: helpers.withMessage("Comment can not be null", required),
          minLength: helpers.withMessage(
            "Comment must be at least 5 characters in length",
            minLength
          ),
        },
      };
    });

    const v$ = Vuelidate(rules, form);

    return { form, v$ };
  },
  methods: {
    async submitForm(e) {
      this.v$.$validate();
      if (!this.v$.$error) {
        const url = "comment/post";
        const dataObj = {
          name: this.form.name,
          email: this.form.email,
          website: this.form.website,
          comment: this.form.comment,
          blogId: this.$store.state.blogId
        };
        try {
          const result = await this.postComment(url, JSON.stringify(dataObj));
          this.config.msg = result.data.msg;
          this.config.title = "Success!";
          this.successNotice(this.config);
          this.form.name = '';
          this.form.email = '';
          this.form.website = '';
          this.form.comment = '';
          //set $dirty flag and all its children to false to hide all error validation message
          this.v$.$reset();
          /* console.log(result); */
        } catch (error) {
          this.config.title = "Oops! Something went wrong!";
          this.config.msg = error.response.data.msg;
          this.errorNotice(this.config);
          /* console.log(error); */
        }
      }
    },
  },
};
</script>

<style>
.icon {
  width: 16px;
  display: inline-block;
}

#ckeditor {
  margin-bottom: 10px;
}

.ck-content {
  height: 200px;
}

@media screen and (min-width: 801px) {
  .alert-error {
    width: calc(33.33% - 20px);
  }
}
</style>