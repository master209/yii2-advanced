<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
  <v-container fluid>
    <v-layout row>
      <v-flex xs12>

        <h1>Пользователи</h1>

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
          </template>
        </v-data-table>

      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        headers: [
          {text: 'id', value: 'id',},
          { text: 'Логин', value: 'username' },
          { text: 'ФИО', value: 'fio' },
          { text: 'Телефон', value: 'phoneMob' },
          { text: 'E-mail', value: 'email' },
          { text: 'Статус', value: 'status' }
        ],
      }
    },
    computed: {
      users () {
        return this.$store.getters.users
      },
      loading () {
        return this.$store.getters.loading
      }
    },
    created () {
      this.$store.dispatch('fetchUsers')
    }
  }
</script>
