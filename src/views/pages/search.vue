<template>
  <div class="app" id="search">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <div class="row content-container col-lg-9 col-md-9">
          <div class="card-container index-filter">
            <p>搜索：{{ $route.params.key }}</p>
          </div>
        </div>
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
      const url = this.$API.getService("Question", "search");
      this.$API
        .post(url, {
          key: this.$route.params.key,
          page: that.page,
          num: that.pageNum,
        })
        .then(res => {
          console.log(res.data.data);
          that.questions = res.data.data;
          if(that.questions.length <= 8) {
            that.handleReachBottom()
          }
        });
    },
    handleReachBottom() {
      return new Promise(resolve => {
        const that = this;
        const url = this.$API.getService("Question", "search");
        this.$API
          .post(url, {
            key: this.$route.params.key,
            page: ++that.page,
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
    this.getAllMajor()
    this.getQuestionPage();
  },
  watch: {
    $route(now) {
      this.getQuestionPage()
    }
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
</style>


