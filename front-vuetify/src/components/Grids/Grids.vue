<template>
    <v-container fluid>
        <v-layout row>
            <v-flex xs12>
                <h1>Grids</h1>
                <app-grid-standard></app-grid-standard>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import GridStandard from './GridStandard'

  export default {
    components: {
      'app-grid-standard': GridStandard
    }  }
</script>
