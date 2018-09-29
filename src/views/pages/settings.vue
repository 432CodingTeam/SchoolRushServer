<template>
  <div class="app" id="settings">
    <div class="container grid-container">
      <div class="grid-no-padding card userinfo-settings-wrap col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <Col class="userinfo-settings-title" span="24">个人设置</Col>
        <Col class="userinfo-settings-content-wrap" offset="4" span="16">
          <Form ref="userInfo" :model="userInfo" :rules="validInfo" :label-width="100">
            <Col class="settings-avatar" offset="11" span="13">
                <img class="avatar" :src="userInfo.avatar">
                <corp-image @imgData="imgDataChanged"></corp-image>
            </Col>
            <FormItem label="用户名" prop="name">
              <Input size="large" disabled v-model="userInfo.name" placeholder="请输入用户名"></Input>
            </FormItem>
            <FormItem label="性别" prop="gender">
              <RadioGroup size="large" v-model="userInfo.gender">
                  <Radio label="1">男</Radio>
                  <Radio label="0">女</Radio>
              </RadioGroup>
            </FormItem>
            <FormItem label="邮箱" prop="email">
              <Input size="large" v-model="userInfo.email" placeholder="输入你的邮箱"></Input>
            </FormItem>
            <FormItem label="学校" prop="campusID">
              <Select size="large" v-model="userInfo.campusID" filterable>
                <Option v-for="item in campusData" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
            </FormItem>
            <FormItem label="专业" prop="majorID">
              <Select size="large" v-model="userInfo.majorID" filterable>
                <Option v-for="item in majorData" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
            </FormItem>
            <FormItem label="兴趣专业" prop="vice">
              <Select v-model="userInfo.vice" filterable multiple>
                <Option v-for="item in majorData" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
            </FormItem>
            <FormItem label="一句话介绍" prop="describe">
              <Input v-model="userInfo.describe" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="一句话介绍自己吧"></Input>
            </FormItem>
            <FormItem label="电话" prop="tel">
              <Input size="large" v-model="userInfo.tel" placeholder="输入你的电话"></Input>
            </FormItem>
            <!-- 提交按钮 -->
            <FormItem>
              <Button class="submitBtn" type="primary" size="large" @click="handleSubmit('userInfo')">修改</Button>
            </FormItem>
          </Form>
        </Col>
      </div>
    </div>
  </div>
</template>
<script>
import corpImage from "../components/tools/corp-image"
export default {
  data() {
    return {
      userInfo: {
        name: "",
        email: "",
        tel: "",
        campusID: "",
        campusName: "",
        major: "",
        vice: [],
        avatar: "./src/static/img/small-logo.png",
        describe: "",
        gender: ""
      },
      campusData: [],
      majorData: [],
      validInfo: {
        name: [
          {
            required: true,
            message: "用户名不能为空",
            trigger: "blur"
          }
        ],
        email: [
          {
            required: true,
            message: "请填写邮箱",
            trigger: "blur"
          },
          { type: "email", message: "邮箱格式错误", trigger: "blur" }
        ],
        tel: [
          {
            //required: true,
            message: "请填写电话",
            trigger: "bulr"
          }
        ],
        campusName: [
          { required: true, message: "请选择学校", trigger: "change" }
        ],
        gender: [
          { 
            required: true,
            message: "请选择性别",
            trigger: "change"
          }
        ],
        majorID: [
          {
            required: true,
            message: "专业不能为空",
            trigger: "change"
          }
        ],
        describe: [
          {
            required: true,
            message: "请输入一句话介绍",
            trigger: "blur"
          },
          {
            type: "string",
            max: 60,
            message: "最多30字",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    handleSubmit(name) {
      this.$refs[name].validate(valid => {
        if (valid) {
          let vice = ""
          for(var i in this.userInfo.vice) {
            if(vice.length == 0)
              vice = this.userInfo.vice[i].toString()
            else
              vice += "," + this.userInfo.vice[i].toString()
          }
          let data = {
            id: this.userInfo.id,
            name: this.userInfo.name,
            email: this.userInfo.email,
            tel: this.userInfo.tel,
            campusID: this.userInfo.campusID,
            majorID: this.userInfo.majorID,
            vice: vice,
            avatar: this.userInfo.avatar,
            gender: this.userInfo.gender,
            describe: this.userInfo.describe
          }
          console.log(data)
          this.updateUserInfo(data)
        } else {
          this.$Message.error("填写有误!");
        }
      });
    },
    updateUserInfo(data) {
      let url = this.$API.getService("User", "updateById")
      let that = this

      this.$API.post(url, data)
      .then((res) => {
        setTimeout(() => {
          this.$router.replace("/index")
        }, 1000)
        this.$Notice.success({
          title: "修改成功"
        })
      })
      .catch((err) => {
        this.$Notice.error({
          title: "更新失败",
          desc: "请检查网络，或联系管理员提交BUG"
        })
      })
    },
    handleReset(name) {
      this.$refs[name].resetFields();
    },
    imgDataChanged(imgData) {
      this.userInfo.avatar = imgData
    },
    getUserInfo() {
      let uid = localStorage.getItem("uid")
      let url = this.$API.getService("User", "getById")
      let that = this

      this.$API.post(url, {id:uid})
      .then((res) => {
        let Uinfo = res.data.data
        localStorage.setItem("userinfo", JSON.stringify(Uinfo))
        if(!Uinfo.vice) //防止多选select出错
          Uinfo.vice = []
        else {
          let vice = Uinfo.vice.split(',')
          Uinfo.vice = []
          for(var i in vice) {
            Uinfo.vice.push(vice[i])
          }
        }
        that.userInfo = Uinfo
      })
      .catch((err) => {
        console.log(err)
        this.$Notice.error({
          title: "用户数据获取失败",
          desc: "请检查网络，或联系管理员提交BUG"
        })
      })
    },
    getAllCampus() {
      let that = this
      let url = this.$API.getService("Campus", "getAll")

      this.$API.post(url)
      .then((res) => {
        that.campusData = res.data.data
      })
      .catch((err) => {
        console.log(err)
        this.$Notice.error({
          title: "高校数据获取失败",
          desc: "请检查网络，或联系管理员提交BUG"
        })
      })
    },
    getAllMajor() {
      let that = this
      let url = this.$API.getService("Major", "getAll")

      this.$API.post(url)
      .then((res) => {
        that.majorData = res.data.data
      })
      .catch((err) => {
        console.log(err)
        this.$Notice.error({
          title: "专业数据获取失败",
          desc: "请检查网络，或联系管理员提交BUG"
        })
      })
    }
  },
  components: {
    "corp-image": corpImage
  },
  mounted() {
    this.getUserInfo()
    this.getAllCampus()
    this.getAllMajor()
  }
}
</script>
<style lang="sass">
#settings
  .card
    padding: 1rem 0
    background: #fff
  .userinfo-settings-wrap
    margin-bottom: 6rem
  .userinfo-settings-title
    border-bottom: .1rem solid #e9e9e9
    padding-left: 2rem
    padding-bottom: 1rem 
  .userinfo-settings-content-wrap
    padding-top: 2rem
    .ivu-form-item
      .ivu-form-item-label
        font-size: 1.4rem
    .submitBtn
      margin-top: 1rem
    .settings-avatar
      z-index: 99
      img.avatar
        height: 8rem
        margin-top: -1rem
        border-radius: .5rem
        box-shadow: 0 0 .2rem 0 #999
  .ivu-form-item
    margin-bottom: 1.7rem
  .ivu-form-item-error-tip
    padding-top: .1rem
</style>
