<template>
  <div class="app" id="trd-group-square">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <Row :gutter="gutter" class="content">
          <Col v-for="item in groupList" :key="item.id" :xs="12" :sm="8" :md="8" :lg="8">
            <div class="card card-container" @click="toGroup(item.id)">
              <Col span= "8" class="group-card-left">
                <Avatar :src="item.avatar" shape="circle" size="large"/>
                <p class="members">{{ item.members }} 个成员</p>
              </Col>
              <Col span= "16" class="group-card-right">
                <p class="group-name">{{ dealName(item.name) }}</p>
                <p class="introduce">{{ dealIntroduce(item.introduce) }}</p>
              </Col>
            </div>
          </Col>
          <Col span="24">
            <div v-if="isLoadingMore" class="loading" id="load" ref="load"><Spin fix></Spin></div>
            <div v-else class="tips">没有了</div>
          </Col>
        </Row>
        <!-- 内容部分结束 -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      gutter: 7,
      query: "",
      groupList: [],
      groupListBack: [],
      isLoadingMore: true,
      page: 1,
      pageLength: 20,
    }
  },
  methods: {
    getMajorGroups() {
      let url = this.$API.getService("Group", "getPageByMid")
      let mid  = this.$route.params.id
      this.$API.post(url, {
        mid: mid,
        page: this.page,
        length: this.pageLength
      })
      .then( res => {
        let that = this
        if(res.data.data.length == 0) {
          this.isLoadingMore = false
          return
        }
        res.data.data.map(item => {
          that.groupList.push(item)
          that.groupListBack.push(item)
        })
        this.page++
      })
    },
    toGroup(parentID) {
      this.$router.push("/agroup/" + parentID)
    },
    getElementToPageTop(el) {
      if(el.parentElement) {
        return this.getElementToPageTop(el.parentElement) + el.offsetTop
      }
      return el.offsetTop
    },
    addListener() {
      let load = this.$refs.load
      let that = this
      
      if(window.scrollY + window.innerHeight > that.getElementToPageTop(load) - 20) {
        this.isLoadingMore = false
        return
      }
      window.addEventListener( //用于无限加载功能
        "scroll",
        function() {
          console.log(window.scrollY + window.innerHeight)
          console.log(load.offsetTop);
          
          
          if (
            window.scrollY + window.innerHeight > that.getElementToPageTop(load) - 20
          ) {
            that.getMajorGroups()
          }
        },
        true
      );
    },
    dealIntroduce(text) {
      return this.sub(text, 60)
    },
    dealName(text) {
      return this.sub(text, 21)
    },
    sub(str,n){
      var r=/[^\x00-\xff]/g;
      if(str.replace(r,"mm").length<=n){return str;}
      var m=Math.floor(n/2);
      for(var i=m;i<str.length;i++){
          if(str.substr(0,i).replace(r,"mm").length>=n){
              return str.substr(0,i);
          }
      }
      return str;
    },
    search() {
      let query = this.query
      let res   = []
      for(var i in this.groupListBack) {
        if(this.groupListBack[i].name.indexOf(query) >= 0) {
          res.push(this.groupListBack[i])
        }
      }
      this.groupList = res
    }
  },
  mounted() {
    this.getMajorGroups()
    this.addListener()
  },

}
</script>

<style lang="sass">
#trd-group-square
  .card
    text-align: center
    padding: 3rem 1rem
    margin: .5rem 0
    cursor: pointer
    height: 18rem
    .ivu-avatar-large
      width: 5.5rem
      height: 5.5rem
      border-radius: 0
  .card:hover
    box-shadow: 0 0 .1rem .3rem #ddd
  .tips
    text-align: center
    padding: 3rem 1rem
    margin: .5rem 0
  .ivu-avatar
    margin-right: .7rem
  .group-card-left
    .members
      margin-top: 2.5rem
      font-size: 1.4rem
  .group-card-right
    text-align: left
    font-size: 1.5rem
    .group-name
      font-size: 1.6rem
      word-break: break-all
    .introduce
      margin-top: 1rem
      padding: 1rem 1rem
      background: #ececec
      border-radius: .5rem
      word-break: break-all
  .loading
    height: 4.5rem
    position: relative
</style>
