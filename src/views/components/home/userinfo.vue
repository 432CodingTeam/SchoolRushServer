<template>
  <div id="com-app">
    <Col span="24">
      <p class="userinfo-avatar">
        <img class="circle-img" :src="userInfo.avatar" alt="iimT">
        <Icon v-if="isAdmin" @click.native="toSettings" class="settings" type="gear-a"></Icon>
      </p>
      <p class="userinfo-username">{{ userInfo.name }}</p>
    </Col>
    <Col span="24">
      <p class="userinfo-locate-school">
        <Tag type="border" color="green">{{ userInfo.campusInfo.locate }}</Tag>
        <Tag type="border" color="blue">{{ userInfo.campusInfo.name }}</Tag>
      </p>
    </Col>
    <Col class="user-info-followed" span="12">
      <p class="num">{{ follow }}</p>
      <p class="title">关注了</p>
    </Col>
    <Col class="user-info-follower" span="12">
      <p class="num">{{ follower }}</p>
      <p class="title">关注者</p>
    </Col>
    <Col class="answerPie-container" span="12">
      <!-- 擅长领域的饼图 -->
      <div id="answerPie" :style="{width: '230px', height: '200px', margin: '0 auto'}"></div>
    </Col>
    <Col class="answerPie-container" span="12">
      <!-- 其他数据的饼图 -->
      <div id="Pie" :style="{width: '230px', height: '200px', margin: '0 auto'}"></div>
    </Col>
    <p class="userinfo-qinfo">
      <Col span="8">
        <Col class="userhome-data-num" span="24">
          {{ userInfo.solveInfo.passedNum }}
        </Col>
        <Col class="userhome-data-text" span="24">
          解决问题
        </Col>
      </Col>
      <Col span="8">
        <Col class="userhome-data-num" span="24">
          {{ getUserPassRate() }}
        </Col>
        <Col class="userhome-data-text" span="24">
          通过率
        </Col>
      </Col>
      <Col span="8">
          <Col class="userhome-data-num" span="24">
            {{ userInfo.solveInfo.tobeSolvedNum }}
          </Col>
          <Col class="userhome-data-text" span="24">
            待解决
          </Col>
        </Col>
    </p>
  </div>
</template>
<script>
let echarts = require("echarts/lib/echarts")
// 引入柱状图组件
require("echarts/lib/chart/pie")
// 引入提示框和title组件
require("echarts/lib/component/tooltip")
require("echarts/lib/component/title")

