<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <v-container fluid>
        <v-layout row>
            <v-flex xs12>
                <h1>Grid no-data</h1>

                <v-data-table
                        :headers="headers"
                        :items="desserts"
                        class="elevation-1"
                >
                    <template v-slot:no-data>
                        <v-alert :value="true" color="error" icon="warning">
                            Нет данных для отображения
                        </v-alert>
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
          {
            text: 'Dessert (100g serving)',
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Calories', value: 'calories' },
          { text: 'Fat (g)', value: 'fat' },
          { text: 'Carbs (g)', value: 'carbs' },
          { text: 'Protein (g)', value: 'protein' },
          { text: 'Iron (%)', value: 'iron' }
        ],
        desserts: []
      }
    }
  }
</script>
