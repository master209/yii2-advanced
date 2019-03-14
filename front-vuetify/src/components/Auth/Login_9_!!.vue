<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6 lg4>
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Login form</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="valid" ref="form" validation>
              <v-text-field
                prepend-icon="person"
                name="username"
                label="Логин"
                type="username"
                v-model="username"
                @focus="onServerValidate"
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
                @focus="onServerValidate"
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
              :disabled="!valid || loading"
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
        valid: false,
        username: '',
        password: '',
        wasServerValidate: false,
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
          v => !!v || 'Необходимо заполнить «Логин»',
          v => (v && v.length >= 2) || 'Логин должен быть не короче 2 символов',
          v => (v && v.length <= 20) || 'Логин должен быть не длиннее 20 символов'
        ]
      },
      passwordRules() {
        return [
          v => !!v || 'Необходимо заполнить «Пароль»',
          v => (v && v.length >= 6) || 'Пароль должен быть не короче 6 символов'
        ]
      }
    },
    methods: {
      onServerValidate () {
        if (this.wasServerValidate) {
          this.valid = true
          this.messages.password = null
        }
      },
      onSubmit () {
        if (this.valid && this.$refs.form.validate()) {
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
            this.valid = false;
            console.log('from onSubmit, errors: ', JSON.parse(errors));
// https://github.com/vuetifyjs/vuetify/issues/218
// Form fields custom validation message
            let _errors = JSON.parse(errors)
            for (let field in _errors) {
              let err = _errors[field]
              console.log('field, err: ', field, err);
              for (let mes in err) {
                console.log('mes: ', err[mes]);
                this.messages[field] = err[mes]
                this.wasServerValidate = true;
              }
            }
          })
        }
      }
    },
    created () {
      if (this.$route.query['loginError']) {
        this.$store.dispatch('setError', 'Please log in to access this page.')
      }
    }
  }
</script>
