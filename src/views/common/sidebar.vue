<template>
  <div class="sidebar">
    <div class="sidebar-item sidebar-userinfo-container">
            <Row type="flex" justify="center" align="middle" class="code-row-bg">
              <Col span="24">
              <p class="userinfo-avatar">
                <Avatar shape="square" size="large" :src="userInfo.avatar" />
              </p>
              <p class="userinfo-username">{{ userInfo.name}}</p>
              </Col>
              <Col span="24">
              <p class="userinfo-locate-school">
                <Tag color="green">{{ userInfo.campusInfo.locate }}</Tag>
                <router-link :to="'/campus/' + userInfo.campusInfo.id">
                  <Tag color="blue">{{ userInfo.campusInfo.name }}</Tag>
                </router-link>
              </p>
              </Col>
              <Col class="user-info-followed" span="12">
              <p class="num"><a :href="'/followed/' + userInfo.id">{{ follow }} </a></p>
              <p class="title">关注了</p>
              </Col>
              <Col class="user-info-follower" span="12">
              <p class="num"><a :href="'/follower/' + userInfo.id">{{ follower }} </a></p>
              <p class="title">关注者</p>
              </Col>
              <Col class="answerPie-container" span="24">
              <!-- 擅长领域的饼图 -->
              <div id="answerPie" :style="{width: '230px', height: '200px'}"></div>
              </Col>
              <p class="userinfo-qinfo">
                <Col span="8">
                <Col class="userinfo-data-num" span="24"> {{ userInfo.solveInfo.passedNum }}
                </Col>
                <Col span="24"> 解决问题
                </Col>
                </Col>
                <Col span="8">
                <Col class="userinfo-data-num" span="24"> {{ getUserPassRate() }}
                </Col>
                <Col span="24"> 通过率
                </Col>
                </Col>
                <Col span="8">
                <Col class="userinfo-data-num" span="24"> {{ userInfo.solveInfo.tobeSolvedNum }}
                </Col>
                <Col span="24"> 待解决
                </Col>
                </Col>
              </p>

            </Row>
          </div>
          <div class="sidebar-item school sidebar-userinfo-container">
            <Row>
              <p class="userinfo-school">
                <Col span="24" class="user-info-school-badge">
                  <Avatar shape="square" size="large" :src="userInfo.campusInfo.badge" />
                <Col span="24">
                <Col span="24">
                <p class="campus-name">{{ userInfo.campusInfo.name }}</p>
                </Col>
                <Col span="24"> {{ userInfo.majorInfo.name }}
                <Tag color="green">No.5</Tag>
                </Col>
                </Col>
                </Col>
              </p>
              <p class="userinfo-user-school">
                <Col span="24">
                <Col span="8">
                <Col class="userinfo-data-num" span="24"> {{ Utils.formatByK(campusPassedTotal) }}
                </Col>
                <Col span="24"> 共通过
                </Col>
                </Col>
                <Col span="8">
                <Col class="userinfo-data-num" span="24"> {{ Utils.formatByK(campusRank) }}
                </Col>
                <Col span="24"> 校园排行
                </Col>
                </Col>
                <Col span="8">
                <Col class="userinfo-data-num" span="24">{{ userInfo.campusInfo.members }}
                </Col>
                <Col span="24"> 成员
                </Col>
                </Col>
                </Col>
              </p>
            </Row>
          </div>
          <div class="sidebar-item sidebar-fastto-container">
              <ul class="fastto">
                <li><p><Icon type="disc"></Icon><router-link to="/challenge">挑战</router-link></p></li>
                <li><p><Icon type="hammer"></Icon><router-link to="/solving">未通过</router-link></p></li>
                <li><p><Icon type="checkmark-circled"></Icon><router-link to="/solved">已通过</router-link></p></li>
                <!-- <li><p><Icon type="pricetags"></Icon>关注的标签</p></li> -->
                <!-- <li><p><Icon type="university"></Icon>关注的学校</p></li> -->
              </ul>
          </div>
          <div class="sidebar-info-container">
            <p>侵权举报网上有害信息举报专区</p>
            <p>违法和不良信息举报：010-454134879</p>
            <p>儿童色情信息举报专区</p>
            <p><router-link to="/bug">更新记录/BUG提交/建议</router-link></p>
            <p>联系我们 © 2018 SchoolRush</p>
          </div>
  </div>
