<template>
  <div class="humberger__menu__subscribe">
    <div class="humberger__menu__title sidebar__item__title">
      <h6>Subscribe</h6>
    </div>
    <p>
      Subscribe to our newsletter and get our newest updates right on your
      inbox.
    </p>
    <form @submit.prevent="submitForm">
      <input
        v-model="state.email"
        @dblclick="state.email = ''"
        type="email"
        class="email-input"
        placeholder="Your email"
      />
      <label for="agree-check">
        I agree to the terms & conditions
        <input v-model="agreement" type="checkbox" id="agree-check" />
        <span class="checkmark"></span>
      </label>
      <button type="submit" class="site-btn">Subscribe</button>
    </form>
  </div>
</template>

<script>
import { reactive, computed } from "@vue/composition-api";
import { useVuelidate } from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";

export default {
  data() {
    return {
      agreement: "",
      config: {
        status: false,
        title: "Oops! Something went wrong!",
        msg: "",
        duration: 6,
      },
    };
  },
  setup() {
    const state = reactive({
      email: "",
    });

    const rules = computed(() => ({
      email: {
        required: helpers.withMessage("Email is required", required),
        email: helpers.withMessage("Email is invalid", email),
      },
    }));

    const v$ = useVuelidate(rules, state);

    return { state, v$ };
  },
  methods: {
    async submitForm() {
      if (this.agreement == "" || this.agreement == null) {
        this.config.msg = "You have to agree with our terms and conditions.";
        this.errorNotice(this.config);
      } else {
        this.v$.$validate();
        if (!this.v$.$error) {
          try {
            const url = "subscribe";
            const dataObj = {
              email: this.state.email,
            };
            const result = await this.subscribe(url, dataObj);
            /* console.log(result.data); */
            if (result.data.status == false) {
              this.config.title = "Oh!";
              this.config.msg = result.data.msg;
              this.infoNotice(this.config);
            } else {
              this.config.title = "Thank you!";
              this.config.msg = result.data.msg;
              this.successNotice(this.config);
            }
          } catch (error) {
            console.log(error);
            this.config.msg = error.response.data.msg;
            this.errorNotice(this.config);
          }
        } else if (this.v$.email.$error) {
          this.config.msg = this.v$.email.$errors[0].$message;
          this.errorNotice(this.config);
        }
      }
    },
  },
};
</script>

<style scoped>
</style>
