<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
  <v-app>
    <v-navigation-drawer
            app
            temporary
            v-model="drawer"
    >
    <v-list>
        <v-list-tile
            v-for="link of links"
            :key="link.title"
            :to="link.url"
        >
            <v-list-tile-action>
                <v-icon>{{link.icon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
                <v-list-tile-title v-text="link.title"></v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
            v-if="isUserLoggedIn"
            @click="onLogout"
        >
            <v-list-tile-action>
                <v-icon>exit_to_app</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
                <v-list-tile-title v-text="'Logout'"></v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>

    </v-list>

    </v-navigation-drawer>

    <v-toolbar app dark color="primary">
      <v-toolbar-side-icon
        @click="drawer = !drawer"
        class="hidden-md-and-up"
      ></v-toolbar-side-icon>
      <v-toolbar-title>
          <router-link to="/" tag="span" class="pointer">Ad app</router-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items class="hidden-sm-and-down">
          <v-menu offset-y>
              <template v-slot:activator="{ on }">
                  <v-btn
                          v-on="on"
                          flat
                  >
                      <v-icon left>grid_on</v-icon>
                      Grids
                  </v-btn>
              </template>
              <v-list>
                  <v-list-tile
                          v-for="(item, index) in gridItems"
                          :key="index"
                          :to="item.url"
                  >
                      <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                  </v-list-tile>
              </v-list>
          </v-menu>

          <v-btn
                  v-for="link in links"
                  :key="link.title"
                  :to="link.url"
                  flat
          >
              <v-icon left>{{link.icon}}</v-icon>
              {{link.title}}
          </v-btn>

          <v-btn
              @click="onLogout"
              flat
              v-if="isUserLoggedIn"
          >
              <v-icon left>exit_to_app</v-icon>
              Logout
          </v-btn>

      </v-toolbar-items>
    </v-toolbar>

    <v-content>
      <router-view></router-view>
    </v-content>

    <template v-if="error">
      <v-snackbar
          :timeout="5000"
          :multi-line="true"
          color="error"
          @input="closeError"
          :value="true"
      >
          {{error}}
          <v-btn flat dark @click.native="closeError">Close</v-btn>
      </v-snackbar>
    </template>
  </v-app>
</template>

<script>

export default {
  data: function () {
    return {
      drawer: false
    }
  },
  computed: {
    error () {
      return this.$store.getters.error
    },
    isUserLoggedIn () {
      return this.$store.getters.isUserLoggedIn
    },
    links () {
      if (this.isUserLoggedIn) {
        return [
          {title: 'Users', icon: 'people', url: '/users'},
          {title: 'Orders', icon: 'bookmark_border', url: '/orders'},
          {title: 'New ad', icon: 'note_add', url: '/new'},
          {title: 'My ads', icon: 'list', url: '/list'},
        ]
      }

      return [
        {title: 'Login', icon: 'lock', url: '/login'},
        {title: 'Registration', icon: 'face', url: '/registration'}
      ]
    },
    gridItems () {
      return [
        {title: 'No-data', url: '/grid-no-data'},
        {title: 'Standard', url: '/grid-standard'},
        {title: 'Items and headers slots', url: '/grid-items-and-headers-slots'},
        {title: 'HeaderCell slot', url: '/grid-headercell-slot'},
        {title: 'Progress', url: '/grid-progress'},
        {title: 'Footer', url: '/grid-footer'},
        {title: 'Expandable', url: '/grid-expandable'},
        {title: 'Selectable rows', url: '/grid-selectable-rows'},
        {title: 'Search filter', url: '/grid-search-filter'},
        {title: 'Paginator custom icons', url: '/grid-paginator-custom-icons'},
        {title: 'External pagination', url: '/grid-external-pagination'},
        {title: 'External sorting', url: '/grid-external-sorting'},
        {title: 'Paginate and sort server side', url: '/grid-paginate-and-sort-server-side'},
        {title: 'Remove header and footer', url: '/grid-remove-header-and-footer'},
        {title: 'Inline Editing', url: '/grid-inline-editing'},
        {title: 'CRUD Actions', url: '/grid-crud-actions'},
      ]
    }
  },
  methods: {
    closeError () {
      this.$store.dispatch('clearError')
    },
    onLogout () {
      this.$store.dispatch('logoutUser')
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>
    .pointer {
        cursor: pointer;
    }
</style>