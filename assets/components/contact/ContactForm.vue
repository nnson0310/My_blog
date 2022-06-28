<template>
  <div class="col-lg-6 col-md-6">
    <div class="contact__form">
      <div class="contact__form__title">
        <h2>Get In Touch</h2>
        <p>
          My experience with Realy is absolutely positive. The themes are
          beautifully designed and well documented. Realy theme provides quick
          support.
        </p>
      </div>
      <form @submit.prevent="submitForm">
        <input
          v-model.trim="form.firstName"
          type="text"
          placeholder="First Name"
          @dblclick="form.firstName = ''"
        />
        <Alert type="error" v-if="v$.firstName.$error">{{
          v$.firstName.$errors[0].$message
        }}</Alert>
        <input
          v-model="form.lastName"
          type="text"
          placeholder="Last Name"
          @dblclick="form.lastName = ''"
        />
        <Alert type="error" v-if="v$.lastName.$error">{{
          v$.lastName.$errors[0].$message
        }}</Alert>
        <input
          v-model="form.emailAddress"
          type="email"
          placeholder="Email"
          @dblclick="form.emailAddress = ''"
        />
        <Alert type="error" v-if="v$.emailAddress.$error">{{
          v$.emailAddress.$errors[0].$message
        }}</Alert>
        <input
          v-model="form.subject"
          type="text"
          placeholder="Subject"
          @dblclick="form.subject = ''"
        />
        <Alert type="error" v-if="v$.subject.$error">{{
          v$.subject.$errors[0].$message
        }}</Alert>
        <textarea
          v-model="form.content"
          placeholder="Message"
          @dblclick="form.content = ''"
        ></textarea>
        <Alert type="error" v-if="v$.content.$error">{{
          v$.content.$errors[0].$message
        }}</Alert>
        <button type="submit" class="site-btn">Submit</button>
      </form>
    </div>
  </div>
</template>

<script>
import { reactive, computed } from "@vue/composition-api";
import Vuelidate from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";

export default {
  data() {
    return {
      config: {
        status: false,
        title: "",
        msg: "",
        duration: 6,
      },
    };
  },
  setup() {
    const form = reactive({
      firstName: "",
      lastName: "",
      emailAddress: "",
      subject: "",
      content: "",
    });

    const rules = computed(() => {
      return {
        firstName: {
          required: helpers.withMessage(
            "First name can not be null.",
            required
          ),
        },
        lastName: {
          required: helpers.withMessage("Last name can not be null.", required),
        },
        emailAddress: {
          required: helpers.withMessage("Email can not be null.", required),
          email: helpers.withMessage("Email is invalid.", email),
        },
        subject: {
          required: helpers.withMessage("Subject can not be null.", required),
        },
        content: {
          required: helpers.withMessage("Content can not be null.", required),
        },
      };
    });

    const v$ = Vuelidate(rules, form);

    return { form, v$ };
  },
  methods: {
    async submitForm() {
      this.v$.$validate();
      if (!this.v$.$error) {
        const url = "api/message/send";
        const dataObj = {
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          emailAddress: this.form.emailAddress,
          subject: this.form.subject,
          content: this.form.content,
        };
        /* console.log(dataObj); */
        try {
          const res = await this.sendMessage(url, JSON.stringify(dataObj));
          /* console.log(res); */
          this.config.msg = res.data.msg;
          this.config.title = "Submit form successfully!";
          this.successNotice(this.config);
        } catch (error) {
          /* console.log(error.response); */
          this.config.msg = error.response.data.msg;
          this.config.title = "Oops! Something went wrong.";
          this.errorNotice(this.config);
        }
      } else {
        this.config.msg = "Maybe you have input some invalid data.";
        this.config.title = "Oops! Something went wrong.";
        this.errorNotice(this.config);
      }
    },
  },
};
</script>

<style scoped>
.ivu-alert {
  margin-top: -10px;
  color: #e46045;
}
</style>