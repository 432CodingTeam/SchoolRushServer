<template>
  <div class="app" id="agroup">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-9 col-md-9">
          <!-- 发起活动 -->
          <Form ref="formCustom" class="setup-group-form" :model="activityInfo" :label-width="80">
            <FormItem label="活动名称">
              <Input type="text" size="large" v-model="activityInfo.title"></Input>
            </FormItem>
            <FormItem label="活动介绍">
              <mavon-editor class="editor" placeholder="活动内容..." :toolbars="toolbars" ref=md @imgAdd="$imgAdd" @change="updateMD"></mavon-editor>
            </FormItem>
            <FormItem :label="'选择题集('+ activityInfo.questionsID.length +')'">
              <Select
                placeholder="可搜索"
                v-model="activityInfo.questionsID"
                filterable
                remote
                :remote-method="searchQuestions"
                :loading="searchLoading"
                size="large"
                multiple>
                <Option v-for="item in questions" :value="item.id" :key="item.id">{{item.title}}</Option>
              </Select>
            </FormItem>
            <FormItem >
              <Button size="large" type="primary" @click="setupActivity">发起</Button>
              <Button size="large" type="ghost" @click="cancelSetup" style="margin-left: 8px">取消</Button>
            </FormItem>
          </Form>
          <!-- 发起活动 end -->
        </div>
        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <agroup-sidebar :gid="gid"></agroup-sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
    <!-- 导航条下面内容与侧边栏部分结束 -->
  </div>
</template>
<script>
import { mavonEditor } from "mavon-editor";
import "mavon-editor/dist/css/index.css";
import agroupSidebar from "./groupsidebar"
import markdownHtml from "../../common/markdown-html"
import formatTime from "../../components/tools/formatTime.js"
export default {
  data() {
    return {
      searchLoading: false,
      activityInfo: {
        title: "",
        questionsID: [],
        content: "",
      },
      formatTime: formatTime,
      gid: this.$route.params.gid,
      groupActivity: [],
      page: 1,
      pageNum: 15,
      questions: [],
      toolbars: require("../../../static/data/mavonEditorTools.js"),
      loading: true,
    }
  },
  methods: {
    $imgAdd(pos, $file) {
      // 将图片上传到服务器.
      const url = this.$API.getService("Upload", "base64UploadUPY");
      this.$API
        .post(url, { img: $file.miniurl, name: $file.name })
        .then(url => {
          let res = url.data.data;
          if (res.code == 0) {
            console.log("上传失败！");
            return;
          }
          this.$refs.md.$img2Url(pos, res);
        });
    },
    updateMD(value, render) {
      this.activityInfo.content = value
    },
    searchQuestions(query) {
      console.log(query)
      this.searchLoading = true
      let that = this
      let url = this.$API.getService("Question", "searchSimple")

      this.$API.post(url, {
        key: query,
        num: 20
      }).then(res => {
        that.questions = res.data.data
        that.searchLoading = false
      })
    },
    changePage(page) {
      console.log("页码" + page)
      this.pate = page
    },
    setupActivity() {
      let that = this
      let url  = this.$API.getService("Groupactivity", "add")

      let questionsID = ""
      let first_flag = 0
      let activityInfo = this.activityInfo
      activityInfo.gid = this.$route.params.gid

      for(var i in activityInfo.questionsID) {
        if(!first_flag) {
          questionsID = activityInfo.questionsID[i]
          first_flag = 1
        }
        questionsID = "," + activityInfo.questionsID[i]
      }

      this.$API.post(url, activityInfo).then(res => {
        let result = res.data.data
        if(result.hasOwnProperty("id")) {
          this.$Message.success('发起成功!')
          this.$router.replace("/group/" + this.$route.params.gid + "/activity/" + result.id)
        } else {
          this.$Message.error('发起失败!');
        }
      })
    },
    cancelSetup() {
      return this.$router.go(-1)
    },
  },
  components: {
    agroupSidebar,markdownHtml,mavonEditor
  },
  mounted() {
    console.log(require("../../../static/data/mavonEditorTools.js"));
    
  }
};
</script>
<style lang="sass">
#agroup
  .gruop-activity
    padding: 0 .5rem
    .gruop-info
      padding: 1rem 0
      margin-bottom: 1rem
      border-bottom: .1rem solid #fff
      .ivu-avatar-large
        width: 5rem
        height: 5rem
      span.name
        padding-left: 1rem
        font-size: 2rem
        font-weight: bold
      .is-memeber
        // margin-top: 2rem
        font-size: 1.2rem
        padding-left: 2rem
        button
          margin-left: .5rem
      .right
        margin: 2rem 1rem
        font-size: 1.4rem
    p.title
      font-size: 1.5rem
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
    .introduce-container
      margin-top: .5rem
      border-radius: .5rem
      background: #eee
      padding: 2rem
  .tips
    background: #eee
    padding: 2rem 1rem
    border-radius: .5rem
</style>
