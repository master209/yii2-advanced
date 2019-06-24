<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
  <v-container fluid>
    <v-layout row>
      <v-flex xs12>

      <div v-if="!error">
        <h1>Пользователи</h1>

        <v-toolbar flat color="white">
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="800px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" dark class="mb-2" v-on="on">Новый элемент</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container grid-list-md>
                  <v-form v-model="validClient" ref="form" validation>
                    <v-layout wrap>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.username"
                                name="username"
                                label="Логин"
                                prepend-icon="person"
                                @focus="validateServer"
                                :rules="usernameRules"
                                :error-messages="messages.username"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.password"
                                name="password"
                                label="Пароль"
                                type="password"
                                prepend-icon="lock"
                                :counter="30"
                                @focus="validateServer"
                                :rules="passwordRules"
                                :error-messages="messages.password"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.email"
                                name="email"
                                label="E-mail"
                                type="email"
                                prepend-icon="alternate_email"
                                @focus="validateServer"
                                :rules="emailRules"
                                :error-messages="messages.email"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.phoneMob"
                                name="phoneMob"
                                label="Телефон"
                                :mask="phoneMask"
                                prepend-icon="phone"
                                @focus="validateServer"
                                :error-messages="messages.phone_mob"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.lastname"
                                name="lastname"
                                label="Фамилия"
                                @focus="validateServer"
                                :rules="lastnameRules"
                                :error-messages="messages.lastname"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.firstname"
                                name="firstname"
                                label="Имя"
                                @focus="validateServer"
                                :rules="firstnameRules"
                                :error-messages="messages.firstname"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.byfather"
                                name="byfather"
                                label="Отчество"
                                @focus="validateServer"
                                :error-messages="messages.byfather"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-select
                                v-model="editedItem.status"
                                :items="statusList"
                                item-value="id"
                                item-text="name"
                                name="status"
                                label="Статус"
                                @focus="validateServer"
                                :error-messages="messages.status"
                        ></v-select>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.birthday"
                                name="birthday"
                                label="Дата рождения"
                                @focus="validateServer"
                                :error-messages="messages.birthday"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.gender"
                                name="gender"
                                label="Пол"
                                @focus="validateServer"
                                :rules="genderRules"
                                :error-messages="messages.gender"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.position"
                                name="position"
                                label="Должность"
                                @focus="validateServer"
                                :error-messages="messages.position"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-text-field
                                v-model="editedItem.other"
                                name="other"
                                type="text"
                                label="Доп инфо"
                                @focus="validateServer"
                                :error-messages="messages.other"
                        ></v-text-field>
                      </v-flex>

                    </v-layout>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="close">Отменить</v-btn>
                <v-btn
                        type="submit"
                        color="primary"
                        @click="save"
                        :loading="loading"
                        :disabled="!validClient || loading"
                >Сохранить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
        
        <v-data-table
            :headers="headers"
            :items="users"
            class="elevation-1"
        >
          <template v-slot:items="props">
            <td>{{ props.item.id }}</td>
            <td>{{ props.item.username }}</td>
            <td >{{ props.item.password }}</td>
            <td >{{ props.item.email }}</td>
            <td >{{ statusById(props.item.status).name }}</td>
            <td >{{ props.item.lastname }} {{ props.item.firstname }}</td>
            <td >{{ props.item.phoneMob }} </td>
            <td >{{ props.item.position }}</td>
            <td class="justify-center layout px-0">
              <v-icon
                      small
                      class="mr-2"
                      @click="editItem(props.item)"
              >
                edit
              </v-icon>
              <v-icon
                      small
                      @click="deleteItem(props.item)"
              >
                delete
              </v-icon>
            </td>
          </template>
        </v-data-table>
      </div>
        <div v-else>
          <h2 style="color: red;">403 Forbidden. {{error}}</h2>
        </div>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  const emailRegex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,5})+$/

  export default {
    data: () => ({
        dialog: false,
        error: false,
        validClient: false,
        validServer: true,
        phoneMask: 'phone',
        headers: [
          {text: 'id', value: 'id',},
          { text: 'Логин', value: 'username' },
          { text: 'Пароль', value: 'password' },
          { text: 'E-mail', value: 'email' },
          { text: 'Статус', value: 'status' },
          { text: 'ФИО', value: 'fio' },
          { text: 'Телефон', value: 'phoneMob' },
          { text: 'Должность', value: 'position' },
        ],
        editedIndex: -1,  // признак созд. нового элемента (-1) или редактировния старого
        editedItem: {
          id: '',
          username: 0,
          password: 0,
          email: 0,
          status: 0,

          lastname: 0,
          firstname: 0,
          byfather: 0,
          phoneMob: 0,
          birthday: 0,
          gender: 0,
          position: 0,
          other: 0,
        },
        defaultItem: {
          id: '',
          username: 0,
          password: 0,
          email: 0,
          status: 0,

          lastname: 0,
          firstname: 0,
          byfather: 0,
          phoneMob: 0,
          birthday: 0,
          gender: 0,
          position: 0,
          other: 0,
        },
        messages: {
          username: [],
          email: [],
          password: [],
          status: [],

          lastname: [],
          firstname: [],
          byfather: [],
          phone_mob: [],
          birthday: [],
          gender: [],
          position: [],
          other: [],
        }
    }),

    computed: {
      users () {
        return this.$store.getters.users
      },
      loading () {
        return this.$store.getters.loading
      },
      statusList () {
        return this.$store.getters.statusList
      },
      formTitle () {
        return this.editedIndex === -1 ? 'Новый элемент' : `Редактирование пользователя ${this.editedItem.username}`
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
      emailRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «E-mail»',
          v => emailRegex.test(v) || 'Некорректный E-mail'
        ]
      },
      lastnameRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Фамилия»',
          v => (v && v.length <= 255) || 'не более 255 символов'
        ]
      },
      genderRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Пол»',
        ]
      },
      firstnameRules() {
        return [
          v => !!v || 'Необходимо заполнить поле «Имя»',
          v => (v && v.length <= 255) || 'не более 255 символов'
        ]
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.$store.dispatch('statusList')
        .then(() => {                           //грид юзеров не отобразится пока не будет готов массив статусов
          this.$store.dispatch('fetchUsers')
            .catch((error) => {
              if(error.body.status === 403) {
                this.error = 'Вы не можете просматривать эту страницу'
              }
            })
        })
    },

    methods: {
      editItem (item) {   //копирует редактируемый объект и отображает его в модальном окне
        console.log('Users.vue editItem(editItem): ', item)   //весь объект User
        this.editedIndex = this.users.indexOf(item)
        console.log('Users.vue editedIndex: ', this.editedIndex)  //индекс объекта item в массиве Users[]
        this.editedItem = Object.assign({}, item)   //копирование объекта из item в {}
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.users.indexOf(item)
        confirm('Вы действительно хотите удалить этот элемент?') && this.users.splice(index, 1)
      },

      close () {
        this.dialog = false
        this.resetFields()
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)   //копирование объекта (очистка editedItem пустыми значениями из defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      validateServer () {
        if (!this.validServer) {    //если ошибка пришла с сервера,
          this.resetFields()        //то при фокусе в инпуте разблокирую кнопку Submit на форме
        }
      },
      save () {
        if (this.editedIndex > -1) {    //если редактирование
          if (this.$refs.form.validate()) {
            const user = {
              id: this.editedItem.id,
              username: this.editedItem.username,
              password: this.editedItem.password,
              email: this.editedItem.email,
              status: this.editedItem.status,

              lastname: this.editedItem.lastname,
              firstname: this.editedItem.firstname,
              byfather: this.editedItem.byfather,
              phoneMob: this.editedItem.phoneMob,
              birthday: this.editedItem.birthday,
              gender: this.editedItem.gender,
              position: this.editedItem.position,
              other: this.editedItem.other,
            }
            console.log('Users.vue save() user: ', user)
            this.$store.dispatch('updateUser', user)
              .then(() => {
                this.validServer = true;
                Object.assign(this.users[this.editedIndex], this.editedItem)
                this.close()
                // this.$router.push('/')
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
        } else {    //иначе - новый объект
          this.users.push(this.editedItem)
        }
      },

      resetFields () {
        this.validClient = true   //разблокирую кнопку Submit на форме
        this.messages.username = null
        this.messages.password = null
        this.messages.email = null
        this.messages.status = null

        this.messages.lastname = null
        this.messages.firstname = null
        this.messages.byfather = null
        this.messages.phone_mob = null
        this.messages.birthday = null
        this.messages.gender = null
        this.messages.position = null
        this.messages.other = null
      },

      statusById (id) {
        return this.$store.getters.statusById(id)
      },
    }

  }
</script>
