<template>
  <div class="app index">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <!-- 内容部分开始 -->
        <!-- 筛选模块 弃用 <div class="row content-container col-lg-9 col-md-9">
          <div class="card-container index-filter">
            <Row :gutter="16">
              <Col :lg="7" :md="7" :sm="7" :xs="15">
                <RadioGroup v-model="filterType" type="button" style="width:100%">
                  <Radio label="选择"></Radio>
                  <Radio label="判断"></Radio>
                  <Radio label="填空"></Radio>
                </RadioGroup>
              </Col>
              <Col :lg="10" :md="10" :sm="11" :xs="15">
                <Select v-model="filterMajor" filterable>
                  <Option v-for="item in majorData" :value="item.value" :key="item.label">{{ item.label }}</Option>
                </Select>
              </Col>
              <Col :lg="7" :md="7" :sm="6" :xs="15">
                <span>难度</span>
                <Rate allow-half v-model="filterLevel"></Rate>
              </Col>
            </Row>
          </div
        </div>  -->
        <question-card v-for="item in questions" :question-info="item" :key="item.id"></question-card>
        <div v-if="nomore" class="question-card">
          <div ref="lazy" class="row content-container col-lg-9 col-md-9">
            <div class="card-container">
              <p class="nomore-data">没有数据了噢</p>
            </div>
          </div>
        </div>
        <lazy-card v-if="!nomore" @onLoading="handleReachBottom" :loaded="isLoading"></lazy-card>
        
        <!-- 内容部分结束 -->
        <!-- 右侧边栏开始 -->
        <div class="row sidebar-container col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <sidebar></sidebar>
        </div>
        <!-- 右侧边栏结束 -->
      </div>
    </div>
    <!-- 导航条下面内容与侧边栏部分结束 -->
  </div>
</template>

<script>
import questionCard from "./question-card.vue";
import sidebar from "../common/sidebar";
import lazyCard from "../components/tools/lazy-question-card"
export default {
  data() {
    return {
      tagColors: ["blue", "red", "yellow", "green"],
      filterType: 0,
      filterMajor: "",
      filterLevel: 0,
      majorData: [],
      questions: [],
      page: 1,
      pageNum: 8,
      isLoading: false,
      nomore: false,
    };
  },
  methods: {
    jumpToSettings() {
      //跳转到设置页面
      this.$Notice.info({
        title: "个人信息不全",
        desc: "请先补全信息，便于筛选你的题目..."
      });

      this.$router.push("./settings");
    },
    getAllMajor() {
      let that = this;
      let url = this.$API.getService("Major", "getAll");

      this.$API
        .post(url)
        .then(res => {
          that.majorData = res.data.data;
        })
        .catch(err => {
          console.log(err);
          this.$Notice.error({
            title: "专业数据获取失败",
            desc: "请检查网络，或联系管理员提交BUG"
          });
        });
    },
    getQuestionPage() {
      const that = this;
      const url = this.$API.getService("Usertoq", "GetPassed"); //"GetPageInformation"
      let uid = parseInt(localStorage.getItem("uid"))
      this.$API
        .post(url, {
          uid: uid,
          page: that.page++,
          num: that.pageNum,
        })
        .then(res => {
          console.log(res.data.data);
          that.questions = res.data.data;
        });
    },
    handleReachBottom() {
      return new Promise(resolve => {
        const that = this;
        const url = this.$API.getService("Usertoq", "GetPassed"); //"GetPageInformation"
        let uid = parseInt(localStorage.getItem("uid"))
        this.$API
          .post(url, {
            uid: uid,
            page: that.page++,
            num: that.pageNum,
          })
          .then(res => {
            console.log(res.data.data);
            let newData = res.data.data
            if(newData.length == 0) {
              that.nomore = true
            }
            for(var i=0;i<newData.length;i++){
            　that.questions.push(newData[i]);
            }
            newData = null;
            this.isLoading = true            
            resolve();
          });
      });
    }
  },
  mounted() {
    //获取用户信息
    this.getQuestionPage();
  },
  components: {
    questionCard,
    sidebar,
    lazyCard
  }
};
</script>

<style lang="sass">

$bright-blue: #0084ff
.container
  margin: 0 auto
.nomore-data
  padding: 2rem
  text-align: center
@media (max-width: 992px)
  .content-container
    float: none
    padding: 0
  .nav-right
    padding-right: .5rem
@media (max-width: 960px)
  .nav-left
    .search-container
      display: none
@media (max-width: 670px)
  .nav-left
    .nav-title
      display: none
@media (max-width: 500px)
  .nav-right
    .right-icon
      display: none
</style>


