<template>
  <div class="app" id="challenge">
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <div class="card-container challenge-panel-container">
          <p class="title">挑战</p>
          <p>当前状态：{{ socket_msg }}</p>
          <p>正在匹配人数：{{ online_num }}</p>
          <p>
            这是介绍...... 这是介绍...... 这是介绍...... 这是介绍...... 
          </p>
          <p class="center"><Avatar size="large" :src="userInfo.avatar" /></p>
          <p class="center">当前用户：{{ userInfo.name }}</p>
          <p class="center">{{ userInfo.describe }}</p>
          <p class="center button-container">
            <Button @click="findOpponent" size="large" type="success">匹配</Button>
          </p>
          <Spin size="large" fix v-if="isFindng"></Spin>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import io from "socket.io-client"
export default {
  data() {
    let userInfo = JSON.parse(localStorage.getItem("userinfo"))
    return {
      online_num: 0,
      socket_msg: "未连接",
      userInfo: userInfo,
      io: io,
      socket: {},
      isFindng: false,
    }
  },
  methods: {
    findOpponent() {
      //发送匹配事件给后端
      let userInfo = JSON.parse(localStorage.getItem("userinfo"))
      this.socket.emit("find", {username: userInfo.name})
      this.socket_msg = "开始匹配..."
      this.isFindng = true
    },
    initScoket() {
      this.socket = this.io("http://localhost:3000")
      let socket = this.socket
      let that = this
      
      //开始绑定各种监听
      //连接成功
      socket.on("successconn", function () {
        that.socket_msg = "已连接！"
      })
      //人数更新
      socket.on("onlineupdate", function (num) {
        that.online_num = num
      })

      //正在寻找对手
      socket.on('finding', function(msg){
        that.socket_msg = "正在匹配对手..."
      });

      //找到对手
      socket.on("found", function (data) {
        that.socket_msg = "找到对手：" + data.opponent
        that.isFindng = false
      })

    }
  },
  mounted() {
    this.initScoket()
  }
};
</script>
<style lang="sass">
#challenge
  .challenge-panel-container
    *
      // color: #fff
    border: .3rem solid #eee
    padding: 2rem
    // background: #2b85e4
    .title
      text-align: center
      font-size: 2rem
    .button-container
    .center
      text-align: center
</style>
