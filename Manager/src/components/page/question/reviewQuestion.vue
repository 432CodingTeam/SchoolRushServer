<template>
  <div class="table">
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-menu"></i> 问题管理</el-breadcrumb-item>
        <el-breadcrumb-item>待审核问题</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <el-tabs v-model="questionType" @tab-click="handleClick">
      <el-tab-pane label="选择题" name="1"><select-list review></select-list></el-tab-pane>
      <el-tab-pane label="填空题" name="2"><blank-list review></blank-list></el-tab-pane>
      <el-tab-pane label="判断题" name="3"><judge-list review></judge-list></el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import selectList from "./selectList"
import judgeList from "./judgeList"
import blankList from "./blankList"
export default {
  data() {
    return {
      questionType: "2",
    };
  },
  methods: {
    
  },
  watch: {
  },
  components: {
    selectList,
    judgeList,
    blankList,
  }
};
</script>

<style scoped>
.handle-box {
  margin-bottom: 20px;
}
.handle-select {
  width: 120px;
}
.handle-input {
  width: 300px;
  display: inline-block;
}
.article-title:hover {
  cursor: pointer;
  color: #20a0ff;
}
.article-title:visited {
  color: black;
}
</style>