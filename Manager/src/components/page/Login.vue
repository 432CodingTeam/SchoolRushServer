<template>
  <div class="login-wrap">
    <div class="quote">

    </div>
    <div class="ms-login">
      <div class="ms-title">
        通过问题提升与分享
      </div>
      <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="0px" class="demo-ruleForm">
        <el-form-item prop="username">
          <el-input v-model="ruleForm.name" placeholder="username"></el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input type="password" placeholder="password" v-model="ruleForm.pass" @keyup.enter.native="submitForm('ruleForm')"></el-input>
        </el-form-item>
        <div class="login-btn">
          <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
        </div>
        <p style="font-size:12px;line-height:30px;color:#999;">Tips : 好好学习，天天向上。</p>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      ccap: {
        image_src: "",
        code: ""
      },
      ruleForm: {
        name: "",
        pass: "",
        ccap: ""
      },
      rules: {
        name: [{ required: true, message: "请输入用户名", trigger: "blur" }],
        pass: [{ required: true, message: "请输入密码", trigger: "blur" }]
      }
    };
  },
  methods: {
    submitForm(formName) {
      const that = this
      that.$refs[formName].validate(valid => {
        if (valid) {
          that.login(that.ruleForm.name, that.ruleForm.word, function(res) {
            //登陆成功
            localStorage.setItem("admin_uid", res.uid)
            localStorage.setItem("admin_token", res.token)
            that.$router.push("/Situation")
          });
        } else {
          that.$message.error("请填写完整！")
          return false;
        }
      });
    },
    login(user, pwd, cb) {
      const that = this;
      let url = this.$API.getService("User", "login");
      that.$API.post(url, this.ruleForm).then(res => {
        console.log(res.data.data);
        let result = res.data.data;
        if (result.hasOwnProperty("res") && result.res == false) {
          //登陆失败
          that.$message.error("登陆失败！");
          return;
        }
        cb(result);
      });
    }
  }
};
</script>

<style scoped>
.login-wrap {
  position: relative;
  width: 100%;
  height: 100%;
  background: url("../../../static/img/login-bg.jpg") 100% 100%;
  background-size: 100% 100%;
}
.ms-title {
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  padding: 0;
  margin-top: -115px;
  text-align: center;
  font-size: 30px;
  font: "Lato", sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.2rem;
  color: #222;
}
.demo-ruleForm {
  margin-top: 40px;
}
.ms-login {
  position: absolute;
  left: 50%;
  top: 50%;
  width: 320px;
  height: 200px;
  margin: -200px 0 0 -190px;
  padding: 40px;
  border-radius: 5px;
  background: #fff;
  box-shadow: 0px 0px 10px 0px #888;
}
.login-btn {
  text-align: center;
}
.login-btn button {
  width: 100%;
  height: 36px;
}
</style>