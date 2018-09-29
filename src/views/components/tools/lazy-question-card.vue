<template>
  <!-- 问题展示卡片组件 -->
  <div class="question-card">
    <div ref="lazy" class="row content-container col-lg-9 col-md-9">
      <div class="card-container">
        <div class="card-left-container flex left">
          <div class="flex-container flex">
            <div class="label-container flex">
              <Tag class="Tag" color="#eee">
              </Tag>
              <Tag class="Tag" color="#eee">
              </Tag>
              <Tag class="Tag" color="#eee">
              </Tag>
            </div>
            <div class="breadcrumb-container flex">
              <p class="lazy-major"></p>
            </div>
          </div>
        </div>
        <div class="card-right-container right">
          <div class="title">
            <p class="animate lazy-title" :class="{'short':short, 'long':long}"></p>
          </div>
          <div class="q-author-say">
            <p class="animate item" :class="{'short':short, 'long':long}"></p>
          </div>
          <div class="q-difficulty">
            <p class="animate item" :class="{'long':short, 'short':long}"></p>
          </div>
          <!--   这里是故意打乱的    -->
          <div class="q-set-info">
            <p class="animate item" :class="{'long':short, 'short':long}"></p>
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
    handelScroll(e) {
      console.log(e);
    },
    startAnimate() {
      let that = this;
      let lazy = this.$refs.lazy;
      window.addEventListener(
        "scroll",
        function() {
          if (
            window.screen.availHeight + window.pageYOffset >
            lazy.offsetTop + 1.5 * lazy.offsetHeight
          ) {
            that.isVisiable = true;
          }
        },
        true
      );
    }
  },
  mounted() {
    this.startAnimate();
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
</style>

