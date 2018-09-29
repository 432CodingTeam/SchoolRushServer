<template>
  <div id="msgCart">
    <div id="msgPanel">
      <div id="title">
        <img :src="userInfo.avatar" alt="">
        <div id="titleText">
          <p id="id">
            <router-link :to="'/home/' + userInfo.id">{{ userInfo.name }}</router-link>
            <span v-if="userInfo.gender=='0'"><Icon type="md-male" /></span>
            <span v-else><Icon type="md-female" /></span>
            <span class="campus-name">{{ userInfo.campusInfo.name }}</span>
          </p>
          <p id="signature">{{ userInfo.describe }}</p>
        </div>
      </div>

      <div id="button">
        <Button v-if="!isFollowed" type="primary" @click="follow" size="large" :disabled="uid==adminID">关注他</Button>
        <Button v-else type="ghost" @click="unFollow" size="large" :disabled="uid==adminID">取消关注</Button>
        <Button size="large" @click="sendMsg" :disabled="uid==adminID">发私信</Button>
      </div>
      <!-- <div id="cartItem">
        <ul>
          <li>
            <div>
              <p>
                
              </p>
            </div>
          </li>
        </ul>
      </div> -->
      <div class="hr"></div>
      <div id="cartItem">
        <ul>
          <li>
            <div>
              <p>答题</p>
              <p>
                <strong>{{ solved }}</strong>
              </p>
            </div>
          </li>
          <li>
            <div>
              <p>出题</p>
              <p>
                <strong>{{ shared }}</strong>
              </p>
            </div>
          </li>
          <li>
            <div>
              <p>关注者</p>
              <p>
                <strong>{{ follower }}</strong>
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>

  </div>
</template>
<script>
export default {
  data() {
    return {
      follower: 0,
      isFollowed: false,
      solved: 0,
      shared: 0,
      userInfo: {
        campusInfo: {},
      },
      adminID: localStorage.getItem("uid")
    }
  },
  methods: {
    follow() {
      let uid = localStorage.getItem("uid")
      let url = this.$API.getService("Follow", "Add");

      if(uid == this.uid) {
        this.$Message.info('不能关注自己!');
        return
      }

      this.$API.post(url, {
        uid: uid,
        type: 1,
        target: this.uid
      }).then(res => {
        let result = res.data.data
        if(result.hasOwnProperty("msg")) {
          this.$Message.info('已经关注过了!');
          return
        }
        if(result.hasOwnProperty("id")) {
          this.isFollowed = true
          this.$Message.success('关注成功!');
        }
      })
    },
    sendMsg() {
      //发送私信
    },
    unFollow() {
      let uid = localStorage.getItem("uid")
      let url = this.$API.getService("Follow", "UnFollow");
      this.$API.post(url, {
        uid: uid,
        type: 1,
        target: this.uid
      }).then(res => {
        let result = res.data.data
        if(result == 1){
          this.$Message.success('关注成功!');
          this.isFollowed = false
        }
      })
    },
    checkFollowed(){
      let uid = localStorage.getItem("uid")
      let url = this.$API.getService("Follow", "IsFollowed");
      this.$API.post(url, {
        uid: uid,
        type: 1,
        target: this.uid
      }).then(res => {
        let result = res.data.data

        this.isFollowed = result
      })
    },
    getUserInfo() {
      let url = this.$API.getService("User", "getById");
      let that = this;

      this.$API.post(url, {
          id: this.uid
      }).then((res) => {
        let Uinfo = res.data.data
        this.userInfo = Uinfo
      }).catch(err => {
      });
    },
    getFollowerNum() {
      let that = this
      let url = this.$API.getService("Follow", "GetFollowerNum")

      this.$API.post(url, {
        uid: this.uid
      }).then((res) => {
        that.follower = parseInt(res.data.data)
      })
    },
    getUserPassedNum() {
      let that = this
      let url = this.$API.getService("Usertoq", "getPassedNum")
      

      this.$API.post(url, {
        uid: this.uid
      }).then((res) => {
        
        that.solved = res.data.data
      })
    },
    getUserSharedNum() {
      let that = this
      let url = this.$API.getService("Userliveness", "getUserSharedNum")

      this.$API.post(url, {
        uid: this.uid
      }).then((res) => {
        that.shared = res.data.data
      })
    }
  },
  props: ["uid"],
  mounted() {
    this.getUserInfo()
    this.getFollowerNum()
    this.getUserPassedNum()
    this.getUserSharedNum()
    this.checkFollowed()
  }
};
</script>


<style>
#msgCart #msgPanel {
  width: 31rem;
  background: #f9f9f9;
  border-radius: 10px;
  padding: 1rem 0;
  overflow: hidden;
}
#msgCart #title img {
  margin-top: 0.5rem;
  margin-left: 1rem;
  border: 1px white solid;
  border-radius: 100%;
  width: 8rem;
  height: 8rem;
  overflow: hidden;
}
#msgCart #titleText {
  width: 60%;
  float: right;
  overflow: hidden;
  padding: 0.8rem;
}
#msgCart #id {
  display: block;
  font-size: 2rem;
  text-align: left;
}
#msgCart #signature {
  display: block;
  font-size: 1.4rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  text-align: left;
}
#msgCart .hr {
  margin: 0 auto;
  width: 92%;
  height: 1px;
  background: rgb(230, 229, 229);
}
#msgCart #cartItem ul {
  text-align: center;
  margin-top: 1rem;
}
#msgCart #cartItem ul li {
  margin: 0 2rem;
  display: inline-block;
}
#msgCart #button {
  text-align: center;
  margin-bottom: .5rem;
}
#msgCart #button button {
  margin-left: 2rem;
  margin-right: 2rem;
}
</style>
<style lang="sass">
#msgCart
  #id
    text-align: left
  #signature
    text-align: left
  .campus-name
    font-size: 1.4rem
    font-weight: normal
</style>