</template>
<script>
import Utils from "../components/tools/utils.js"
let echarts = require("echarts/lib/echarts");
// 引入柱状图组件
require("echarts/lib/chart/pie");
// 引入提示框和title组件
require("echarts/lib/component/tooltip");
require("echarts/lib/component/title");
export default {
  data() {
    return {
      Utils: Utils,
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
      follower: 0,
      follow: 0,
      userFileds: [{name: "暂无",value: 1}],
      campusRank: 0,
      campusPassedTotal: 0,
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
        // if(!res.dat.data) return
        that.follow = parseInt(res.data.data)
      })
    },
    getFollowerNum() {
      let uid = localStorage.getItem("uid");
      let that = this
      let url = this.$API.getService("Follow", "GetFollowerNum")

      this.$API.post(url, {
        uid: uid
      }).then((res) => {
        that.follower = parseInt(res.data.data)
      })
    },
    drawAnswerPie() {
      // 绘制个人擅长图表
      let myChart = echarts.init(document.getElementById("answerPie"));
      myChart.setOption({
        series: {
          type: "pie",
          center: ["50%", "53%"],
          radius: ["45%", "68%"],
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
                textShadowBlur: "5",
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
              color: function(o) {
                let color = [
                  "#2db7f5",
                  "#19be6b",
                  "#f90",
                  "#2d8cf0",
                  "#ed3f14"
                ];
                return color[o.dataIndex];
              }
            }
          }
        },
        title: {
          text: "擅长领域",
          x: "center",
          textStyle: {
            fontSize: 14,
            fontWeight: "normal",
            color: "#000",
            fontFamily:
              "'Microsoft YaHei','serif' , 'monospace', 'Arial', 'Courier New'"
          }
        }
      });
    },
    jumpToSettings() {
      //跳转到设置页面
      this.$Notice.info({
        title: "个人信息不全",
        desc: "请先补全信息，便于筛选你的题目..."
      });

      this.$router.push("./settings");
    },
    getUserInfo() {
      let uid = localStorage.getItem("uid");
      let url = this.$API.getService("User", "getById");
      let that = this;

      this.$API
        .post(url, {
          id: uid
        })
        .then((res) => {
          let Uinfo = res.data.data
          for(var i in Uinfo) {
            that.$set(that.userInfo, i, Uinfo[i])
          }
          //后续的事情
          that.getUserGoodFileds()
          that.getUserCampusRank()
          that.getCampusPassedTotal()
          //end
          localStorage.setItem("userinfo", JSON.stringify(Uinfo));
          for (let i in Uinfo) {
            if (!Uinfo[i]) {
              that.jumpToSettings();
              break;
            }
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    getUserGoodFileds() {
      let that = this
      let url = this.$API.getService("User", "GetGoodAtRankTop")

      this.$API.post(url, {
        id: this.userInfo.id
      }).then((res) => {
        let data = res.data.data
        if(!data || data.hasOwnProperty("msg")) {
          that.drawAnswerPie()
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
      }).catch(err => {
        console.log(err)
      })
    },
    getUserCampusRank() {
      let that = this
      let url = this.$API.getService("User", "GetRankAtcampus")

      this.$API.post(url, {
        id: this.userInfo.id
      }).then((res) => {
        that.campusRank = res.data.data
      })
    },
    getCampusPassedTotal() {
      let that = this
      let url = this.$API.getService("Campusmajorpassed", "getCampusPassedNum")

      this.$API.post(url, {
        cid: this.userInfo.campusInfo.id
      }).then((res) => {
        that.campusPassedTotal = res.data.data
      })
    },
    getUserCampusRank() {
      let that = this
      let url = this.$API.getService("User", "GetRankAtcampus")

      this.$API.post(url, {
        id: this.userInfo.id
      }).then((res) => {
        that.campusRank = res.data.data
      })
    },
    getCampusPassedTotal() {
      let that = this
      let url = this.$API.getService("Campusmajorpassed", "getCampusPassedNum")

      this.$API.post(url, {
        cid: this.userInfo.campusInfo.id
      }).then((res) => {
        that.campusPassedTotal = res.data.data
      })
    },
    getUserPassRate() {
      let solved = this.userInfo.solveInfo
      if(solved.tobeSolvedNum + solved.passedNum == 0) return "0%"
      let hund = solved.passedNum/(solved.tobeSolvedNum + solved.passedNum) * 10000
      let intNum = parseInt(hund)
      return intNum/100 + "%"
    }
  },
  mounted() {
    this.getUserInfo()
    this.getFollowNum()
    this.getFollowerNum()
  }
}
</script>
<style lang="sass">
@import "../../static/sass/grid.sass"
@import "../../static/sass/common.sass"
.ivu-col
  // margin: 0
.sidebar-container
  .sidebar-userinfo-container
    .user-info-followed,.user-info-follower
      padding: 1rem 0
      padding-bottom: 0
      border-bottom: .1rem solid #eee
      p.title
        font-size: 1.6rem
      p.num
        font-size: 2.3rem
        font-weight: bold
      a
        color: #444
    .userinfo-avatar
      .ivu-avatar-large
        width: 7rem
        height: 7rem
        margin-top: 2rem
  .user-info-school-badge
      .ivu-avatar-large
        width: 7rem
        height: 7rem
        margin-bottom: 1rem
.sidebar-container
  float: right
  margin-left: 0
  padding: 0
  margin: 0
  padding-left: .5rem
  position: absolute
  right: 0
  .sidebar-item
    border-radius: .3rem
    box-shadow: 0 0 .3rem 0 #ccc
    background: #fff
.latest-passed
  margin-top: 1rem
  .latest-passed-user
    margin: .5rem
    img
      height: 5rem
.sidebar-userinfo-container
  p
    text-align: center
    img.avatar
      height: 7rem
      margin-bottom: -2.5rem
      margin-top: 1rem
  p.title
    padding: .4rem 0
    border-bottom: .1rem solid #e7e7e7
  p.share-q
    margin: 1rem 0
    margin-top: -.5rem
    span
      font-size: 2.3rem
      font-weight: bold
  .userinfo-data-num
    font-size: 2.3rem
    font-weight: bold
  .userinfo-username
    padding: .5rem 0
    padding-top: 1rem
    font-size: 2rem
    font-weight: bold
  .userinfo-locate-school,.userinfo-qinfo,.userinfo-school,.userinfo-user-school
    padding: .5rem 0
  .userinfo-qinfo
    width: 100%
  .userinfo-school
    padding-top: 1rem
.sidebar-fastto-container
  margin-top: 1rem
.sidebar-info-container
  height: 15rem
  border-radius: .3rem
  margin-top: 1rem
  color: #8590a6
  font-size: 1.4rem
.card-container
  pdding-bottom: 1rem
  border-radius: .3rem
  box-shadow: 0 0 .3rem 0 #ccc
  background: #fff
  overflow: hidden
.card-left-container
  justify-content: center
  flex-direction: column
  position: absolute
  left: 0
  top: 0
  bottom: 0
  width: 25%
  border-right: .1rem solid #e9e9e9
  box-sizing: border-box
  .flex-container
    flex-direction: column
    .label-container
      flex-wrap: wrap
      justify-content: center
      $tag-height: 2.3rem
      .Tag
        height: $tag-height
        line-height: $tag-height
        padding: 0 1rem
        font-size: 1.4rem
    .breadcrumb-container
      padding-top: 3rem
      font-size: 1.6rem
      justify-content: center
      *
        font-size: 1.6rem
      .ivu-breadcrumb-item-separator
        color: #888
.card-right-container
  width: 75%
  padding: 1rem 1.5rem
  p
    padding-top: .5rem
  .title
    font-size: 2.5rem
  .q-set-info
    span
      margin-right: .5rem
  .q-difficulty
  .q-author-say
    .author-say
      font-size: 1.3rem
      background: #ececec
      border-radius: .3rem
      padding: .3rem 0
.answerPie-container
  #answerPie
    margin: 0 auto
@media (max-width: 992px)
  .sidebar-container
    display: none
</style>
