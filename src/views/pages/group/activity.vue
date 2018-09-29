<template>
  <div class="app" id="activity">
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9">
          <div class="activity-container card-container">
            <p class="title">
              <span>{{ activityInfo.title }}</span>
              <span class="title-right time">创建于{{ formatTime.getDateDiff(activityInfo.starttime) }}</span>
              <span class="title-right complete-num">{{ groupPassInfo.pass + "/" + groupPassInfo.total }}人已完成</span>
              <span class="review"><Button type="success" size="small" @click="checkStatistics">查看统计</Button></span>
              <span class="title-right review"><Button type="primary" size="small" @click="goGroup(groupInfo.id)">{{ groupInfo.name }}</Button></span>
            </p>
            <p class="activity-content">
              <markdown-html :markdown="activityInfo.content"></markdown-html>
            </p>
            <p class="condition">你的完成情况 {{ userPassed + "/" + totalNum }} <div class="line"></div></p>
            <ul class="list">
              <li v-for="item in userPassInfo"
                  v-on:mouseover="handelShowSmallTitle(item.id)"
                  :key="item.id"
                  :class="{'complete': item.status}"
                  @click="handelToQuestion(item.id)">
                {{ item.title }}
                <span class="title-small" :class="{'show-small-title': showSmallTitle[item.id]}">{{ "  查看问题" }}</span>
                <Icon class="right" v-if="item.status" type="checkmark-round"></Icon>
              </li>
            </ul>
          </div>
        </div>


        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <agroup-sidebar></agroup-sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
  </div>
</template>
<script>
import markdownHtml from "../../common/markdown-html"
import agroupSidebar from "./groupsidebar"
import formatTime from "../../components/tools/formatTime.js"
export default {
  data() {
    return {
      content: "## 这里是活动介绍\nXXXXX",
      isComplete: true,
      activityInfo: {
        title: "",
        questionsID: "",
        gid: "",
        starttime: "2018-05-25 22:49:08",
        content: "",
      },
      groupPassInfo: {
        pass: 0,
        total: 0
      },
      groupInfo: {
        id: 0,
        name: "",
      },
      userPassInfo: [],
      showSmallTitle: {},
      userPassed: 0,
      totalNum: 0,
      questions: [],
      formatTime: formatTime
    }
  },
  methods: {
    goGroup(id) {
      this.$router.replace("/agroup/" + id)
    },
    getActivityInfo() {
      let url = this.$API.getService("Groupactivity", "getById")
      let aid = this.$route.params.id

      this.$API.post(url,{id: aid}).then(res => {
        this.activityInfo = res.data.data
        this.$set(this.activityInfo, "content", res.data.data.content)
      })
    },
    getGroupPassInfo() {
      let url = this.$API.getService("Groupactivity", "getActivityCompleteInfo")
      let aid = this.$route.params.id

      this.$API.post(url,{id: aid}).then(res => {
        this.groupPassInfo = res.data.data
      })
    },
    getGroupInfo(){
      let url = this.$API.getService("Group", "GetById")

      this.$API.post(url, {id: this.$route.params.gid}).then(res => {
        let result = res.data.data
        this.groupInfo = result
      })
    },
    getUserPassInfo() {
      let url = this.$API.getService("Groupactivity", "getActivityUserPassInfo")
      let aid = this.$route.params.id
      let uid = parseInt(localStorage.getItem("uid"))

      this.$API.post(url,{
        aid: aid,
        uid: uid
      }).then(res => {
        this.userPassInfo = res.data.data
        this.totalNum = this.userPassInfo.length
        let userPassNum = 0
        for(var i in this.userPassInfo) {
          this.showSmallTitle[this.userPassInfo[i].id] = false
          if(this.userPassInfo[i].status)
            userPassNum++
        }
        this.userPassed = userPassNum
      })
    },
    checkStatistics() { //查看统计
      console.log(111)
      this.$router.push("./" + this.$route.params.id + "/statistics")
    },
    handelShowSmallTitle(id) {
      this.$set(this.showSmallTitle, id, true)
    },
    handelToQuestion(id) {
      this.$router.replace("/question/" + id)
    }
  },
  mounted() {
    this.getActivityInfo()
    this.getGroupPassInfo()
    this.getUserPassInfo()
    this.getGroupInfo()
  },
  watch: {
  },
  components: {
    markdownHtml,
    agroupSidebar
  },
}
</script>
<style lang="sass">
#activity
  padding-top: 2rem
  .container
    margin-top: 4rem
  .activity-container
    padding: 2rem
    div.line
      height: .1rem
      width: 100%
      background: #eee
    .title
      .title-right
        float: right
        font-size: 1.4rem
        line-height: 2.2rem
        margin: 0 .5rem
  p.title
    font-size: 2.2rem
    padding-bottom: 1rem
    border-bottom: .1rem solid #e7e7e7
  ul.list
    li
      width: 100%
      margin: 1rem 0
      border: .2rem solid #fff
      background: #2d8cf0
      color: #fff
      padding: 1rem 3rem
      border-radius: 4rem
      cursor: pointer
      i
        line-height: 2.4rem
      a
        color: #fff
    li:hover
      background: #5cadff
    li.complete
      background: #19be6b
    li.complete:hover
      background: #19be6b
    .title-small
      font-size: 1.3rem
      color: #ddd
      display: none
    .show-small-title
      color: #000
      display: inline-block
  .activity-content
    background: #eee
    margin: 1rem 0
    padding: 1rem 2rem
    border-radius: .5rem
</style>

