<template>
  <div id="answer">
    <Scroll :height="1000" :on-reach-bottom="getPage">
      <a-answer v-for="item in analysises" :answer="item" :key="item.id"></a-answer>
      <p style="text-align: center" v-if="analysises.length == 0">还没有人回答过噢，来第一个通过吧 ~</p>
    </Scroll>
  </div>
</template>
<script>
import markdownHtml from "../common/markdown-html"
import userCard from "../components/msgCart"
import aAnswer from "./aAnswer"
export default {
  data() {
    return {
      analysises: [],
      agreeBtnType: "",
      page: 1,
      collapse: true,
      collapseIcon: 'chevron-down',
      collapseText: '展开'
    }
  },
  methods: {
    getPage() {
      let url = this.$API.getService("Analysis", "getPageByQid")

      this.$API.post(url, {
        qid: this.qid,
        page: this.page,
        length: 10
      }).then(res => {
        if(this.page == 1)
          this.analysises = res.data.data
        else {
          for(var i in res.data.data) {
            this.analysises.push(res.data.data[i])
          }
        }

        this.page++
      })
    },
  },
  mounted() {
    this.getPage()
  },
  components: {
    markdownHtml,
    userCard,
    aAnswer
  },
  props: ['qid']
}
</script>
<style lang="sass">
#answer
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


