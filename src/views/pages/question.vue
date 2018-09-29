<template>
  <!-- 题目通用页面 -->
  <div class="app question">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9 col-sm-12 col-xs-12">
          <div v-if="found" class="card-container">
            <a-select :question="question" @onTipoff="handelTipoff" @onAnswer="handelAnswer" :handeling="handeling" v-if="question.type==1"></a-select>
            <a-judge :question="question" @onTipoff="handelTipoff" @onAnswer="handelAnswer" :handeling="handeling" v-else-if="question.type==2"></a-judge>
            <a-blank :question="question" @onTipoff="handelTipoff" @onAnswer="handelAnswer" :handeling="handeling" v-else-if="question.type==3"></a-blank>
            <p v-if="isPassed" class="mark">
              <!-- 评分 -->
              您认为这道题的难度如何：
              <Rate allow-half show-text :disabled="hasMarked" @on-change="mark" v-model="markRate" >
                <span style="color: #f5a623; font-size: 14px"> {{ thanksMark }}</span>
              </Rate>
            </p>
          </div>
          <div v-if="found" class="card-container comment-container">
            <Tabs>
              <TabPane label="回答">
                <answers :qid="question.id"></answers>
              </TabPane>
              <TabPane label="评论">
                <comment :qid="$route.params.id"></comment>
              </TabPane>
            </Tabs>
          </div>
          <!-- 举报原因对话框 -->
          <Modal
            v-model="tipoffModalVisiable"
            title="举报原因"
            @on-ok="doTipoff">
            <Input v-model="tipoffReason" type="textarea" placeholder="输入你的举报理由..."></Input>
          </Modal>
        </div>
        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <sidebar></sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
  </div>
</template>
<script>
//引入答题组件
import answerSelect from "../questions/answerSelect"
import answerJudge from "../questions/answerJudge"
import answerBlank from "../questions/answerBlank"
import comment from "../questions/comments"
import sidebar from "../common/sidebar"
import answers from "../questions/answer"
import notFound from "./404"

export default {
  data() {
    return {
      question: {},
      found: false,
      handeling: false,
      isPassed: false,
      tipoffModalVisiable: false,
      tipoffReason: "",
      markRate: 0,
      hasMarked: false,
      thanksMark: ""
    };
  },
  methods: {
    handelAnswer(data) {
      //当子组件传过来用户提交题目时
      let action = "PassedQuestion"
      if(!data.result) {
        //用户回答错误
        action = "SolveQuestion"
      }
      this.isPassed = true
      delete data["result"] //删除一个键 轻量化传入后台的数据

      const that = this
      const url = this.$API.getService("Usertoq", action)

      this.handeling = true
      this.$API.post(url, data).then((res) => {
        that.handeling = false
      })
    },
    handelTipoff() {
      this.tipoffModalVisiable = true
    },
    doTipoff(){
      let id = this.$route.params.id
      console.log(id + "被举报，理由为：" + this.tipoffReason)
      let data = {
        type: 2,
        reason: this.tipoffReason,
        target: id,
      }
      const url = this.$API.getService("Tipoff", "add")

      this.$API.post(url, data).then(res => {
        if(res.data.data.hasOwnProperty("id")) {
          this.$Message.success('举报成功!')
          this.tipoffReason = ""
          return
        }
        this.$Message.error('举报失败!')
      })
    },
    getQuestion() {
      let id = this.$route.params.id
      const that = this
      const url = this.$API.getService("Question", "getById")

      this.$API.post(url,{id: id})
      .then((res) => {
        if(res.data.data.res == false) {
          that.$router.push("/404")
          return
        }
        that.found = true
        that.question = res.data.data
      })
    },
    isUserPassed() {
      let uid     = localStorage.getItem("uid")
      let qid     = this.$route.params.id
      const that  = this
      const url   = this.$API.getService("Usertoq", "isPassed")

      this.$API.post(url, {uid: uid, qid: qid})
      .then((res) => {
        
        that.isPassed = res.data.data
      })
    },
    getMarkedInfo() {
      let url = this.$API.getService("UserMark", "hasMarked")
      let uid = parseInt(localStorage.getItem("uid"))
      let qid = this.$route.params.id

      this.$API.post(url, {
        uid: uid,
        qid: qid
      }).then(res => {
        this.hasMarked = res.data.data
        
        if(this.hasMarked) {
          this.thanksMark = "你已打分~"
          this.markRate   = this.question.levels
          
        }
      })
    },
    mark(v) {
      
      let url = this.$API.getService("UserMark", "add")
      let uid = parseInt(localStorage.getItem("uid"))
      let qid = this.$route.params.id

      this.$API.post(url, {
        uid: uid,
        qid: qid,
        mark: v
      }).then(res => {
        this.thanksMark = "感谢打分~"
        this.hasMarked = true
      })
    }
  },
  mounted() {
    //获取题目类型
    this.getQuestion()
    //this.isUserPassed()
    this.getMarkedInfo()
  },
  components: {
    aSelect: answerSelect,
    aJudge: answerJudge,
    aBlank: answerBlank,
    Comment: comment,
    sidebar,
    notFound,
    answers,
  }
};
</script>
<style lang="sass">
.question
  .q-content
    background: #e6e6e6
    padding: 2rem
    margin-top: 1rem
    border-radius: .5rem
  .comment-container
    padding-bottom: 0rem
  .mark
    padding: 2rem 0
    text-align: center
    .ivu-rate
      margin-top: -.4rem
$bright-blue: #0084ff
.container
  margin: 0 auto
.content-container
  float: left
  padding: 0
  margin: 0
  padding-right: .5rem
  margin-bottom: 1rem
.card-container
  pdding-bottom: 1rem
  border-radius: .3rem
  box-shadow: 0 0 .3rem 0 #ccc
  background: #fff
  overflow: hidden
  .editor
    z-index: 5
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
        border-radius: .1rem
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
      padding: .3rem .5rem
.Img
  vertical-align: top
.img-auto-fit
  margin-top: .2rem
  height: 90%
.Input
  width: 100%
.school-badge
  width: 5rem
  border-radius: 50%
.campus-name
  font-size: 1.8rem
  font-weight: bold
.answerPie-container
  padding-top: .5rem
  #answerPie
    margin: 0 auto
</style>