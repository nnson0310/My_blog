<template>
  <!-- Contact Section Begin -->
  <section class="contact spad">
    <div class="container">
      <div class="contact__text">
        <div class="row">
          <div class="col-lg-12">
            <div class="breadcrumb__text">
              <h2>Contact</h2>
              <div class="breadcrumb__option">
                <router-link to="/home">Home</router-link>
                <span>Contact</span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 col-md-6">
            <div class="contact__map">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29793.980384380367!2d105.81945410681621!3d21.022778763204165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2z44OZ44OI44OK44OgIOODj-ODjuOCpCDjg5vjgqLjg7Pjgq3jgqjjg6DljLog44OP44OO44Kk!5e0!3m2!1sja!2s!4v1638505439727!5m2!1sja!2s"
                height="350"
                style="border: 0"
                allowfullscreen=""
                aria-hidden="false"
                tabindex="0"
                loading="lazy"
              ></iframe>
            </div>
            <div class="contact__widget">
              <ul>
                <li>
                  <i class="fa fa-map-marker"></i>
                  <span>Ha Noi, Viet Nam</span>
                </li>
                <!-- <li>
                  <i class="fa fa-mobile"></i>
                  <span>Phone: 258-556-189</span>
                </li> -->
                <li>
                  <i class="fa fa-envelope-o"></i>
                  <span>Email: life_decryption@gmail.com</span>
                </li>
              </ul>
            </div>
          </div>
          <contact-form></contact-form>
        </div>
      </div>
    </div>
  </section>
  <!-- Contact Section End -->
</template>

<script>
import ContactForm from '../contact/ContactForm.vue';

export default {
  data() {
    return {
      
    }
  },
  components: {
    ContactForm
  }
};
</script>

<style scoped>
</style>