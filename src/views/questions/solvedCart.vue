<template>
  <!-- 已解决问题展示卡片组件 -->
  <div class="question-card">
    <div ref="lazy" class="row content-container col-lg-9 col-md-9">
      <div class="card-container">
        <div class="questionPanel">
            <div id="titlePanel"><h3>这道题很简单</h3></div>
            <div id="questionContent">
                <p>没啥，</p>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      short: false,
      long: true,
      timer: {},
      isVisiable: false
    };
  },
  methods: {
    
  },
  watch: {
    isVisiable(now, old) {
      if (!old && now) {
        clearInterval(this.timer)
        this.timer = setInterval(() => {
          this.short = !this.short
          this.long  = !this.long
        }, 500);
        setTimeout(() => {
          this.$emit("onLoading")
        }, 1500)
      }
    },
    loaded(now, old){
      if(!now) return
      if (!old && now) {
        this.isVisiable = false
        clearInterval(this.timer)
        //this.loaded = false
      }
    }
  },
  props: ["loaded"]
};
</script>
<style lang="sass">
.question-card
  .ivu-tag
    .ivu-tag-text
      a
        color: #fff
  .short
    width: 2rem
    background: #eee
    height: 1.7rem
    
  .long
    width: 100%
    background: #eee
    height: 1.7rem
    
  .item
    margin-top: .8rem
  .title
    .long
      height: 3rem
    .short
      height: 3rem
      width: 50%
  .q-author-say
    .long
      width: 60%
    .short
      width: 40%
  .breadcrumb-container
    .lazy-major
      width: 80%
      height: 2rem
      background: #eee
  .q-difficulty
    .long
      width: 35%
    .short
      width: 20%
  .q-set-info
    .long
      width: 85%
    .short
      width: 30%
  .animate
    transition: width .5s
    -moz-transition: width .5s
    -webkit-transition: width .5s
    -o-transition: width .5s
.question-card   
    .questionPanel
        padding: 1rem
    #questionContent
        overflow: hidden
        text-overflow: ellipsis
        white-space: nowrap
</style>

