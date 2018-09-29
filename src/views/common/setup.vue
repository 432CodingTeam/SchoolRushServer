<template>
  <div class="app" id="setup">

    <div class="container grid-container">
      <div class="grid-no-padding col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <div class="row content-container col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card-container">
            <Tabs value="select">
              <!-- <TabPane label="选择题" name="select">
                <v-select @onAddLabel="handelAddLabel" @submitQ="handelSubmit" :major-data="majorData" :label-data="labelData"></v-select>
              </TabPane>
              <TabPane label="判断题" name="judge">
                <v-judge @onAddLabel="handelAddLabel" @submitQ="handelSubmit" :major-data="majorData" :label-data="labelData"></v-judge>
              </TabPane>
              <TabPane label="填空题" name="blank">
                <v-blank @onAddLabel="handelAddLabel" @submitQ="handelSubmit" :major-data="majorData" :label-data="labelData"></v-blank>
              </TabPane> -->
              <TabPane label="设计问题" name="edit">
                <edit-q @submitQ="handelSubmit"></edit-q>
              </TabPane>
            </Tabs>
          </div>
        </div>
        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <!-- <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <sidebar></sidebar>
        </div> -->
        <!-- 右侧边栏结束 -->
      </div>
    </div>
  </div>
</template>
<script>
import vSelect from "../questions/select"
import vJudge from "../questions/judge"
import vBlank from "../questions/blank"
import editQ from "../questions/editQuestion"
import utils from "../components/tools/utils"
import sidebar from "../common/sidebar"
export default {
  data() {
    return {
    };
  },
  components: {
    vSelect,
    vJudge,
    vBlank,
    sidebar,
    editQ,
  },
  methods: {
    handelSubmit(question) {
      let Question = utils.copy(question);
      //添加问题
      let uid = parseInt(localStorage.getItem("uid"));

      Question.uid = uid;

      //上传到服务器
      const that = this;
      const url = this.$API.getService("Question", "add");

      this.$API
        .post(url, Question)
        .then(res => {
          let result = res.data.data
          console.log(result)
          that.$router.replace({path:'/submitSuccess',query:{id:result.id, title:result.title}})
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  mounted() {
  },
};
</script>
<style lang="sass">
#setup
  .card-container
    padding: 2rem 3rem
</style>
