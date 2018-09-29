<template>
  <div class="app" id="group">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9">
          <div class="gruop-activity card-container">
            <p class="title">小组活动
              <Button type="success" size="small" @click="showSetupGroup">创建小组</Button>
              <Button class="square" size="large" type="success" @click="toGroupSquare" ghost>小组广场</Button>
            </p>
            
            <p v-for="item in activityInfo" :key="item.id" class="activity-item">
              <span class="title">
                <router-link :to="'/group/'+ item.group.id +'/activity/' + item.id">{{ item.title }}</router-link>
              </span>
              <span class="right gruop">
                <router-link :to="'/agroup/' + item.gid">{{ item.group.name }}</router-link>
              </span>
              <span class="right time">{{ formatTime.getDateDiff(item.starttime) }}</span>
              <span class="right complete-num">{{ item.pass + "/" + item.total }}人已完成</span>
            </p>
            <p class="page">
              <Page :total="10" @on-change="changePage" size="small"></Page>
            </p>
          </div>
        </div>

        <!-- 创建小组的Modal -->
        <Modal
          v-model="showSetupGroupModal"
          title="创建小组"
          @on-ok="setupGroup"
          @on-cancel="cancelSetup">
          <Form ref="formCustom" class="setup-group-form" :model="groupInfo" :label-width="80">
            <FormItem label="小组名称">
                <Input type="text" v-model="groupInfo.name"></Input>
            </FormItem>
            <FormItem label="小组头像">
              <img style="width: 6rem; margin-left: 1rem" class="avatar" :src="groupInfo.avatar">
              <corp-image @imgData="imgDataChanged"></corp-image>
            </FormItem>
            <FormItem label="所属专业">
              <Select
                  v-model="groupInfo.mid"
                  filterable
                  remote
                  :remote-method="searchMajor"
                  :loading="searchMajorLoading">
                  <Option v-for="item in majorList" :value="item.id" :key="item.id">{{item.name}}</Option>
              </Select>
            </FormItem>
            <FormItem label="小组介绍">
                <Input type="textarea" :rows="5" v-model="groupInfo.introduce" number></Input>
            </FormItem>
          </Form>
        </Modal>
        <!-- 创建小组的Modal end -->

        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <group-sidebar></group-sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
    <!-- 导航条下面内容与侧边栏部分结束 -->
  </div>
</template>
<script>
import groupSidebar from "./group/sidebar"
import corpImage from "../components/tools/corp-image"
import formatTime from "../components/tools/formatTime.js"
export default {
  data() {
    return {
      groupInfo: {
        name: "",
        mid: "",
        avatar: "http://api.iimt.me/public/upload/user_bad959d27568dbb9c4715408db6b131f.png",
        introduce: "",
      },
      majorList: [],
      page: 1,
      pageNum: 15,
      activityInfo: [],
      showSetupGroupModal: false,
      formatTime: formatTime,
      searchMajorLoading: false,
    }
  },
  methods: {
    imgDataChanged(imgData) {
      this.groupInfo.avatar = imgData
    },
    setupGroup() {
      if(this.groupInfo.name == "") {
        this.$Message.info('小组名不能为空');
        return
      }
      if(this.groupInfo.introduce == "") {
        this.$Message.info('小组介绍不能为空');
        return
      }
      if(this.groupInfo.mid == '') {
        this.$Message.info('小组所属专业不能为空');
        return
      }

      let that = this
      let url = this.$API.getService("Group", "add")
      let uid = parseInt(localStorage.getItem("uid"))
      console.log(this.groupInfo.mid)
      
      this.$API.post(url, {
        name: this.groupInfo.name,
        avatar: this.groupInfo.avatar,
        creator: uid,
        mid: this.groupInfo.mid,
        introduce: this.groupInfo.introduce,
      }).then(res => {
        console.log(res)
        
        let result = res.data.data
        if(result.hasOwnProperty("id")) {
          this.$Message.info('创建成功');
          this.$router.push("/agroup/" + result.id)
        } else {
          this.$Message.error('创建失败')
        }
      })
    },
    searchMajor(query) {
      this.searchMajorLoading = true
      let url = this.$API.getService('Major', 'getBylike')

      this.$API.post(url, {query: query})
      .then( res => {
        this.majorList = res.data.data
        this.searchMajorLoading = false
      })
    },
    cancelSetup() {
      return
    },
    changePage(page) {
      console.log("页码"+page)
      this.page = page
    },
    toGroupSquare() {
      this.$router.push("/groupSquare")
    },
    showSetupGroup() {
      this.showSetupGroupModal = true
    },
    getActivityInfo() {
      let url = this.$API.getService("Groupactivity", "getSiteActivityByPage")

      this.$API.post(url, {
        page: this.page,
        num: this.pageNum
      }).then(res => {
        this.activityInfo = res.data.data
      })
    }
  },
  components: {
    groupSidebar,
    corpImage
  },
  mounted() {
    this.getActivityInfo()
  },
  watch: {
    page() {
      this.getActivityInfo()
    }
  }
};
</script>
<style lang="sass">
#group
  .gruop-activity
    padding: 0 .5rem
    p.title
      font-size: 2.5rem
      font-weight: bold
      padding-top: 2rem
      padding-bottom: 1.5rem
      border-bottom: .1rem solid #eee
    .activity-item
      padding: .8rem 0
      font-size: 1.5rem
      border-bottom: .1rem dashed #ececec
      .right
        margin: 0 1rem
    .page
      text-align: center
      padding: 1rem 0
    .square
      float: right
</style>
