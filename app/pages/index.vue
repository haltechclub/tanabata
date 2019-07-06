<template>
  <v-layout column justify-center align-center wrap>
    <v-flex xs12 sm8 md6>
      <div class="text-xs-center">
        <logo/>
      </div>
      <v-layout wrap justify-space-around>
        <div v-for="item in items" :key="item.id">
          <Tanzaku :wish="item"></Tanzaku>
        </div>
      </v-layout>
    </v-flex>

    <v-fab-transition>
      <v-btn
        @click="dialog = true"
        color="green"
        fixed
        bottom
        right
        fab>
        <v-icon>add</v-icon>
      </v-btn>
    </v-fab-transition>
    <v-dialog v-model="dialog" persistent max-width="600px">
      <div class="parent">
        <div class="dialog">
          <h2>願い事</h2>
          <form>
            <div>
              <textarea v-model.trim="wish" class="inputForm tArea"></textarea>
            </div>
            <div class="name">
              <label>お名前</label>
              <input v-model.trim="name" type="text" class="inputForm tName"/>
            </div>
            <div class="img">
              <label>背景</label>
              <input @change="onUpload()" type="file" class="inputForm iImg">
            </div>
          </form>
        </div>
        <span class="bt btBack" @click="dialog = false">やめておく</span>
        <span class="bt btWrite" @click="write">書き込む</span>
      </div>
    </v-dialog>
  </v-layout>
</template>

<style lang="scss" scoped>

  .parent {
    position: relative;
    overflow: hidden;
    height: 500px;
    background: gray;
    font-size: 18px;
    font-family: 'Hannari', serif;
  }

  .dialog {
    width: 100%;
    text-orientation: upright;
    writing-mode: vertical-rl;

    h2 {
      margin: 25px;
    }
  }

  .inputForm {
    writing-mode: initial;
    transform: rotate(90deg);
    transform-origin: top left;
    margin-top: 50px;
    color: black;
  }

  .tArea {
    background: white;
    width: 400px;
    height: 200px;
    margin-right: -400px;
  }

  .tName {
    background: white;
    width: 200px;
    height: 20px;
    margin-right: -200px;
  }

  .name {
    margin-right: 200px;
    margin-top: 200px;
  }

  .img {
    margin-right: 20px;
    margin-top: 210px;
  }

  .iImg {
    font-size: 14px;
    width: 200px;
    height: 40px;
    margin-right: -200px;
  }

  .bt {
    position: absolute;
    width: 40px;
    height: 100px;
    line-height: 40px;
    text-align: center;
    text-orientation: upright;
    writing-mode: vertical-rl;
    border: #00C48D solid 1px;

    bottom: 10px;
  }

  .btBack {
    left: 10px;
  }

  .btWrite {
    left: 60px;
  }
</style>

<script>
  import Logo from '~/components/Logo.vue'
  import Tanzaku from '~/components/Tanzaku.vue'

  const uuidv4 = require('uuid/v4')
  const colors = ['green', 'yellow', 'red', 'blue']
  export default {
    components: {
      Logo,
      Tanzaku
    },
    methods: {
      onUpload: function () {
        this.images = event.target.files
      },
      write: function (event) {
        this.dialog = false
        if (this.userId === '') {
          this.userId = 'usr-' + uuidv4()
        }
        const id = 'pst-' + uuidv4()
        const back = colors[Math.floor(Math.random() * colors.length)]
        this.items.unshift({
          id: id,
          body: this.wish,
          name: this.name,
          color: back,
          user_id: this.userId,
          background: '',
          created_at: new Date()
        })

        const formData = new FormData()
        formData.append('id', id)
        formData.append('body', this.wish)
        formData.append('name', this.name)
        formData.append('color', back)
        formData.append('user_id', this.userId)
        for (let i = 0; i < this.images.length; i++) {
          const image = this.images[i]
          formData.append('background[]', image)
        }
        this.$axios.$post('/api/', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })

        this.wish = ''
        this.name = ''
        this.images = []
      }
    },
    data() {
      return {
        dialog: false,
        items: [],
        wish: '',
        name: '',
        userId: '',
        images: []
      }
    },
    async asyncData({ app }) {
      const response = await app.$axios.$get('/api/')
      return {
        items: response
      }
    }
  }
</script>
