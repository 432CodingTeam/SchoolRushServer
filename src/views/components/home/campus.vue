<template>
  <div id="com-app">
    <p class="userinfo-school">
      <Col span="24">
        <img class="campus-badge" :src="campus.badge" alt="shufe">
        <Col span="24">
          <Col span="24">
            <p class="campus-name">{{ campus.name }}</p>
          </Col>
          <Col span="24">
            计算机专业 <Tag type="border" color="green">No.5</Tag>
          </Col>
        </Col>
      </Col>
    </p>
    <Col class="campus-chart" span="24">
      <ul class="campus-chart-item">
        <li v-for="item in topMajor" :key="item.majorID">
          <Col class="campus-chart" span="7">{{item.majorName}}</Col>
          <Col class="campus-chart" span="16">
            <Progress :percent="item.pquestionCount" hide-info status="success"></Progress>
          </Col>
        </li>
      </ul>
    </Col>
    <p class="userinfo-user-school">
      <Col span="24">
        <Col span="8">
          <Col class="userhome-data-num" span="24">
            {{ Utils.formatByK(campusPassedTotal) }}
          </Col>
          <Col class="userhome-data-text" span="24">
            总通过数
          </Col>
        </Col>
        <Col span="8">
          <Col class="userhome-data-num" span="24">
            {{ Utils.formatByK(campusRank) }}
          </Col>
          <Col class="userhome-data-text" span="24">
            校园排行
          </Col>
        </Col>
        <Col span="8">
          <Col class="userhome-data-num" span="24">
            {{ campus.members }}
          </Col>
          <Col class="userhome-data-text" span="24">
            成员
          </Col>
        </Col>
      </Col>
    </p>
  </div>
</template>
<script>
import Utils from "../tools/utils.js"
export default {
  data() {
    return {
      Utils: Utils,
      campus: {
        id: "",
        name: "",
        badge: "",
        members: "",
        locate: "",
      },
      campusRank: 0,
      campusPassedTotal: 0,
      topMajor: [{
        majorID: 1,
        majorName: "计算机科学与技术",
        pquestionCount: 78
      },
      {
        majorID: 2,
        majorName: "网络工程",
        pquestionCount: 50
      },
      {
        majorID: 3,
        majorName: "电子信息工程",
        pquestionCount: 40
      },
      {
        majorID: 4,
        majorName: "电子与计算工程",
        pquestionCount: 45
      },{
        majorID: 5,
        majorName: "网络安全",
        pquestionCount: 20
      }],
    }
  },
  methods: {
    getCampusInfo() {
      let that = this
      const url = this.$API.getService("Campus", "getById")

      this.$API.post(url, {id: this.cid})
      .then((res) => {
        that.campus = res.data.data
      })
    },
     getUserCampusRank() {
      let that = this
      let url = this.$API.getService("User", "GetRankAtcampus")
      let id  = localStorage.getItem("uid")

      this.$API.post(url, {
        id: id
      }).then((res) => {
        that.campusRank = res.data.data
      })
    },
    getCampusPassedTotal() {
      let that = this
      let url = this.$API.getService("Campusmajorpassed", "getCampusPassedNum")

      this.$API.post(url, {
        cid: this.cid
      }).then((res) => {
        that.campusPassedTotal = res.data.data
      })
    },
    getTopFive() {
      let that = this
      let url = this.$API.getService("Campusmajorpassed", "GetTopFiveMajor")

      this.$API.post(url, {campusID: this.cid})
      .then(res => {
        let result = res.data.data
        if(result.hasOwnProperty("res")) {
          return
        }
        let total = 0
        for(var i in result) {
          total += parseInt(result[i].pquestionCount)
        }
        console.log(total)
        for(var j in result) {
          result[j].pquestionCount = parseInt((result[j].pquestionCount/total) * 100)
        }
        this.topMajor = result
        console.log(result)
      })
    }
  },
  created() {
    this.getCampusInfo()
    this.getUserCampusRank()
    this.getCampusPassedTotal()
    this.getTopFive()
  },
  props: ["cid"],
  watch: {
    cid(now, old) {
      if(now) {
        this.getCampusInfo()
      }
    },
    campus(now, old) {
    }
  }
}
</script>
<style lang="sass">
#com-app
  p
    text-align: center
  .campus-badge
    border-radius: 50%
    height: 10rem
    box-shadow: 0 0 1rem 0 #ccc
  .campus-name
    font-size: 2.3rem
    font-weight: bold
    line-height: 5rem
  ul.campus-chart-item
    margin-top: .2rem
    li
      font-size: 1.4rem
      line-height: 2.4rem
      padding-right: 1rem
      text-align: right
  .campus-chart
  .campus-chart-item
    padding-top: 4.5rem
  .ivu-progress-outer
    margin-left: 1.5rem
    .ivu-progress-inner
      margin-bottom: .5rem
</style>
