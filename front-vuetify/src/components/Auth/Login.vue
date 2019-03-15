<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6 lg4>
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Login form</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="validClient" ref="form" validation>
              <v-text-field
                prepend-icon="person"
                name="username"
                label="Логин"
                type="username"
                v-model="username"
                @focus="validateServer"
                :rules="usernameRules"
                :error-messages="messages.username"
              ></v-text-field>
              <v-text-field
                prepend-icon="lock"
                name="password"
                label="Пароль"
                type="password"
                :counter="20"
                v-model="password"
                @focus="validateServer"
                :rules="passwordRules"
                :error-messages="messages.password"
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
            >Login</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  // const usernameRegex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/

  export default {
    data () {
      return {
        validClient: false,
        validServer: true,
        username: '',
        password: '',
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
        if (this.validClient && this.$refs.form.validate()) {
          const user = {
            username: this.username,
            password: this.password
          }
          console.log('onSubmit user: ', user);

          this.$store.dispatch('loginUser', user)
          .then(() => {
            this.$router.push('/')
          })
          .catch((errors) => {  // {"password":["Incorrect username or password."]}
            console.log('from onSubmit, errors: ', errors);
// https://github.com/vuetifyjs/vuetify/issues/218
// Form fields custom validation message
            for (let field in errors) {
              let err = errors[field]
              console.log('field, err: ', field, err);
              for (let mes in err) {
                console.log('mes: ', err[mes]);
                this.messages[field] = err[mes]
                this.validServer = false;
              }
            }
          })
        }
      }
    },
    created () {
      if (this.$route.query['loginError']) {
        this.$store.dispatch('setError', 'Пожалуйста, авторизуйтесь для доступа к этой странице.')
      }
    }
  }
</script>
