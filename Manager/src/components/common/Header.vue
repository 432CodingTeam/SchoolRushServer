<template>
  <div class="header">
    <div class="logo">SchoolRush Manager</div>
    <div class="user-info">
      <el-dropdown trigger="click" @command="handleCommand">
        <span class="el-dropdown-link">
          <img class="user-logo" src="http://p6a87gauo.bkt.clouddn.com/user_23.png"> iimT
        </span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="loginout">退出</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      userInfo: {},
    };
  },
  mounted() {
    //页面加载完成之后
    this.getAdminInfo()
  },
  methods: {
    handleCommand(command) {
      if (command == "loginout") {
        const that = this;
        let url = this.$API.getService("User", "logout")

        this.$API.get(url, {params:{name: that.userInfo.name}})
        .then((res) => {
          if(res.data.data == 1) {
            that.$message.success("退出成功！")
            that.$router.push("./login")
          }
        }).catch((err) => {
          console.log(err.data)
        })
      }
    },
    getAdminInfo() {
      let that = this
      let url = this.$API.getService("User", "getById");
      let uid = localStorage.getItem("admin_uid");

      this.$API.get(url, {
        params: {
          id: uid
        }
      }).then(res => {
        console.log(res)
        let result = res.data.data
        that.userInfo = result
        localStorage.setItem("adminUserInfo", JSON.stringify(result));
      });
    }
  }
};
</script>
<style scoped>
.header {
  position: relative;
  box-sizing: border-box;
  width: 100%;
  height: 70px;
  font-size: 22px;
  line-height: 70px;
  color: #fff;
}
.header .logo {
  float: left;
  width: 250px;
  text-align: center;
}
.user-info {
  float: right;
  padding-right: 50px;
  font-size: 16px;
  color: #fff;
}
.user-info .el-dropdown-link {
  position: relative;
  display: inline-block;
  padding-left: 50px;
  color: #fff;
  cursor: pointer;
  vertical-align: middle;
}
.user-info .user-logo {
  position: absolute;
  left: 0;
  top: 15px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
.el-dropdown-menu__item {
  text-align: center;
}
</style>
