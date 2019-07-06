<template>
  <v-app dark>
    <v-content class="star">
      <v-container>
        <nuxt/>
      </v-container>
    </v-content>
    <v-footer :fixed="fixed" app>
      <span>&copy; 2019 HAL Tech Club</span>
    </v-footer>
  </v-app>
</template>

<style>
  .star{
    background-color: gray;
    background-image: url('~assets/images/bg.gif');
    background-repeat: repeat;
  }
</style>

<script>
  export default {
    data() {
      return {
        clipped: false,
        drawer: false,
        fixed: false,
        items: [
          {
            icon: 'apps',
            title: 'Welcome',
            to: '/'
          },
          {
            icon: 'bubble_chart',
            title: 'Inspire',
            to: '/inspire'
          }
        ],
        miniVariant: false,
        right: true,
        rightDrawer: false,
        title: 'Vuetify.js'
      }
    }
  }
</script>
