<template>
  <v-container>
    <v-layout row>
      <v-flex xs12>
        <v-card v-if="!loading">
          <!--<v-card-media-->
            <!--:src="ad.imageSrc"-->
            <!--height="300px"-->
          <!--&gt;</v-card-media>-->
          <v-card-text>
            <h1 class="text--primary">{{ad.title}}</h1>
            <p>{{ad.description}}</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <add-edit-ad-modal :ad="ad" v-if="isOwner"></add-edit-ad-modal>
            <app-buy-modal :ad="ad"></app-buy-modal>
          </v-card-actions>
        </v-card>
        <div v-else class="text-xs-center">
          <v-progress-circular
            indeterminate
            :size="100"
            :width="4"
            color="purple"
          ></v-progress-circular>
        </div>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import EditAdModal from './EditAdModal'

export default {
  props: ['id'],
  data () {
    return {
      ad: [],
      isOwner: false
    }
  },
  computed: {
    loading () {
      return this.$store.getters.loading
    }
  },
  methods: {
    markDone (order) {
      this.$store.dispatch('markOrderDone', order.id)
        .then(() => {
          order.done = true
        })
        .catch(() => {})
    }
  },
  created () {
    console.log('Ad.vue created this.id: ', this.id)
    this.$store.dispatch('adById', this.id)
      .then(() => {
        this.ad = this.$store.getters.ad(this.id)
        console.log('Ad.vue created getters.ad: ', this.ad)
        if(!this.$store.getters.user) {
          return false
        }
        this.isOwner = (this.ad.ownerId == this.$store.getters.user.id)
        // console.log('Ad.vue user.id: ', this.$store.getters.user.id)
        console.log('Ad.vue created this.isOwner: ', this.isOwner)
      })
      .catch((error) => {
        console.log('Ad.vue created catch error: ', error)
        // TODO: вместо контента на странице - вывести ошибку 404
      })
  },
  components: {
    'add-edit-ad-modal': EditAdModal
  }
}
</script>
