<template>
  <div class="app followed-user">
    <!-- 导航条下面内容与侧边栏部分开始 -->
    <div class="container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <div class="row content-container col-lg-9 col-md-9">
          <!-- 内容部分开始 -->
          <div v-for="id in ids" :key="id" class="card-container">
            <user-card :uid="id"></user-card>
          </div>
          <!-- 内容部分结束 -->
        </div>
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
import sidebar from "../../common/sidebar";
import userCard from "../../components/msgCart"
export default {
  data() {
    return {
      ids: []
    };
  },
  methods: {
    getFollowerIds() {
      let uid = this.$route.params.id
      let url = this.$API.getService("Follow", "getFollowerIds")

      this.$API.post(url, {id: uid, page: 1})
      .then( res => {
        console.log(res)
        this.ids = res.data.data
      })
    }
  },
  created() {
    this.getFollowerIds()
  },
  components: {
    userCard,
    sidebar
  }
};
</script>

<style lang="sass">

.followed-user
  .card-container
    display: inline-block
    margin: .4rem

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


