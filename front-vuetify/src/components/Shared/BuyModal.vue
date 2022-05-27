<template>
  <v-dialog width="400px" v-model="modal">
    <v-btn class="primary" flat slot="activator">Купить</v-btn>

    <v-card>
      <v-container>
        <v-layout row>
          <v-flex xs12>
            <v-card-title>
              <h1 class="text--primary">Хотите купить это?</h1>
            </v-card-title>
          </v-flex>
        </v-layout>
        <v-divider></v-divider>
        <v-layout row>
          <v-flex xs12>
            <v-form v-model="validClient" ref="form" validation>
            <v-card-text>
                <v-text-field
                  name="name"
                  label="Ваше имя"
                  type="text"
                  v-model="name"
                  :rules="nameRules"
                  :error-messages="messages.name"
                ></v-text-field>
                <v-text-field
                  name="phone"
                  label="Ваш телефон"
                  type="text"
                  v-model="phone"
                  :rules="phoneRules"
                  :error-messages="messages.phone"
                ></v-text-field>
              </v-card-text>
              <v-divider></v-divider>
              <v-layout row>
                <v-flex xs12>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      flat
                      @click="onCancel"
                      :disabled="localLoading"
                    >
                      Закрыть
                    </v-btn>
                    <v-btn
                      type="submit"
                      class="success"
                      flat
                      @click="onSave"
                      :disabled="!validClient || localLoading"
                      :loading="localLoading"
                    >
                      Купить!
                    </v-btn>
                  </v-card-actions>
                </v-flex>
              </v-layout>
            </v-form>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    props: ['ad'],
    data () {
      return {
        validClient: false,
        modal: false,
        name: '',
        phone: '',
        messages: {
          name: [],
          phone: [],
        },
        localLoading: false
      }
    },
    computed: {
      loading () {
        return this.$store.getters.loading
      },
      nameRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Ваше имя»',
          v => (v && v.length >= 2) || 'не менее 2 символов',
          v => (v && v.length <= 20) || 'не более 20 символов'
        ]
      },
      phoneRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Ваш телефон»',
          v => (v && v.length >= 6) || 'не менее 6 символов'
        ]
      }
    },
    methods: {
      onCancel () {
        this.name = ''
        this.phone = ''
        this.modal = false
      },
      onSave () {
        // if (this.name !== '' && this.phone !== '') {
        if (this.validClient && this.$refs.form.validate()) {
          console.log('BuyModal.vue onSave() ad: ', this.ad)
          this.localLoading = true
          this.$store.dispatch('createOrder', {
            name: this.name,
            phone: this.phone,
            adId: this.ad.id,
            // ownerId: this.ad.ownerId
          })
          .finally(() => {  // ".finally() вызывается в любом случае - если .than() или .catch()"
            this.name = ''
            this.phone = ''
            this.localLoading = false
            this.modal = false
          })
        }
      }
    }
  }
</script>
