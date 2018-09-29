<template>
  <div class="app" id="sub-group-square">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <Affix :offset-top="60">
          <Row>
            <Col span="24">
              <Input v-model="query" size="large" @on-change="search" placeholder="你想找什么..."/>
            </Col>
          </Row>
        </Affix>
        <Row :gutter="gutter" class="content">
          <Col v-for="item in majorList" :key="item.id" :xs="12" :sm="8" :md="8" :lg="8">
            <div class="card card-container" @click="toTrdSquare(item.id)">
              {{ item.name }}
              <!-- <router-link class="group-link" :to="'/groups/' + item.id">{{ item.name }}</router-link> -->
            </div>
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
      majorList: [],
      majorListBack: []
    }
  },
  methods: {
    getParentMajor() {
      let url = this.$API.getService("Major", "getByParent")
      let id  = this.$route.params.id
      this.$API.post(url, {id: id})
      .then( res => {
        this.majorList     = res.data.data
        this.majorListBack = res.data.data
      })
    },
    toTrdSquare(parentID) {
      this.$router.push('/groups/' + parentID)
    },
    search() {
      let query = this.query
      let res   = []
      for(var i in this.majorListBack) {
        if(this.majorListBack[i].name.indexOf(query) >= 0) {
          res.push(this.majorListBack[i])
        }
      }
      this.majorList = res
    }
  },
  mounted() {
    this.getParentMajor()
  }
}
</script>

<style lang="sass">
#sub-group-square
  .ivu-input
    width: 100%
  .ivu-input-large
    height: 40px
    padding: 7px 8px
    font-size: 15px
    box-shadow: 0rem .0rem .5rem #777
  .content
    margin-top: .5rem
  .card
    text-align: center
    padding: 3rem 1rem
    margin: .5rem 0
    cursor: pointer
  .card:hover
    background: #62aeff
    color: #f9f9f9
  .ivu-avatar
    margin-right: .7rem
  .group-link
    color: #495060
  .group-link:hover
    color: #f9f9f9
</style>
