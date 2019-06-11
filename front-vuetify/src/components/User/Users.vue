<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
  <v-container fluid>
    <v-layout row>
      <v-flex xs12>

        <h1>Пользователи</h1>

        <v-toolbar flat color="white">
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" dark class="mb-2" v-on="on">Новый элемент</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs12 sm6 md4>
                      <v-text-field v-model="editedItem.username" label="Логин"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field v-model="editedItem.fio" label="ФИО"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field v-model="editedItem.phoneMob" label="Телефон"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field v-model="editedItem.email" label="E-mail"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                      <v-text-field v-model="editedItem.status" label="Статус"></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="close">Отменить</v-btn>
                <v-btn color="blue darken-1" flat @click="save">Сохранить</v-btn>
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
            <td >{{ props.item.fio }}</td>
            <td >{{ props.item.phoneMob }}</td>
            <td >{{ props.item.email }}</td>
            <td >{{ props.item.status }}</td>
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

      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data: () => ({
        dialog: false,
        headers: [
          {text: 'id', value: 'id',},
          { text: 'Логин', value: 'username' },
          { text: 'ФИО', value: 'fio' },
          { text: 'Телефон', value: 'phoneMob' },
          { text: 'E-mail', value: 'email' },
          { text: 'Статус', value: 'status' }
        ],
        editedIndex: -1,
        editedItem: {
          id: '',
          username: 0,
          fio: 0,
          phoneMob: 0,
          email: 0,
          status: 0
        },
        defaultItem: {
          id: '',
          username: 0,
          fio: 0,
          phoneMob: 0,
          email: 0,
          status: 0
        }
    }),

    computed: {
      users () {
        return this.$store.getters.users
      },
      loading () {
        return this.$store.getters.loading
      },
      formTitle () {
        return this.editedIndex === -1 ? 'Новый элемент' : 'Редактировать элемент'
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.$store.dispatch('fetchUsers')
    },

    methods: {
      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.users.indexOf(item)
        confirm('Вы действительно хотите удалить этот элемент?') && this.users.splice(index, 1)
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.users[this.editedIndex], this.editedItem)
        } else {
          this.users.push(this.editedItem)
        }
        this.close()
      }
    }

  }
</script>
