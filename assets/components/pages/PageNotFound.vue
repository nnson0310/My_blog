<template>
  <!-- Section 404 Begin -->
  <section class="section-404">
    <div class="text-404">
      <h1>404</h1>
      <h3>Opps! This page Could Not Be Found!</h3>
      <p>
        Sorry bit the page you are looking for does not exist, have been removed
        or name changed
      </p>
      <div class="nav">
        <router-link to="/home">Go back to home page</router-link>
      </div>
    </div>
  </section>
  <!-- Section 404 End -->
</template>

<script>
export default {};
</script>

<style scoped>
.nav {
  width: 100%;
  height: 46px;
  background: #f4952f;
  border: none;
  padding-left: 20px;
  font-size: 15px;
  color: #ffffff;
}
</style>