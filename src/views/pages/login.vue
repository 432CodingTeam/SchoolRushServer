<template>
  <div class="login-container">
    <div class="panel">
      <div class="logo flex-horizental-center">
        <img src="../../static/img/logo.png">
      </div>
      <div class="login-title">设计问题来分享你擅长的知识</div>
      <div v-if="isLoginPanel" class="login-form">
        <div class="input-form Input-wrapper Input-account">
          <input v-model="loginInfo.username.value" 
            :placeholder = "loginInfo.username.placeholder"
            :class       = "{warn:loginInfo.username.va}"
              class      = "Input"
              type       = "text"
              value      = "123">
        </div>
        <div class="input-form Input-wrapper Input-password">
          <input v-model="loginInfo.password.value" 
          :placeholder   = "loginInfo.password.placeholder"
          :class         = "{warn:loginInfo.password.va}"
            class        = "Input"
            type         = "password"
            @keyup.enter = "login">
        </div>
        <div class="Button-wrapper">
          <button type="submit" class="Button login-btn" @click="login">登陆</button>
        </div>
      </div>
      <div v-else class="login-form">
        <div class="input-form Input-wrapper Input-email">
          <input v-model="registerInfo.email.value" :placeholder="registerInfo.email.placeholder" :class="{warn:registerInfo.email.va}" class="Input" type="email">
        </div>
        <div class="input-form Input-wrapper Input-account">
          <input v-model="registerInfo.username.value" :placeholder="registerInfo.username.placeholder" :class="{warn:registerInfo.username.va}" class="Input" type="text">
        </div>
        <div class="input-form Input-wrapper Input-password">
          <input v-model="registerInfo.password.value" :placeholder="registerInfo.password.placeholder" :class="{warn:registerInfo.password.va}" class="Input" type="password">
        </div>
        <div class="input-form Input-wrapper Input-password">
          <input v-model="registerInfo.validpass.value" :placeholder="registerInfo.validpass.placeholder" :class="{warn:registerInfo.validpass.va}" class="Input"  @keyup.enter="register" type="password">
        </div>
        <div class="Button-wrapper">
          <button class="Button login-btn" @click="register">注册</button>
        </div>
      </div>
      <div class="bottom">
        <p v-if="isLoginPanel">没有账号？<a @click="triggleLogin" href="#">注册</a></p>
        <p v-else>已有账号？<a @click="triggleLogin" href="#">登陆</a></p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      loginInfo: {
        username: {
          value      : '',
          va         : false,
          placeholder: '邮箱或用户名'
        },
        password: {
          value      : '',
          va         : false,
          placeholder: '密码'
        },
      },
      registerInfo: {
        email: {
          value      : '',
          va         : false,
          placeholder: '邮箱'
        },
        username: {
          value      : '',
          va         : false,
          placeholder: '用户名'
        },
        password: {
          value      : '',
          va         : false,
          placeholder: '密码'
        },
        validpass: {
          value      : '',
          va         : false,
          placeholder: '确认密码'
        }
      },
      registerInfoBackup: {},
      isLoginPanel      : false,
      tokenExpDays      : 3
    }
  },
  methods: {
    triggleLogin() {
      this.isLoginPanel = !this.isLoginPanel
    },
    login() {
      let validRes = this.valid(this.loginInfo)
      if(validRes) {
        let url  = this.$API.getService("User", "login")
        let that = this
        let data = {
          name: this.loginInfo.username.value,
          pass: this.loginInfo.password.value
        }
        this.$API.post(url, data)
        .then(res => {
          //保存用户id 与token信息
          if(res.data.data) {
            let data = res.data.data
            localStorage.setItem("uid", data.uid)
            localStorage.setItem("sr_token", data.token)
            //设置token 3天过期
            this.setCookie('sr_token', data.token, this.tokenExpDays) 
            that.$Notice.success({
                title: '登陆成功',
                desc : "正在跳转..."
            });
            //跳转到首页
            this.$router.push("/index")
            return
          }
          that.$Notice.error({
            title: '登陆失败',
            desc : res.data.data.msg
          });
        })
        .catch((err) => {
          this.$Notice.error({
              title: '登陆失败',
              desc : "请检查网络，或与网站管理员联系提交BUG"
          });
        })
      }
    },
    setCookie(c_name,value,expiredays) {
      let exdate = new Date()
      exdate.setDate(exdate.getDate()+expiredays)
      document.cookie = c_name+ "=" +escape(value)+
        ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
    },
    getCookie(c_name){
      if (document.cookie.length>0) {
        let c_start = document.cookie.indexOf(c_name + "=")
        if (c_start!=-1) { 
              c_start           = c_start + c_name.length+1
          let c_end             = document.cookie.indexOf(";",c_start)
          if  (c_end==-1) c_end = document.cookie.length
          return unescape(document.cookie.substring(c_start,c_end))
        }
      }
      return ""
    },
    checkCookie() {
      let token = this.getCookie('sr_token')
      
      if (token!=null && token!=""){
        //有cookie
        console.log('有cookie')
        this.validCurToken(token)
      } else {
        //没有cookie
        console.log('没有cookie')
      }
    },
    register() {
      let validRes = this.valid(this.registerInfo)
      let that     = this

      if(!validRes) return

      let url = this.$API.getService("User", "add")

      let data = {
        email: this.registerInfo.email.value,
        name : this.registerInfo.username.value,
        pass : this.registerInfo.password.value,
      }

      this.$API.post(url, data)
      .then((res) => {
        console.log(res)
        if(res.data.data.hasOwnProperty("res")) { //有重复
          let repeatProp     = that.registerInfo.username
          let repeatPropName = "用户名"
          if(res.data.data.error == "邮箱重复" ) {
            repeatProp     = that.registerInfo.email
            repeatPropName = "邮箱"
          }
          that.$set(repeatProp, "value", "")
          that.$set(repeatProp, 'va', true)
          that.$set(repeatProp, "placeholder", "该"+ repeatPropName +"已被使用")
          return
        }
        //保存用户id 与token信息
        localStorage.setItem("uid", res.data.data.id)
        localStorage.setItem("sr_token", res.data.data.token)
        //设置cookie
        this.setCookie('sr_token', data.token, this.tokenExpDays) 
        this.$Message.success('登陆成功!');
        //跳转到首页
        this.$router.replace("/index")
      })
      .catch((err) => {
        this.$Message.success('登陆失败，请检查网络!');
      })
    },
    valid(obj) {  //表单验证
      console.log(obj)
      let flag = true
      //遍历属性查找是否有空值
      for (let i in obj) {
        if(obj[i].value == "") {
          flag = false
          this.$set(obj[i], 'va', true)
          let placeholder = obj[i].placeholder
          if(placeholder.indexOf("不能为空") == -1) {
            if (placeholder.indexOf("两次") != -1) continue //跳过确认密码
            this.$set(obj[i], 'placeholder', obj[i].placeholder + "不能为空")
          }
        }
      }
      //如果是登陆页面 没有确认密码 并且都不空 直接返回
      if(!obj.hasOwnProperty("validpass")) return flag

      //不是登陆页面 验证密码和确认密码相同
      if(obj.password.value.trim() != "" && obj.password.value.trim() != obj.validpass.value.trim()) {
        //密码不为空 而且两次输入密码不同
        flag = false
        //置空两个密码输入框
        this.$set(obj.password, "value", "")
        this.$set(obj.validpass, "value", "")
        //提示框的颜色变成红色
        this.$set(obj.password, 'va', true)
        this.$set(obj.validpass, 'va', true)

        this.$set(obj.validpass, "placeholder", "两次输入的密码不一致")
      }

      //验证邮箱
      let email_pattern = /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/i
      if(!email_pattern.test(obj.email.value)) {
        flag = false
        this.$set(obj.email, "value", "")
        //提示框的颜色变成红色
        this.$set(obj.email, 'va', true)

        this.$set(obj.email, "placeholder", "邮箱格式不正确")
      }

      //验证用户名 4-16位 字母 数字 下划线 减号
      var name_pattern = /^[a-zA-Z0-9_-]{4,16}$/
      if(!name_pattern.test(obj.username.value)) {
        flag = false
        this.$set(obj.username, "value", "")
        //提示框的颜色变成红色
        this.$set(obj.username, 'va', true)

        this.$set(obj.username, "placeholder", "4-16位 字母 数字 下划线 减号")
      }

      //验证密码长度
      if(obj.password.value.length > 18 || obj.password.value.length < 8) {
        flag = false
        this.$set(obj.password, "value", "")
        //提示框的颜色变成红色
        this.$set(obj.password, 'va', true)

        this.$set(obj.password, "placeholder", "密码长度8-18")
      }
      return flag
    },
    validCurToken(token) {
      //通过cookie中的token来验证身份
      if(!token) return
      let url = this.$API.getService("User", "getUserByToken")

      this.$API.post(url, {token: token})
      .then(res => {
        if(!res.data.data) return

        localStorage.setItem("uid",res.data.data.id)
        localStorage.setItem('userinfo', res.data.data)

        this.$router.replace("/index")
      })
    }
  },
  mounted() {
    this.checkCookie()
  }
}
</script>
<style lang="sass">
  @import "../../static/sass/common.sass"
  /* 变量定义 */
  $login-bg-img: "../../static/img/login-bg.png"
  $white       : #fff
  $base-color  : #0084ff

  .login-container
    position       : absolute
    top            : 0
    bottom         : 0
    left           : 0
    right          : 0
    background     : url($login-bg-img)
    background-size: 100% 100%
  .panel
    position     : absolute
    $width       : 43.2rem
    $height      : 43rem
    width        : $width
    top          : 45%
    left         : 50%
    margin-left  : -$width/2
    margin-top   : -$height/2
    background   : $white
    border-radius: 5px
    box-shadow   : 0px 0px .1rem 0px #ddd
    overflow     : hidden
  .flex-horizental-center
    /* flex布局 */
    display        : flex
    display        : -webkit-flex
    justify-content: center
  .login-title
    margin-top    : 1rem
    text-align    : center
    font-size     : 2.2rem
    letter-spacing: 0
    color         : $base-color
  .login-form
    padding-top: .5rem
    display    : block
  .Input
    display   : block
    width     : 100%
    height    : 4.8rem
    font-size : 1.4rem
    padding   : 0
    background: transparent
    border    : none
    outline   : none
    resize    : none
  .Input-wrapper
    position     : relative
    width        : 80%
    margin       : 0 auto
    height       : 4.8rem
    padding      : 0
    font-size    : 1.4rem
    background   : #fff
    border-bottom: .1rem solid #ebebeb
    box-sizing   : border-box
    transition   : background .2s, border .2s
  .Input-password
    margin-top: 1rem
  .Button-wrapper
    width        : 80%
    margin       : 0 auto
    margin-top   : 2rem
    margin-bottom: 8rem
  .Button
    color           : #fff
    width           : 100%
    background-color: #0084ff
    padding         : .3rem 1.6rem
    font-size       : 1.4rem
    line-height     : 3rem
    text-align      : center
    cursor          : pointer
    border          : .1rem solid
    border-radius   : .3rem
  .bottom
    position       : absolute
    display        : flex
    display        : -webkit-flex
    bottom         : 0
    width          : 100%
    height         : 6rem
    background     : #f6f6f6
    box-shadow     : 0 0 .1rem 0 #ccc
    align-items    : center
    justify-content: center
    p
      display  : flex
      display  : -webkit-flex
      font-size: 1.6rem
      
</style>


