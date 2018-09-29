<template>
  <!-- 高校通用页面 -->
  <div class="app campus">
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <div class="row content-container col-lg-9 col-md-9 col-sm-12 col-xs-12">
          <!-- 学校介绍 -->
          <!-- 学校名 - 校徽 - 校园注册人数 - 贡献题数 - 总解决数 -->
          <Card class="campus-info">
            <!-- content -->
            <Row>
              <Col class="left" :xs="24" :sm="8" :md="6" :lg="4">
                <!-- Left - 校徽 -->
                <div class="badge">
                  <img :src="campusInfo.badge" :alt="campusInfo.name">
                </div>
              </Col>
              <Col class="right" :xs="24" :sm="16" :md="18" :lg="20">
                <!-- right - 数据-->
                <h2 class="name">
                  {{ campusInfo.name }}
                  <Tag color="green">{{ campusInfo.locate }}</Tag>
                </h2>
                <p class="con">
                  目前总注册了<span class="members">{{ campusInfo.members }}</span>人，共分享了<span class="shared">{{ campusInfo.shared }}</span>个问题，共解决了<span class="solved">{{ campusInfo.solved }}</span>次问题。
                </p>
              </Col>
            </Row>
          </Card>
          <!-- 学校介绍结束 -->
          <div class="card-container campus-liveness">
            <Tabs>
              <TabPane label="校园动态">
                <campus-live :data="livenessData" @loadMore="loadMore"></campus-live>
                <p class="nomore" v-if="nomore">没有数据了</p>
                <div class="hide" ref="lazy"></div>
              </TabPane>
            </Tabs>
          </div>
        </div>
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <sidebar></sidebar>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import campusLive from "./campus/campusLive"
import majorLive from "./campus/majorLive"
import sidebar from "../common/sidebar"
export default {
  data() {
    return {
      nomore: false,
      page: 1,
      pageNum: 10,
      livenessData: [],
      isLoadingMore: false,
      campusInfo: {},
    }
  },
  methods: {
    getLivenessData() {
      let that = this
      let url = this.$API.getService("Livenesscampus", "getLivenessByCampusId")

      this.$API.post(url, {
        id: this.$route.params.id,
        page: this.page,
        num: this.pageNum
      }).then(res => {
        if(!res.data.data || res.data.data.length == 0) {
          that.nomore = true
        }
        that.livenessData = res.data.data
      })
    },
    getCampusInfo() {
      let cid = this.$route.params.id
      let url = this.$API.getService("Campus", "getById")

      this.$API.post(url, {id: cid})
      .then(res => {
        this.campusInfo = res.data.data
      })
    },
    loadMore() {
      this.isLoadingMore = true
      const that = this;
      const url = this.$API.getService("Livenesscampus", "getLivenessByCampusId");
      this.$API.post(url, {
        id: this.$route.params.id,
        page: ++that.page,
        num: that.pageNum,
      }).then(res => {
        console.log(res.data.data);
        let newData = res.data.data
        if(newData.length == 0) {
          that.nomore = true
          return;
        }
        for(var i=0;i<newData.length;i++){
        　that.livenessData.push(newData[i]);
        }
        newData = null;
        this.isLoadingMore = false
      });
    },
    addScrollListener() {
      let that = this
      window.addEventListener(
        "scroll",
        function() {
          let lazy = that.$refs.lazy;
          if (
            window.screen.availHeight + window.pageYOffset >
            lazy.offsetTop + 1.5 * lazy.offsetHeight
          ) {
            if(that.isLoadingMore) return true
            that.loadMore()
          }
        },
        true
      );
    }
  },
  mounted() {
    this.getLivenessData()
    this.addScrollListener()
    this.getCampusInfo()
  },
  components: {
    campusLive, majorLive,sidebar
  },
  watch: {
    $route(now, old) {
      this.getLivenessData()
      this.addScrollListener()
      this.getCampusInfo()
    }
  }
}
</script>
<style lang="sass">
.campus
  .nomore
    padding: 2rem
    text-align: center
  .campus-liveness
    margin-top: 1rem
    .poptip
      .ivu-poptip-body
        background: #f9f9f9
  .campus-info
    .badge
      border-radius: .5rem
    // .badge:hover
    //   box-shadow: 0rem .0rem .5rem #777
    .left
      border-right: .1rem solid #e9eaec
    .right
      height: 100%
      padding-left: 3rem
      .name
        padding-bottom: 2rem
        .ivu-tag
          font-size: 1.3rem
          font-weight: normal
          margin-left: .5rem
      .con
        padding-top: 2rem
        font-size: 1.5rem
        .members, .solved, .shared
          font-size: 3rem
          font-weight: bold
          padding: 1rem 1.5rem
          margin: 0 .5rem
          padding-bottom: 0
          background: #ccc
          border-radius: .5rem
</style>