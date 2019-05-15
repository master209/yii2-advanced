<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6>
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Регистрация</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="validClient" ref="form" validation>
              <v-text-field
                prepend-icon="person"
                name="username"
                label="Логин"
                type="username"
                v-model="username"
                :rules="usernameRules"
                :error-messages="messages.username"
              ></v-text-field>
              <v-text-field
                prepend-icon="lock"
                name="password"
                label="Пароль"
                type="password"
                :counter="6"
                v-model="password"
                :rules="passwordRules"
                :error-messages="messages.password"
              ></v-text-field>
              <v-text-field
                prepend-icon="lock"
                name="confirm-password"
                label="Подтверждение пароля"
                type="password"
                :counter="6"
                v-model="confirmPassword"
                :rules="confirmPasswordRules"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              @click="onSubmit"
              :loading="loading"
              :disabled="!validClient || loading"
            >Создать аккаунт!</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        validClient: false,
        validServer: true,
        username: '',
        password: '',
        confirmPassword: '',
        messages: {
          username: [],
          password: [],
        }
      }
    },
    computed: {
      loading () {
        return this.$store.getters.loading
      },
      usernameRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Логин»',
          v => (v && v.length >= 2) || 'не менее 2 символов',
          v => (v && v.length <= 20) || 'не более 20 символов'
        ]
      },
      passwordRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Пароль»',
          v => (v && v.length >= 6) || 'не менее 6 символов'
        ]
      },
      confirmPasswordRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Подтверждение пароля»',
          v => v === this.password || 'Пароли должны совпадать'
        ]
      }
    },
    methods: {
      validateServer () {
        if (!this.validServer) {    //если ошибка пришла с сервера,
          this.validClient = true   //то при фокусе в инпуте разблокирую кнопку Submit на форме
          this.messages.password = null
        }
      },
      onSubmit () {
        if (this.$refs.form.validate()) {
          const user = {
            username: this.username,
            password: this.password
          }

          this.$store.dispatch('registerUser', user)
          .then(() => {
            // console.log('Registration.vue')
            this.$router.push('/')
          })
          .catch((errors) => {  // {"username":["Данный логин уже используется."]}
            for (let field in errors) {
              for (let mes in errors[field]) {
                this.messages[field] = errors[field][mes]
              }
            }
            this.validServer = false;
          })
        }
      }
    }
  }
</script>
