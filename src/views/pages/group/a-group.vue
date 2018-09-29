<template>
  <div class="app" id="agroup">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9">
          <div class="gruop-activity card-container">
            <div class="gruop-info">
              <p>
                <Avatar shape="square" size="large" icon="person" :src="groupInfo.avatar" />
                <span class="name">{{ groupInfo.name }}</span>
                <span class="is-memeber" v-if="isMember">我是这个小组的成员  <Button @click="quit" type="ghost">退出小组</Button></span>
                <span class="is-memeber" v-else><Button @click="join" type="primary">加入小组</Button></span>
                <span class="right">组长 <router-link :to="'/home/' + groupInfo.createrInfo.id">{{ groupInfo.createrInfo.name }}</router-link></span>
                <span class="right">创建于{{ formatTime.getDateDiff(groupInfo.createtime) }}</span>

                <Modal
                  v-model="isJoin"
                  title="确认加入该小组吗？"
                  :loading="loading"
                  @on-ok="joinOK">
                </Modal>
                <Modal
                  v-model="isQuit"
                  title="确认退出该小组吗"
                  :loading="loading"
                  @on-ok="quitOK">
                </Modal>
              </p>
            </div>
            <div class="introduce-container">
              <p>{{ groupInfo.introduce }}</p>
              <markdown-html markdown=""></markdown-html>
            </div>
            <p class="title">最近活动 <Button type="success" size="small" @click="showSetupActivity">发起活动</Button></p>
            <p v-if="groupActivity.length == 0" class="tips">暂无活动噢，快来发起一个吧~</p>
            <p v-for="item in groupActivity" :key="item.id" class="activity-item">
              <span class="title">
                <router-link :to="'/group/' + gid+'/activity/' + item.id">{{ item.title }}</router-link>
              </span>
              <!-- <span class="right gruop">
                <router-link to=""></router-link>
              </span> -->
              <span class="right time">{{ formatTime.getDateDiff(item.starttime) }}</span>
              <span class="right complete-num">{{ item.passeduserNum }} 完成</span>
              <span class="right">{{ item.questionNum }} 题</span>
            </p>
            <p class="page">
              <Page :total="1" @on-change="changePage" size="small"></Page>
            </p>
          </div>
        </div>


        <!-- 发起活动的Modal -->
        <Modal
          v-model="showSetupActivityModal"
          title="发起活动"
          @on-ok="setupActivity"
          @on-cancel="cancelSetup">
          <Form ref="formCustom" class="setup-group-form" :model="activityInfo" :label-width="80">
            <FormItem label="活动名称">
              <Input type="text" v-model="activityInfo.title"></Input>
            </FormItem>
            <FormItem :label="'选择题集('+ activityInfo.questionsID.length +')'">
              <Select
                placeholder="可搜索"
                v-model="activityInfo.questionsID"
                filterable
                remote
                :remote-method="searchQuestions"
                :loading="searchLoading"
                multiple>
                <Option v-for="item in questions" :value="item.id" :key="item.id">{{item.title}}</Option>
              </Select>
            </FormItem>
            <FormItem label="活动介绍" prop="age">
                <Input type="textarea" :rows="5" v-model="activityInfo.content" placeholder="在这里输入活动介绍..." number></Input>
            </FormItem>
          </Form>
        </Modal>
        <!-- 发起活动的Modal end -->
        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <agroup-sidebar :gid="gid"></agroup-sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
    <!-- 导航条下面内容与侧边栏部分结束 -->
  </div>
