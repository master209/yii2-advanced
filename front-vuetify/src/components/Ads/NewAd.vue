<template>
  <v-container>
    <v-layout row>
      <v-flex xs12 sm6 offset-sm3>
        <h1 class="text--secondary mb-3">Добавить новое объявление</h1>
        <v-form
            action=""
            method="post"
            enctype="multipart/form-data"
            v-model="valid"
            ref="form"
            validation class="mb-3"
        >
          <v-text-field
            name="title"
            label="Заголовок"
            type="text"
            v-model="title"
            required
            :rules="[v => !!v || 'Заголовок является обязательным']"
            :error-messages="messages.title"
          ></v-text-field>
          <v-textarea
            name="description"
            label="Описание"
            type="text"
            v-model="description"
            :rules="[v => !!v || 'Описание является обязательным']"
            :error-messages="messages.description"
          ></v-textarea>
          <v-layout row class="mb-3">
            <v-flex xs12>
                <v-btn class="warning" @click="triggerUpload">
                Файл
                <v-icon right dark>cloud_upload</v-icon>
              </v-btn>
              <input
                    ref="fileInput"
                    type="file"
                    style="display: inherit;"
                    accept="image/*"
                    @change="onFileChange"
              >
            </v-flex>
          </v-layout>
        </v-form>
        <v-layout row>
          <v-flex xs12>
            <img :src="imageSrc" height="120" v-if="imageSrc">
          </v-flex>
        </v-layout>
        <v-layout row>
          <v-flex xs12>
            <v-switch
              label="Добавить в промо?"
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
              Создать объяву
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
        imageSrc: '',    // само изображение в base64
        messages: {
          title: [],
          description: [],
        }

      }
    },
    computed: {
      loading () {
        return this.$store.getters.loading
      }
    },
    methods: {
      createAd () {
        if (this.$refs.form.validate() && this.image) {
          // logic
          const ad = {
            title: this.title,
            description: this.description,
            promo: this.promo,
            image: this.image
            // imageSrc: 'https://cdn-images-1.medium.com/max/850/1*nq9cdMxtdhQ0ZGL8OuSCUQ.jpeg'
          }

          this.$store.dispatch('createAd', ad)
          .then(() => {
            this.$router.push('/list')
          })
          .catch((errors) => {
            for (let field in errors) {
              for (let mes in errors[field]) {
                this.messages[field] = errors[field][mes]
              }
            }
          })

        }
      },
      triggerUpload () {
        this.$refs.fileInput.click()
      },
      onFileChange (event) {
        const file = event.target.files[0]
        this.image = file   // объект File
        console.log('onFileChange: ', file)

        const reader = new FileReader()   // стандартный класс JavaScript

        // прослушка события на окончание загрузки файла (т.к. загрузка файла выполняется асинхр.)
        reader.onload = e => {
          this.imageSrc = reader.result   // само изображение в base64
          // console.log('onFileChange imageSrc: ', this.imageSrc)
        }

        // асинхронная операция; считывает изображение в base64 как атрибут src в тег <img>
        reader.readAsDataURL(file)
      }
    }
  }
</script>
