<template>
  <v-container>
    <v-layout row>
      <v-flex xs12 sm6 offset-sm3>
        <h1 class="text--secondary mb-3">Create new ad</h1>
        <v-form v-model="valid" ref="form" validation class="mb-3">
          <v-text-field
            name="title"
            label="Ad title"
            type="text"
            v-model="title"
            required
            :rules="[v => !!v || 'Title is required']"
          ></v-text-field>
          <v-textarea
            name="description"
            label="Ad description"
            type="text"
            v-model="description"
            :rules="[v => !!v || 'Description is required']"
          ></v-textarea>
        </v-form>
        <v-layout row class="mb-3">
          <v-flex xs12>
              <v-btn class="warning" @click="triggerUpload">
              Upload
              <v-icon right dark>cloud_upload</v-icon>
            </v-btn>
            <input
                  ref="fileInput"
                  type="file"
                  style="display: none;"
                  accept="image/*"
                  @change="onFileChange"
            >
          </v-flex>
        </v-layout>
        <v-layout row>
          <v-flex xs12>
            <img :src="imageSrc" height="100" v-if="imageSrc">
          </v-flex>
        </v-layout>
        <v-layout row>
          <v-flex xs12>
            <v-switch
              label="Add to promo?"
              v-model="promo"
              color="primary"
            ></v-switch>
          </v-flex>
        </v-layout>
        <v-layout row>
          <v-flex xs12>
            <v-spacer></v-spacer>
            <v-btn
              :loading="loading"
              :disabled="!valid || !image || loading"
              class="success"
              @click="createAd"
            >
              Create ad
            </v-btn>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        title: '',
        description: '',
        promo: false,
        valid: false,
        image: null,    // объект File
        imageSrc: ''    // само изображение в base64
      }
    },
    computed: {
      loading () {
        return this.$store.getters.loading
      }
    },
    methods: {
      createAd () {
        if (this.$refs.form.validate() /*&& this.image*/) {
          // logic
          const ad = {
            title: this.title,
            description: this.description,
            promo: this.promo,
            // image: this.image
            imageSrc: 'https://cdn-images-1.medium.com/max/850/1*nq9cdMxtdhQ0ZGL8OuSCUQ.jpeg'
          }

          this.$store.dispatch('createAd', ad)
          .then(() => {
            this.$router.push('/list')
          })
          .catch(() => {})
        }
      },
      triggerUpload () {
        this.$refs.fileInput.click()
      },
      onFileChange (event) {
        const file = event.target.files[0]
        console.log('onFileChange: ', file)

        const reader = new FileReader()   // стандартный класс JavaScript

        // прослушка события на окончание загрузки файла (т.к. загрузка файла выполняется асинхр.)
        reader.onload = e => {
          this.imageSrc = reader.result   // само изображение в base64
        }

        // асинхронная операция; считывает изображение в base64 как атрибут src в тег <img>
        reader.readAsDataURL(file)
        this.image = file   // объект File
      }
    }
  }
</script>