</template>
<script>
import agroupSidebar from "./groupsidebar"
import markdownHtml from "../../common/markdown-html"
import formatTime from "../../components/tools/formatTime.js"
export default {
  data() {
    return {
      searchLoading: false,
      activityInfo: {
        title: "",
        questionsID: [],
        content: "",
      },
      formatTime: formatTime,
      groupInfo: {
        avatar: "",
        createrInfo: {
          id: "",
          name: "",
        },
        createtime: "",
        creator: "",
        id: "",
        introduce: "",
        members: "",
        name: "",
      },
      gid: this.$route.params.id,
      groupActivity: [],
      page: 1,
      pageNum: 15,
      questions: [],
      showSetupActivityModal: false,
      isJoin: false,
      isQuit: false,
      isMember: true,
      loading: true,
    }
  },
  methods: {
    searchQuestions(query) {
      console.log(query)
      this.searchLoading = true
      let that = this
      let url = this.$API.getService("Question", "searchSimple")

      this.$API.post(url, {
        key: query,
        num: 20
      }).then(res => {
        that.questions = res.data.data
        that.searchLoading = false
      })
    },
    changePage(page) {
      console.log("页码" + page)
      this.pate = page
    },
    setupActivity() {
      let that = this
      let url  = this.$API.getService("Groupactivity", "add")

      let questionsID = ""
      let first_flag = 0
      let activityInfo = this.activityInfo
      activityInfo.gid = this.$route.params.id

      for(var i in activityInfo.questionsID) {
        if(!first_flag) {
          questionsID = activityInfo.questionsID[i]
          first_flag = 1
        }
        questionsID = "," + activityInfo.questionsID[i]
      }

      this.$API.post(url, activityInfo).then(res => {
        let result = res.data.data
        if(result.hasOwnProperty("id")) {
          this.$Message.success('发起成功!')
          this.$router.replace("/group/" + this.$route.params.gid + "/activity/" + result.id)
        } else {
          this.$Message.error('发起失败!');
        }
      })
    },
    cancelSetup() {
      return
    },
    hasJoin() {
      //判断用户是否已加入该小组
      let url = this.$API.getService("Usertogroup", "Inquery")
      let uid = parseInt(localStorage.getItem("uid"))

      this.$API.post(url, {
        uid: uid,
        gid: this.$route.params.id
      }).then(res => {
        this.isMember = res.data.data
      }) 
    },
    join() {
      this.isJoin = true
    },
    quit() {
      this.isQuit = true
    },
    joinOK() {
      let url = this.$API.getService("Usertogroup", "Add")
      let uid = parseInt(localStorage.getItem("uid"))

      this.$API.post(url, {
        uid: uid,
        gid: this.$route.params.id
      }).then(res => {
        let result = res.data.data
        this.isJoin = false
        if(result.hasOwnProperty("id")) {
          this.$Message.success('加入成功!')
          this.isMember = true
        } else {
          this.$Message.error('加入失败!')
        }
      })
    },
    showSetupActivity() {
      //this.showSetupActivityModal = true
      this.$router.push("./"+ this.gid +"/publishActivity")
    },
    quitOK() {
      let url = this.$API.getService("Usertogroup", "Delete")
      let user = JSON.parse(localStorage.getItem("userinfo"))

      this.$API.post(url, {
        uid: user.id,
        gid: this.$route.params.id
      }).then(res => {
        let result = res.data.data
        this.isQuit = false
        console.log(result);
        
        if(result) {
          this.isMember = false
          this.$Message.success('退出成功!')
          
        } else {
          this.$Message.error('退出失败!')
        }
      })
    },
    getGroupInfo() {
      let url = this.$API.getService("Group", "GetById")

      this.$API.post(url, {
        id: this.$route.params.id
      }).then(res => {
        let result = res.data.data
        if(result.hasOwnProperty("id")) {
          this.groupInfo = result
          return
        } else {
          this.$router.push("/404")
        }
      })
    },
    getGroupActivity() {
      let url = this.$API.getService("Groupactivity","GetGroupActivityPageById")

      this.$API.post(url, {
        gid: this.$route.params.id,
        page: this.page,
        num: this.pageNum,
      }).then(res => {
        let result = res.data.data
        this.groupActivity = result
      })
    },
  },
  components: {
    agroupSidebar,markdownHtml
  },
  mounted() {
    this.hasJoin()
    this.getGroupInfo()
    this.getGroupActivity()
  }
};
</script>
<style lang="sass">
#agroup
  .gruop-activity
    padding: 0 .5rem
    .gruop-info
      padding: 1rem 0
      margin-bottom: 1rem
      border-bottom: .1rem solid #fff
      .ivu-avatar-large
        width: 5rem
        height: 5rem
      span.name
        padding-left: 1rem
        font-size: 2rem
        font-weight: bold
      .is-memeber
        // margin-top: 2rem
        font-size: 1.2rem
        padding-left: 2rem
        button
          margin-left: .5rem
      .right
        margin: 2rem 1rem
        font-size: 1.4rem
    p.title
      font-size: 1.5rem
      font-weight: bold
      padding-top: 2rem
      padding-bottom: 1.5rem
      border-bottom: .1rem solid #eee
    .activity-item
      padding: .8rem 0
      font-size: 1.5rem
      border-bottom: .1rem dashed #ececec
      .right
        margin: 0 1rem
    .page
      text-align: center
      padding: 1rem 0
    .introduce-container
      margin-top: .5rem
      border-radius: .5rem
      background: #eee
      padding: 2rem
  .tips
    background: #eee
    padding: 2rem 1rem
    border-radius: .5rem
</style>
