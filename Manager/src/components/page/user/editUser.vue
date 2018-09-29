<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-date"></i> 用户管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加用户</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="plugins-tips">
      鼠标移上头像栏可以查看图片。
    </div>
    <!-- 头像弹出层 -->
    <el-popover ref="avatarPop" placement="top" width="120" trigger="hover">
      <img class="user-avatar" :src="user.avatar">
    </el-popover>
    <el-form label-width="100px">
      <el-form-item label="ID:">
        <el-input disabled type="text" v-model="user.id"></el-input>
      </el-form-item>
      <el-form-item label="用户名:">
        <el-input type="text" v-model="user.name"></el-input>
      </el-form-item>
      <el-form-item label="头像:">
        <el-input disabled v-popover:avatarPop type="text" v-model="user.avatar"></el-input>
      </el-form-item>
      <el-form-item label="身份:">
        <el-radio-group v-model="user.identify">
          <el-radio label=1>用户</el-radio>
          <el-radio label=2>管理员</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="性别:">
        <el-radio-group v-model="user.gender">
          <el-radio label=0>女</el-radio>
          <el-radio label=1>男</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="电话:">
        <el-input type="text" v-model="user.tel"></el-input>
      </el-form-item>
      <el-form-item label="邮箱:">
        <el-input type="text" v-model="user.email"></el-input>
      </el-form-item>
      <el-form-item label="密码:">
        <el-input :type="passwordInput" v-model="user.pass">
          <el-button @click="changePassVisual" slot="prepend">显示密码</el-button>
        </el-input>
      </el-form-item>
      <el-form-item label="学校">
        <el-select v-model="user.campusID" filterable placeholder="筛选学校">
          <el-option v-for="item in campusData" :key=item.id :label=item.name :value=item.id>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="专业">
        <el-select v-model="user.majorID" filterable placeholder="筛选专业">
          <el-option v-for="item in majorData" :key=item.id :label=item.name :value=item.id>
          </el-option>
        </el-select>
      </el-form-item>
      <!-- 填空题的答案 -->
      <el-form-item label="一句话介绍:">
        <el-input type="textarea" v-model="user.describe"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="editSubmit">添加 / 修改</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      user: {},
      campusData: [],
      majorData: [],
      passwordInput: "password"
    };
  },
  mounted() {
    //DOM挂载之后 判断是否传入了已经发表的文章
    //如果是 就将此文章信息填入 开始编辑文章
    if (this.$route.query.user) {
      this.user = this.$route.query.user
      console.log(this.user)
    }
    //获取全部分类
    this.getAllMajor()
    this.getAllCampus()
  },
  methods: {
    getAllMajor() {
      //TODO: 获取所有分类
      const that = this
      const url = this.$API.getService("Major", "getAll")

      this.$API.post(url).then((res) => {
        console.log(res)

        that.majorData = res.data.data
      })
    },
    getAllCampus() {
      //TODO: 获取所有分类
      const that = this
      const url = this.$API.getService("Campus", "getAll")

      this.$API.post(url).then((res) => {
        console.log(res)

        that.campusData = res.data.data
      })
    },
    editSubmit() {
      const data = this.user
      const that = this
      const url = this.$API.getService("User", "updateById")
      
      this.$API.post(url, data).then((res) => {
        console.log(res)

        that.$message.success("修改成功！")
      })
    },
    handleSelect(item) {
      this.article.cate = item.id
    },
    changePassVisual() {
      console.log(this.passwordInput)
      if( this.passwordInput == "password")
        this.passwordInput = "text"
      else
        this.passwordInput = "password"
      console.log(this.passwordInput)
    }
  },
  components: {}
};
</script>
<style>
.user-avatar {
  height: 150px
}
</style>
