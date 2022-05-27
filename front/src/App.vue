<template>
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav">
            <router-link tag="li" class="nav-item" exact to="/" active-class="active">
              <a class="nav-link">Home</a>
            </router-link>
            <router-link tag="li" class="nav-item" to="/cars" active-class="active">
              <a class="nav-link">Cars</a>
            </router-link>
          </ul>
        </div>
      </nav>

      <router-view></router-view>
    </div>
</template>

<script>
  export default {}
</script>
