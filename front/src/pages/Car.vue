<template>
  <div>
    <h1>Car id {{ id }}</h1>

    <button class="btn btn-sm btn-default mb-2" @click="goBackToCars">Back</button>
    <br>
    <router-link
      class="btn btn-info mt-2"
      tag="button"
      :to="{name: 'carFull', params: {id: id}, query: {name: carName, year: carYear}}"
    >                       <!-- передача query-параметров через адресную строку -->
      Full info
    </router-link>
    <hr>

    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        carName: '',
        carYear: null,
        id: this.$route.params['id']
        // resource: null
      }
    },
    methods: {
      goBackToCars () {
        this.$router.push('/cars')
      }
    },
    watch: {
      $route (toR, fromR) {
        // this.id = toR.params['id']
      }
    },
    created () {
      this.$resource('car/' + this.id).get()
        .then(response => {
            return response.json()
        })
        .then(car => {
            this.carName = car.name
            this.carYear = car.year
        })
    }
  }
</script>

<style scoped>

</style>
