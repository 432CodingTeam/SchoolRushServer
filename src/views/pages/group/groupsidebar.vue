<template>
  <div id="group-sidebar">
    <div class="sidebar-item sidebar-userinfo-container">
      <Row type="flex" justify="center" align="middle" class="code-row-bg">
        <Col span="24">
        <p class="userinfo-avatar">
          <Avatar shape="square" size="large" :src="userInfo.avatar" />
        </p>
        <p class="userinfo-username">{{ userInfo.name}}</p>
        </Col>
        <Col span="24">
        <p class="userinfo-locate-school">
          <Tag color="green">{{ userInfo.campusInfo.locate }}</Tag>
          <Tag color="blue">{{ userInfo.campusInfo.name }}</Tag>
        </p>
        </Col>
        <Col class="user-info-followed" span="12">
        <p class="num">{{ follow }}</p>
        <p class="title">关注了</p>
        </Col>
        <Col class="user-info-follower" span="12">
        <p class="num">{{ follower }}</p>
        <p class="title">关注者</p>
        </Col>
      </Row>
    </div>
    <div class="sidebar-item sidebar-mygroup-container">
      <p class="title">最近加入</p>
      <Row :gutter="16">
        <Col v-for="item in latestJoinUser" class="group-item" v-on:mouse-over="showUserCard(item.user.id)" :key="item.user.id" span="8">
          <Poptip class="poptip" trigger="hover" placement="left">
            <Avatar shape="square" size="large" icon="person" @click="toUserHome(item.user.id)" :src="item.user.avatar" />
            <p @click="toUserHome(item.user.id)">{{ dealName(item.user.name) }}</p>

            <div class="api" slot="content">
              <user-card :uid="item.user.id"></user-card>
            </div>
          </Poptip>
        </Col>
      </Row>
    </div>
    <div class="sidebar-item sidebar-fastto-container">
        <ul class="fastto">
          <li><p><Icon type="disc"></Icon><router-link to="/challenge">挑战</router-link></p></li>
          <li><p><Icon type="checkmark-circled"></Icon>已通过</p></li>
          <li><p><Icon type="hammer"></Icon>正在解决</p></li>
          <li><p><Icon type="pricetags"></Icon>关注的标签</p></li>
          <li><p><Icon type="university"></Icon>关注的学校</p></li>
        </ul>
    </div>
    <div class="sidebar-info-container">
      <p>侵权举报网上有害信息举报专区</p>
      <p>违法和不良信息举报：010-82716601</p>
      <p>儿童色情信息举报专区</p>
      <p>联系我们 © 2018 SchoolRush</p>
    </div>
  </div>
</template>
<script>
import userCard from "../../components/msgCart"
export default {
  data() {
    return {
      follow: 0,
      follower: 0,
      userInfo: {
        avatar: "",
        campusID: "",
        campusInfo: {},
        describe: "",
        email: "",
        gender: "",
        id: "",
        identify: "",
        majorID: "",
        majorInfo: {},
        name: "",
        tel: "",
        vice: "",
        solveInfo: {},
      },
      latestJoinUser: [],
    }
  },
  methods: {
    getUserInfo() {
      let uid = localStorage.getItem("uid");
      let url = this.$API.getService("User", "getById");
      let that = this;

      this.$API
        .post(url, {
          id: uid
        })
        .then((res) => {
          let Uinfo = res.data.data
          for(var i in Uinfo) {
            that.$set(that.userInfo, i, Uinfo[i])
          }
          localStorage.setItem("userinfo", JSON.stringify(Uinfo));
          for (let i in Uinfo) {
            if (!Uinfo[i]) {
              that.jumpToSettings();
              break;
            }
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    toUserHome(id) {
      this.$router.push("/home/" + id)
    },
    getLatestJoinUser() {
      let url = this.$API.getService("Usertogroup", "getRecentJionU")

      this.$API.post(url, {gid: this.gid}).then(res => {
        let result = res.data.data
        this.latestJoinUser = result
      })
    },
    dealName(text) {
      return this.sub(text, 18)
    },
    sub(str,n){
      var r=/[^\x00-\xff]/g;
      if(str.replace(r,"mm").length<=n){return str;}
      var m=Math.floor(n/2);
      for(var i=m;i<str.length;i++){
          if(str.substr(0,i).replace(r,"mm").length>=n){
              return str.substr(0,i)
          }
      }
      return str
    },
    getFollowNum() {
      let uid = localStorage.getItem("uid")
      let that = this
      let url = this.$API.getService("Follow", "GetFollowNum")

      this.$API.post(url, {
        uid: uid,
        type: 1
      }).then((res) => {
        that.follow = parseInt(res.data.data)
      })
    },
    getFollowerNum() {
      let uid = localStorage.getItem("uid")
      let that = this
      let url = this.$API.getService("Follow", "GetFollowerNum")

      this.$API.post(url, {
        uid: uid
      }).then((res) => {
        that.follower = parseInt(res.data.data)
      })
    },
  },
  mounted() {
    //TODO: 头像与昵称点击只有一个跳转
    this.getUserInfo()
    this.getLatestJoinUser()
    this.getFollowNum()
    this.getFollowerNum()
  },
  props: ["gid"],
  watch: {
    gid() {
      this.getLatestJoinUser()
    }
  },
  components: {
    userCard,
  }
}
</script>
<style lang="sass">
#group-sidebar
  .sidebar-userinfo-container
    .user-info-followed,.user-info-follower
      p.title
        font-size: 1.6rem
      p.num
        font-size: 2.3rem
        font-weight: bold
    .userinfo-avatar
      .ivu-avatar-large
        width: 7rem
        height: 7rem
        margin-top: 2rem
    .userinfo-username
      padding: .5rem 0
      padding-top: 1rem
      font-size: 2rem
      font-weight: bold
  .sidebar-mygroup-container
    margin-top: 1rem
    .poptip
      margin: 0 auto
      display: block
      .ivu-poptip-body
        background: rgb(245, 245, 245)
    p.title
      font-size: 1.5rem
      padding: .5rem 0
      padding-left: 1rem
      border-bottom: .1rem solid #eee
    .group-item
      word-break: break-all
      height: 8rem
      margin: .5rem 0
      .ivu-avatar-large
        margin: 0 auto
        display: block
        width: 4rem
        height: 4rem
        cursor: pointer
      p
        cursor: pointer
        margin-top: .3rem
        font-size: 1.4rem
        text-align: center
</style>
