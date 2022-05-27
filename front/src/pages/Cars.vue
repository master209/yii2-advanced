<template>
  <div class="container pt-2">

  <h1>Cars page</h1>

  <div class="form-group">
    <label for="name">Car name</label>
    <input type="text" id="name" class="form-control" v-model.trim="carName">
  </div>

  <div class="form-group">
    <label for="year">Car year</label>
    <input type="text" id="year" class="form-control" v-model.number="carYear">
  </div>

  <button class="btn btn-success" @click="createCar">Create car</button>
  <button class="btn btn-primary" @click="loadCars">Load cars</button>

  <hr>

  <h2>Cars list</h2>

  <input type="text" placeholder="search..." v-model="searchName">

  <table class="table table-striped">
  <router-link
    tag="tr"
    v-for="car of filteredNames"
    :to="'/car/' + car.id"
    :key="car.id"
  >
    <td><a><strong>{{ car.name }}</strong> - {{ car.year }}</a></td>
    <td><button class="btn btn-danger" @click="deleteCar(car.id)">Delete</button></td>
  </router-link>
  </table>

</div>
</template>


<script>

  import {eventEmitter} from '../main'

  export default {
    data () {
      return {
        carName: '',
        carYear: 2018,
        cars: [],
        resource: null,
        searchName: '',
      }
    },
    computed: {
        filteredNames() {
            return this.cars.filter(car => {  //вызывается для каждого эл-та массива this.cars
                console.log(car.name);
                return car.name.toLowerCase().indexOf(this.searchName.toLowerCase()) !== -1
            })
        }
    },
    methods: {
      createCar () {
        const car = {
          name: this.carName,
          year: this.carYear
        };

        this.resource('cars').save({}, car)
        .then(response => {
            console.log('from createCar, response: ', response);  //ВЫВОДИТСЯ В КОНСОЛЬ БРАУЗЕРА
            eventEmitter.$emit('needReloadCars', 'createCar');
          })
      },
      loadCars () {
        this.resource('cars').get()
          .then(response => {
            console.log('from loadCars, response: ', response.body);
            this.cars = response.body
          })
      },
      deleteCar (id) {
        this.resource('cars/' + id).delete()
        .then(response => {
          console.info('from deleteCar (then), response status: ', response.ok);
          eventEmitter.$emit('needReloadCars', 'deleteCar');
        }).catch(response => {
          console.info('from deleteCar (catch), response status: ', response.ok);
        })
      }

    },
    created () {
      this.resource = this.$resource;
      /*
        get: {method: 'GET'},
        save: {method: 'POST'},
        query: {method: 'GET'},
        update: {method: 'PUT'},
        remove: {method: 'DELETE'},
        delete: {method: 'DELETE'}
      */

      eventEmitter.$on('needReloadCars', (by) => {
        console.log('from needReloadCars, SENDED BY: ', by);  //ВЫВОДИТСЯ В КОНСОЛЬ БРАУЗЕРА
        this.loadCars();    //обновляю список машин
      })
    }
  }
</script>


<style scoped>

</style>