export default {
  data() {
    return {
      isAdmin: false,
      follow: 0,
      follower: 0,
      userInfo: {
        avatar: "",
        campusID: "",
        campusInfo: {},
        describe: "",
        email: "",
        gender: "",
        id: "",
        identify: "",
        majorID: "",
        majorInfo: {},
        name: "",
        tel: "",
        vice: "",
        solveInfo: {},
      },
      userFileds: [{name: "暂无", value: 1}],
    }
  },
  methods: {
    getFollowNum() {
      let uid = localStorage.getItem("uid");
      let that = this
      let url = this.$API.getService("Follow", "GetFollowNum")

      this.$API.post(url, {
        uid: uid,
        type: 1
      }).then((res) => {
        console.log("folower")
        console.log(res)
        // if(!res.dat.data) return
        that.follow = parseInt(res.data.data)
      })
    },
    drawAnswerPie() {
      // 绘制个人擅长图表
      let myChart = echarts.init(document.getElementById("answerPie"));
      myChart.setOption({
        title: {
          text: "擅长领域",
          left: 'center',
          textStyle: {
            fontSize: "16",
            fontWeight: "normal"
          }
        },
        series: {
          type: "pie",
          center: ["50%", "55%"],
          radius: ["35%","75%"],
          avoidLabelOverlap: false,
          labelLine: {
            normal: {
              show: false
            }
          },
          label: {
            normal: {
              show: false,
              position: "center",
            formatter: "{b} : {d}%"
            },
            emphasis: {
              show: true,
              textStyle: {
                fontSize: "17",
                textBorderColor: "#fff",
                textBorderWidth: "3",
                textShadowOffsetY: "0",
                textShadowOffsetX: "0",
                textShadowBlur: "15",
                textShadowColor: "#ccc"
              }
            }
          },
          data: this.userFileds,
          itemStyle: {
            //阴影
            normal: {
              shadowBlur: 10,
              shadowColor: "rgba(0, 0, 0, 0.5)",
              color: function (o){
                  let color = ["#2db7f5","#19be6b","#f90", "#2d8cf0","#ed3f14"]
                  return color[o.dataIndex]
              }
            }
          }
        }
      })
    },
    getFollowerNum() {
      let that = this
      let url = this.$API.getService("Follow", "GetFollowerNum")

      this.$API.post(url, {
        uid: this.uid
      }).then((res) => {
        console.log("folower")
        console.log(res)
        that.follower = parseInt(res.data.data)
      })
    },
    drawPie() {
      // 绘制个人擅长图表
      let myChart = echarts.init(document.getElementById("Pie"));
      myChart.setOption({
        title: {
          text: "通过率",
          left: 'center',
          textStyle: {
            fontSize: "16",
            fontWeight: "normal"
          }
        },
        series: {
          type: "pie",
          center: ["50%", "55%"],
          radius: ["35%","75%"],
          avoidLabelOverlap: false,
          labelLine: {
            normal: {
              show: false
            }
          },
          label: {
            normal: {
              show: false,
              position: "center",
            formatter: "{b} : {d}%"
            },
            emphasis: {
              show: true,
              textStyle: {
                fontSize: "18",
                textBorderColor: "#fff",
                textBorderWidth: "3",
                textShadowOffsetY: "0",
                textShadowOffsetX: "0",
                textShadowBlur: "15",
                textShadowColor: "#ccc"
              }
            }
          },
          data: [
            { name: "未通过", value: this.userInfo.solveInfo.tobeSolvedNum },
            { name: "已通过", value: this.userInfo.solveInfo.passedNum, selected: true },
          ],
          itemStyle: {
            //阴影
            normal: {
              shadowBlur: 10,
              shadowColor: "rgba(0, 0, 0, 0.5)",
              color: function (o){
                  let color = ["#2db7f5","#19be6b","#f90", "#2d8cf0","#ed3f14"]
                  return color[o.dataIndex]
              }
            }
          }
        }
      })
    },
    toSettings() {
      this.$router.push("/settings")
    },
    judgeAdmin() {
      let user = JSON.parse(localStorage.getItem("userinfo"))
      if(user.id == this.uid)
        this.isAdmin = true
    },
    getUserGoodFileds() {
      let that = this
      let url = this.$API.getService("User", "GetGoodAtRankTop")

      this.$API.post(url, {
        id: this.userInfo.id
      }).then((res) => {
        console.log(res)
        let data = res.data.data
        if(!data) {
          //画饼图
          that.drawAnswerPie()
          that.drawPie()
          return
        }
        if(data.length != 0) {
          let maxIndex = 0
          for(var d in data) {
            if(data[d].value > data[maxIndex].value) {
              maxIndex = d
            }
          }
          data[maxIndex].selected = true
          that.userFileds = data
        }
        
        //画饼图
        that.drawAnswerPie()
        that.drawPie()
      })
    },
    getUserInfo() {
      let uid = this.uid
      let url = this.$API.getService("User", "getById");
      let that = this;

      this.$API
        .post(url, {
          id: uid
        })
        .then((res) => {
          let Uinfo = res.data.data
          console.log(Uinfo)
          for(var i in Uinfo) {
            that.$set(that.userInfo, i, Uinfo[i])
          }
          //画饼图
          localStorage.setItem("userinfo", JSON.stringify(Uinfo))
          that.getUserGoodFileds()
          that.judgeAdmin()
        })
        .catch(err => {
          console.log(err);
        });
    },
    getUserPassRate() {
      let solved = this.userInfo.solveInfo
      if(solved.tobeSolvedNum + solved.passedNum == 0) return "0%"
      let hund = solved.passedNum/(solved.tobeSolvedNum + solved.passedNum) * 10000
      let intNum = parseInt(hund)
      return intNum/100 + "%"
    },
  },
  mounted() {
    this.getUserInfo()
    this.getFollowNum()
    this.getFollowerNum()
  },
  props: ["uid"],
  watch: {
    uid(now, old) {
      if(now != old) {
        this.getUserInfo()
        this.judgeAdmin()
      }
    }
  }
}
</script>
<style lang="sass">
#com-app
  p
    text-align: center
  .user-info-followed,.user-info-follower
    padding: 1rem 0
    p.title
      font-size: 1.6rem
      border-bottom: .1rem solid #e6e6e6
      padding-bottom: .5rem
    p.num
      font-size: 2.3rem
      font-weight: bold
.school-badge
  border-radius: 50%
.userinfo-avatar
  height: 10rem
  position: relative
  img
    box-shadow: 0 0 1rem 0 #ccc
  .settings
    position: absolute
    padding: 1rem 1.5rem
    right: .5rem
    top: 0
    font-size: 2rem
    cursor: pointer
    border-radius: .5rem
  .settings:hover
    background: #e6e6e6
.answerPie-container
  margin-top: 1rem
.userinfo-username
  font-size: 2.3rem
  font-weight: bold
  line-height: 5rem
.userinfo-qinfo
  display: block
.userhome-data-num
  font-size: 2.3rem
  font-weight: bold
  margin-top: 2rem
.userhome-data-text
  font-size: 1.4rem
#answerPie
  float: right
#Pie
  float: left
</style>

