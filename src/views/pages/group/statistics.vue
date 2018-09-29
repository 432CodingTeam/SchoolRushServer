<template>
  <div class="app" id="activity">
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9">
          <div class="activity-container card-container">
            统计页面
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
import agroupSidebar from "./groupsidebar"
export default {
  data() {
    return {
        gid: 0,
        aid: 0,
    }
  },
  methods: {
    initData() {
      this.aid = this.$route.params.aid
      this.gid = this.$route.params.gid
    }
  },
  mounted() {
    this.initData()
  },
  components: {
    agroupSidebar
  },
}
</script>

