<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-date"></i> 小组管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加小组</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="plugins-tips">
      可在输入小组的时候点击右边按钮添加下划线。
    </div>
    <!-- 头像弹出层 -->
    <el-popover ref="avatarPop" placement="top" width="120" trigger="hover">
      <img class="group-avatar" :src="group.avatar">
    </el-popover>
    <el-form label-width="100px">
      <el-form-item label="小组名:">
        <el-input type="text" v-model="group.name"></el-input>
      </el-form-item>
      <el-form-item label="专业">
        <el-select v-model="group.mid" filterable placeholder="筛选专业">
          <el-option v-for="item in majorData" :key=item.id :label=item.name :value=item.id>
          </el-option>
        </el-select>
      </el-form-item>
      <!-- 填空题的答案 -->
      <el-form-item label="小组介绍:">
        <el-input type="textarea" v-model="group.introduce"></el-input>
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
      group: {},
      campusData: [],
      majorData: [],
      isUpdate: false,
      passwordInput: "password"
    };
  },
  mounted() {
    //DOM挂载之后 判断是否传入了已经发表的文章
    //如果是 就将此文章信息填入 开始编辑文章
    if (this.$route.query.group) {
      this.group = this.$route.query.group
      this.isUpdate = true
      console.log(this.group)
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
      const data   = this.group
      const that   = this
      let   action = "updateById"
      if(!this.isUpdate) action = "add"
      const url = this.$API.getService("Group", action)
      
      this.$API.post(url, data).then(res => {
        console.log(res)

        if(this.isUpdate)
          that.$message.success("修改成功！")
        else 
          that.$message.success("创建成功！")
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
.group-avatar {
  height: 150px
}
</style>
