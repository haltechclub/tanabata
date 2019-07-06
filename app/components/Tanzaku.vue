<template>
  <div :style="backColor" class="item">
    <div class="inner">
      <div class="body-text">
        <table class="body-table">
          <tr>
            <td class="entry">
              {{ wish.body }}
            </td>
          </tr>
        </table>
      </div>
      <div class="footer-text">
        {{ wish.created_at | kanji }}
        {{ wish.name }}
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment'

  export default {
    name: 'Tanzaku',
    filters: {
      kanji: (stringDate) => {
        const date = moment(stringDate)

        return (
          tokanzi(date.year()).toString() + '年 ' +
          tokanzi((date.month() + 1)).toString() + '月 ' +
          tokanzi(date.date()).toString() + '日'
        )
      }
    },
    data() {
      return {
        day: moment(this.wish.created_at),
        backColor: {
          'background': this.wish.color
        }
      }
    },
    props: {
      wish: Object
    }
  }

  function tokanzi(bef) {
    const txt = ['〇', '一', '二', '三', '四', '五', '六', '七', '八', '九']
    let str = ''
    while (bef > 0) {
      const key = bef % 10
      str = txt[key] + str
      bef = Math.floor(bef / 10)
    }
    return str
  }

</script>

<style lang="scss" scoped>
  .item {
    width: 128px;
    height: 512px;
    margin: 16px;
    border: white 3px solid;
    position: relative;
    color: #444;
    font-weight: bolder;
    font-family: 'Hannari', serif;
  }

  .inner {
    background: rgba(255,255,255,0.8);
    padding-top: 10px;
    writing-mode: vertical-rl;
    text-orientation: upright;
    position: absolute;
    height: 95%;
    width: 90%;
    top: 50%;
    left: 50%;
    display: inline;
    transform: translate(-50%, -50%);
  }

  .body-text {
    overflow: hidden;
    position: relative;
    width: 75%;
    font-size: large;
    word-break: break-all;
  }

  .body-table {
    width: 100%;
  }

  .entry {
    vertical-align: middle;
  }

  .footer-text {
    position: absolute;
    bottom: 10px;
    left: 0;
    overflow: hidden;
    white-space: nowrap;
  }

  a {
    text-decoration: none;
    border-right: 1px dashed;
    padding-right: 3px;
  }
</style>
