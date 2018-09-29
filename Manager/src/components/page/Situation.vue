<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-setting"></i> 概况</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <el-row :gutter="10">
      <el-col :xs="24" :sm="24" :md="18" :lg="18" :xl="18">
        <div class="plugins-tips">
          <div class="content-title">共
            <span class="number cursor" @click="jumpTo('userManage')">{{ curUserNum }}</span>名注册用户，贡献了
            <span class="number cursor" @click="jumpTo('allQuestions')">{{ curQuestionsNum }}</span>个问题，
            <span class="number">{{ 3 }}</span>名用户正在解决问题。</div>
        </div>
        <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>日活跃</span>
            </div>
            <div class="text item">
              <schart canvasId="line" width="850" height="300" :data="dailyLivenessData" type="bar" :options="Options"></schart>
            </div>
          </el-card>
        </el-col>
      </el-col>
      <el-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>待办事项</span>
          </div>
          <div class="text item">
            <el-badge :value="1" :max="99" class="item">
              <el-button size="large" @click="toReviewQuestion">待审核问题</el-button>
            </el-badge>
          </div>
        </el-card>
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>登陆信息</span>
          </div>
          <div class="login-info">
            <p class="avatar"><img src="http://p6a87gauo.bkt.clouddn.com/user_23.png"></p>
            <p class="name">iimT</p>
            <p class="identify">管理员</p>
            <p class="time">{{ curTime }}</p>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <div class="ms-doc">
      <h3>我们的愿景</h3>
      <article>
        <h1>SchoolRush</h1>
        <p>一个基于用户问题解决与分享的刷题平台</p>
        <h2>前言</h2>
        <p>解决自己擅长专业的问题来获取知识，通过设计问题来分享自己在解决问题时获得的经验。每个用户即是学者，也是老师，刷题为自己的高校冲榜。</p>
        <!-- <h2>TODO: </h2>
        <el-checkbox disabled checked>首页数据展示</el-checkbox>
        <br>
        <el-checkbox disabled checked>学校/专业管理</el-checkbox> -->
      </article>
    </div>
  </div>
</template>

<script>
import Schart from "vue-schart";
export default {
  data: function() {
    return {
      dailyLivenessData: [
        { name: "11:00", value: 1141 },
        { name: "12:00", value: 1499 },
        { name: "13:00", value: 2260 },
        { name: "14:00", value: 1170 },
        { name: "15:00", value: 970  },
        { name: "16:00", value: 1450 },
        { name: "17:00", value: 2260 },
        { name: "18:00", value: 1170 },
        { name: "19:00", value: 970  },
        { name: "20:00", value: 1450 },
      ],
      Options: {
        bgColor    : "#ffffff",
        titleColor : "#ffffff",
        legendColor: "#ffffff"
      },
      curTime         : "",
      curUserNum      : 89,
      curQuestionsNum : 5,
      curOnlineNum    : 3,
      readyToReviewNum: 5,
    }
  },
  methods: {
    jumpTo(to) {
      this.$router.push("/" + to)
    },
    getCurTime() {
      let myDate                 = new Date()
      let date                   = myDate.toLocaleDateString()
      let hours                  = myDate.getHours()
      let minutes                = myDate.getMinutes()
      let seconds                = myDate.getSeconds()
      if  (seconds < 10) seconds = "0" + seconds
      if  (minutes < 10) minutes = "0" + minutes
      if  (hours < 10) hours     = "0" + hours

      return date + " " + hours + ":" + minutes + ":" + seconds
    },
    toReviewQuestion() {
      this.$router.push("/reviewQuestion")
    },
    getCurUserNum() {
      let that = this
      let url  = this.$API.getService("User", "getTotalNum")

      this.$API.get(url).then((res) => {
        console.log(res)
          that.curUserNum = res.data.data
      })
    },
    getcurQuestionsNum() {
      let that = this
      let url  = this.$API.getService("Question", "getTotalNum")

      this.$API.get(url).then((res) => {
        console.log(res)
        if(res.data.data == [])
          that.curQuestionsNum = 0
        else
          that.curQuestionsNum = res.data.data
      })
    },
    getcurOnlineNum() {
      let that = this
      let url  = this.$API.getService("User", "getOnlineNum")

      this.$API.get(url).then((res) => {

        that.curOnlineNum = res.data.data
      })
    },
    getReadyToReviewNum() {
      let that = this
      let url  = this.$API.getService("Question", "getTotalNum")

      this.$API.get(url,{
        params:{
          status: 1
        }
      }).then((res) => {
        console.log(res)

        that.readyToReviewNum = res.data.data
      })
    },
    getAdminInfo() {
    },
    initData() {
      this.getAdminInfo()
      this.getCurUserNum()
      this.getcurQuestionsNum()
      this.getcurOnlineNum()
      this.getReadyToReviewNum()
    }
  },
  components: {
    Schart
  },
  mounted() {
    const that  = this;
    var   Timer = setInterval(function() {
      that.curTime = that.getCurTime()
    }, 1000)

    this.initData()
  }
};
</script>

<style scoped>
.login-info p {
  text-align: center;
  margin    : 10px 0;
}
.login-info p.avatar img {
  margin-top   : -15px;
  height       : 60px;
  border-radius: 5px;
}
.login-info p.name {
  font-size    : 18px;
  font-weight  : bold;
  border-radius: 5px;
}
.box-card {
  margin-bottom: 1rem;
}
.box-card .el-card__body {
  padding: 0;
}
.number {
  color    : #ff6655;
  font-size: 50px;
  padding  : 0 8px;
}
.cursor {
  cursor: pointer;
}
.content-title {
  padding    : 0 30px;
  font-weight: 400;
  line-height: 50px;
  margin     : 10px 0;
  font-size  : 24px;
  color      : #1f2f3d;
}
.ms-doc {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial,
    sans-serif;
}
.ms-doc h3 {
  padding         : 9px 10px 10px;
  margin          : 0;
  font-size       : 14px;
  line-height     : 17px;
  background-color: #f5f5f5;
  border          : 1px solid #d8d8d8;
  border-bottom   : 0;
  border-radius   : 3px 3px 0 0;
}
.ms-doc article {
  padding                   : 45px;
  word-wrap                 : break-word;
  background-color          : #fff;
  border                    : 1px solid #ddd;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius : 3px;
}
.ms-doc article h1 {
  font-size     : 32px;
  padding-bottom: 10px;
  margin-bottom : 15px;
  border-bottom : 1px solid #ddd;
}
.ms-doc article h2 {
  margin        : 24px 0 16px;
  font-weight   : 600;
  line-height   : 1.25;
  padding-bottom: 7px;
  font-size     : 24px;
  border-bottom : 1px solid #eee;
}
.ms-doc article p {
  margin-bottom: 15px;
  line-height  : 1.5;
}
.ms-doc article .el-checkbox {
  margin-bottom: 5px;
}
</style>