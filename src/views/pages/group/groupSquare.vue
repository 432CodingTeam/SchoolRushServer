<template>
  <div class="app" id="group-square">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <Row :gutter="gutter">
          <Col v-for="item in majorList" :key="item.id" :xs="12" :sm="8" :md="8" :lg="8">
            <div class="card card-container" @click="toSubSquare(item.id)">
              <Avatar :src="item.pic" shape="square" size="large" />
              {{ item.name }}
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
      gutter : 7,
      piclist: [
        { name: '哲学',   pic:  './public/upload/subject/philosophy.png', },
        { name: '经济学', pic:  './public/upload/subject/ecnomics.png', },
        { name: '法学',   pic:  './public/upload/subject/law.png', },
        { name: '教育学', pic:  './public/upload/subject/education.png', },
        { name: '文学',   pic:  './public/upload/subject/literature.png', },
        { name: '历史学', pic:  './public/upload/subject/history.png', },
        { name: '理学',   pic:  './public/upload/subject/science.png', },
        { name: '工学',   pic:  './public/upload/subject/engineering.png', },
        { name: '农学',   pic:  './public/upload/subject/agronomy.png', },
        { name: '医学',   pic:  './public/upload/subject/medicine.png', },
        { name: '管理学', pic:  './public/upload/subject/management.png', },
        { name: '艺术学', pic:  './public/upload/subject/art.png', },
      ],
      majorList: [],
    }
  },
  methods: {
    getParentMajor() {
      let url = this.$API.getService("Major", "getByParent")
      this.$API.post(url, {id: 0})
      .then( res => {
        let result = res.data.data.map(item => {
          let pic = ''
          for(var i in this.piclist) {
            if(this.piclist[i].name == item.name ) {
              pic = this.piclist[i].pic
              break
            }
          }
          return {
            id  : item.id,
            name: item.name,
            pic : pic
          }
        })
        
        this.majorList = result
      })
    },
    toSubSquare(parentID) {
      this.$router.replace("/groupSquare/" + parentID)
    }
  },
  mounted() {
    this.getParentMajor()
  }
}
</script>

<style lang="sass">
#group-square
  .card
    text-align: center
    padding: 6rem 1rem
    margin: .5rem 0
    cursor: pointer
  .card:hover
    background: #62aeff
    color: #f9f9f9
  .ivu-avatar
    margin-right: .7rem
</style>
