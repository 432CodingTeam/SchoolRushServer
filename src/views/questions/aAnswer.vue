<template>
  <div id="answer">
    <Row>
      <Col class="user" span="3">
        <p>
          <Poptip class="poptip" trigger="hover" placement="bottom-start">
            <Avatar :src="answer.avatar" size="large" />

            <div class="api" slot="content">
              <user-card :uid="answer.uid"></user-card>
            </div>
          </Poptip>
        </p>
        <p class="name">
          <router-link :to="'/home/' + answer.uid">{{ answer.name }}</router-link>
        </p>

        <p class="agree">
          <Button :type="agreeBtnType" @click="handelAgree">支持 {{ answer.agree }}</Button>
        </p>
      </Col>
      <Col span="20">
        <div class="mask" v-if="collapse"></div>
        <p class="q-content" :class="{'collapse': collapse, 'normal': !collapse}">
          <markdown-html :markdown="answer.content"></markdown-html>
        </p>
        <p class="collapse-container">
          <Button type="text" @click="changeCollapse" :icon="collapseIcon">{{ collapseText }}</Button>
        </p>
      </Col>
    </Row>
  </div>
</template>
<script>
import markdownHtml from "../common/markdown-html"
import userCard from "../components/msgCart"
export default {
  data() {
    return {
      agreeBtnType: "default",
      page: 1,
      collapse: true,
      collapseIcon: 'chevron-down',
      collapseText: '展开'
    }
  },
  methods: {
    handelAgree() {
      if(this.agreeBtnType == 'info') {
        //取消支持
        this.agreeBtnType = "default"
        let url = this.$API.getService("Analysis", "cancelAgreeById")

        this.$API.post(url, {
          id: this.answer.id,
          uid: this.answer.uid
        }).then(res => {
          this.answer.agree--
        })
        return
      }
      //支持
      let url = this.$API.getService("Analysis", "addAgreeById")

        this.$API.post(url, {
          id: this.answer.id,
          uid: this.answer.uid
        }).then(res => {
          this.answer.agree++
        })
      this.agreeBtnType = 'info'
    },
    changeCollapse() {
      if(this.collapse) {
        this.collapseIcon = 'chevron-up'
        this.collapseText = '收起'
      } else {
        this.collapseIcon = 'chevron-down'
        this.collapseText = '展开'
      }
      this.collapse = !this.collapse
    },
    hasAgree() {
      let uid = parseInt(localStorage.getItem('uid'))
      let aid = this.answer.id
      let url = this.$API.getService("Analysis", "hasAgree")

      this.$API.post(url, {
        aid: aid,
        uid: uid
      }).then(res => {
        console.log(res)
        
        if(res.data.data) this.agreeBtnType = 'info'
        console.log(this.agreeBtnType);
        
      })
    }
  },
  mounted() {
    this.collapse = true
    this.hasAgree()
  },
  components: {
    markdownHtml,
    userCard
  },
  props: ['answer']
}
</script>
<style lang="sass">
#answer
  border-bottom: .1rem solid #e9e9e9
  margin-top: .5rem
  padding-bottom: .5rem
  margin-bottom: 2rem
  $maskHeight: 16rem
  .poptip
    .ivu-poptip-body
      background: #f9f9f9
  .user
    .ivu-avatar-large
      margin-left: -4rem
    p
      text-align: center
    .name
      font-size: 1.6rem
      margin-top: 1rem
    .agree
      margin-top: 3rem
  .mask
    position: absolute
    top: 3rem
    height: $maskHeight
    left: 0
    right: 0
    background: linear-gradient(to top, rgba(255,255,255,1) 30%, rgba(255,255,255,0))
    z-index: 10
  .hide
    display: none
  .collapse
    height: $maskHeight
    overflow: hidden
  .normal
    padding-bottom: 3.5rem
  .collapse-container
    text-align: center
    margin-top: 1rem
</style>


