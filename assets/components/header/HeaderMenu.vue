<template>
  <div class="col-lg-8 col-md-10 order-md-2 order-3 header-menu">
    <nav class="header__menu">
      <ul>
        <li class="active"><router-link to="/home">Home</router-link></li>
        <li>
          <a href="#">Library</a>
          <div class="header__megamenu__wrapper">
            <div class="header__megamenu">
              <div class="header__megamenu__item">
                <div
                  class="header__megamenu__item--pic set-bg"
                  data-setbg="img/megamenu/p-5.jpg"
                >
                  <div class="label">Vegan</div>
                </div>
                <div class="header__megamenu__item--text">
                  <h5>
                    <a href="#"
                      >How to Make a Milkshake With Any Ice Cream ...</a
                    >
                  </h5>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li><a href="#">Podcast</a></li>
        <li><a href="#">Blog</a></li>
        <li class="dropdown">
          <a href="#">Pages</a>
          <ul class="dropdown__menu">
            <li>
              <a href="#">Categories Grid</a>
            </li>
            <li>
              <a href="#">Categories List</a>
            </li>
            <li><a href="#">Single Post</a></li>
            <li><a href="#">Sign In</a></li>
            <li><a href="#">404</a></li>
            <li><a href="#">Typography</a></li>
          </ul>
        </li>
        <li><router-link to="/about_me">About Me</router-link></li>
        <li><router-link to="/contact">Contact</router-link></li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
@media screen and (min-width: 1000px) {
  .header-menu {
    height: 42px;
  }
}
</style>
