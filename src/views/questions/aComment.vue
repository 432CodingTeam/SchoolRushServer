<template>
  <div id="a-comment">
    <Row class="a-comment">
      <Col span="15">
        
          <Poptip class="poptip" trigger="hover" placement="right">
            <router-link :to="'/home/' + user.id">
            <Avatar shape="square" :src="user.avatar" />
            <span class="name">{{ user.name }}</span>
            </router-link>
            <div class="api" slot="content">
              <user-card :uid="user.id"></user-card>
            </div>
          </Poptip>
        
      </Col>
      <Col span="8">
        <p class="time">{{ time }}</p>
      </Col>
      <Col class="content" span="24">
        <div class="mask" v-if="collapse"></div>
        
        <p :class="{'collapse': collapse, 'normal': !collapse}">
          <markdown-html :markdown="comment.content"></markdown-html>
        </p>
        <p v-if="collapse" class="collapse-container">
          <Button type="text" @click="changeCollapse" icon="chevron-down">展开</Button>
        </p>
      </Col>
      <Col class="methods" span="24">
        <p>
          <!-- <Button type="info" icon="arrow-up-b">{{ comment.agree }}</Button>
          <Button type="ghost" icon="arrow-down-b"></Button> -->
          <!-- <Button type="text" icon="chatbubble">回复</Button>
          <Button type="text" icon="chatbubbles">查看对话</Button> -->
          <Button type="text" @click="tipoff" icon="information-circled">举报</Button>
          <Button class="collapse" v-if="!collapse" type="text" @click="changeCollapse" icon="chevron-up">收起</Button>
        </p>
      </Col>
    </Row>
    <!-- 举报原因对话框 -->
    <Modal
      v-model="tipoffModalVisiable"
      title="举报原因"
      @on-ok="doTipoff">
      <Input v-model="tipoffReason" type="textarea" placeholder="输入你的举报理由..."></Input>
    </Modal>
  </div>
</template>
<script>
import formatTime from "../components/tools/formatTime.js"
import markdownHtml from "../common/markdown-html"
import userCard from "../components/msgCart"
export default {
  data() {
    let Time = formatTime.getDateDiff(this.comment.time)
    return {
      time: Time,
      collapse: true,
      tipoffModalVisiable: false,
      tipoffReason: "",
      hasAgree: false,
      hasDisAgree: false
    }
  },
  methods: {
    changeCollapse() {
      this.collapse = !this.collapse
    },
    tipoff() {
      this.tipoffModalVisiable = true
    },
    doTipoff() {
      let data = {
        type: 3,
        reason: this.tipoffReason,
        target: this.comment.id
      }
      const url = this.$API.getService("Tipoff", "add")
      this.$API.post(url, data).then(res => {
        if(res.data.data.hasOwnProperty("id")) {
          this.$Message.success("举报成功!")
          return
        }
        this.$Message.error("举报失败!")
      })
    },
    agree() {

    },
    disagree() {

    },
    getAgreeInfo() {
      //获取当前登陆用户对这条评论的支持/反对情况
      
    }
  },
  props: ["user", "comment"],
  components: {
    markdownHtml,
    userCard
  },
  mounted() {
    if(this.comment.content.length < 20) {
      this.collapse = false
    }
  }
}
</script>

<style lang="sass">
#a-comment
  .content
    z-index: 5
    position: relative
  .mask
    position: absolute
    top: 3rem
    height: 4rem
    left: 0
    right: 0
    background: linear-gradient(to top, rgba(255,255,255,1) 30%, rgba(255,255,255,0))
    z-index: 10
  .hide
    display: none
  .collapse
    height: 4rem
    overflow: hidden
  .normal
    padding-bottom: 3.5rem
  .collapse-container
    text-align: center
    margin-top: 1rem
  .methods
    .collapse
      float: right
  .time
    font-size: 1.3rem
  .poptip .ivu-poptip-rel
    left: 0
</style>



