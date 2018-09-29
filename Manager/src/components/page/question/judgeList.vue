<template>
  <div class="table">
    <div class="handle-box">
      <el-input v-model="searchKey" placeholder="筛选关键词" class="handle-input mr10"></el-input>
      <el-button type="primary" icon="search" @click="search">搜索</el-button>
    </div>
    <el-table :data="questions" border ref="multipleTable" @selection-change="handleSelectionChange">

      <el-table-column prop="id" label="编号" width="65">
      </el-table-column>
      <el-table-column prop="title" label="问题">
        <template scope="scope">
          <a class="article-title" @click="editQuestion(scope.row)">{{ scope.row.title }}</a>
        </template>
      </el-table-column>
      <!-- <el-table-column prop="correct" label="正确答案" width="95">
      </el-table-column> -->
      <el-table-column prop="majorName" label="专业" width="100">
      </el-table-column>
      <el-table-column prop="uName" label="发布人" width="100">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template scope="scope">
          <el-popover ref="deleteConfirm" placement="top" width="160" v-model="scope.row.confirmDeleteVisible">
            <p>确定删除吗？</p>
            <div style="text-align: right; margin: 0">
              <el-button size="mini" type="text" @click="deleteCalcel">取消</el-button>
              <el-button type="primary" size="mini" @click="deleteSubmit">确定</el-button>
            </div>
          </el-popover>
          <el-button size="small" type="primary" v-if="review" @click="handleReview(scope.$index, scope.row)">再审核</el-button>

          <el-button size="small" type="primary" v-if="!review" @click="handlePassed(scope.$index, scope.row)">通过</el-button>
          <el-button size="small" type="danger" v-if="!review" v-popover:deleteConfirm @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination">
      <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :total="totalQuestion" :page-size="perPageQuestions">
      </el-pagination>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      perPageQuestions: 10,
      totalQuestion: 0,
      questions: [],
      QuestionsBack: [],
      tableData: [],
      curPage: "",
      del_list: [],
      searchKey: "",
      currentDeleteItem: {},
      confirmDeleteVisible: false,
      type: 2,
    };
  },
  methods: {
    deleteCalcel() {
      console.log(this.currentDeleteItem);
      this.questions[this.currentDeleteItem.index].confirmDeleteVisible = false;
      this.currentDeleteItem = {};
      console.log("取消");
    },
    deleteSubmit() {
      let id = this.currentDeleteItem.id
      const that = this
      let url = this.$API.getService("Question", "deleteById")

      this.$API.post(url, {
        id: this.questions[this.currentDeleteItem.index].id
      })
      .then((res) => {
        console.log(res.data.data)

        if (res.data.data == 1){
          that.$message.success("删除成功!")
          that.questions.splice(that.currentDeleteItem.index, 1)
          return
        }
        that.$message.error("删除失败！")
      }).catch((err) => {
        console.log(err)
        that.$message.error("删除失败！")
      })
      this.questions[this.currentDeleteItem.index].confirmDeleteVisible = false;
      this.currentDeleteItem = {};
    },
    editQuestion(row) {
      row.type = 2
      this.$router.push({ path: "/editQuestion", query: { question: row } })
    },
    getQuestionTotal() {
      //获取问题总数
      let that = this
      let url = this.$API.getService("Question", "getTotalNum")

      this.$API.get(url).then((res) => {
        that.totalQuestion = parseInt(res.data.data)
      })
    },
    getPage(page, pageNum) {
      let that = this
      let url = this.$API.getService("Question", "getPageByFilter")

      this.$API.post(url, {
        page: page,
        num: pageNum,
        type: this.type,
        status: this.review? 1:0,
      }).then((res) => {
        console.log(res.data.data)
        that.questions = res.data.data.page
        that.totalQuestion = res.data.data.totalNum
        that.QuestionsBack = that.questions
      })
    },
    resetEditFlag(flag) {
      for (var i = 0; i < this.cate.length; i++)
        this.cate[i].editFlag = flag
    },
    handleCurrentChange(val) {
      this.getPage(val, this.perPageQuestions)
    },
    handleReview(index, row) {
      this.questions[index].status = 0
      let that = this
      let url = this.$API.getService("Question", "UpdateById")

      this.$API.post(url, this.questions[index])
      .then((res) => {
        console.log(res.data.data)
        let result = res.data.data
        that.$message.success("更新成功！")
        //被送入待审核之后 在列表中删除
        that.questions.splice(index, 1)
      }).catch((err) => {
        that.$message.success("更新失败！")
      })
    },
    handlePassed(index, row) {
      this.questions[index].status = 1

      let that = this
      let url = this.$API.getService("Question", "UpdateById")

      this.$API.post(url, this.questions[index])
      .then((res) => {
        console.log(res.data.data)
        let result = res.data.data
        that.$message.success("更新成功！")
        //被送入待审核之后 在列表中删除
        that.questions.splice(index, 1)
      }).catch((err) => {
        that.$message.success("更新失败！")
      })
    },
    search(key) {
      if(!key)
        key = this.searchKey
      key = key.trim()
      let searchRes = []
      for (var i = 0; i < this. QuestionsBack.length; i++) {
        if (this. QuestionsBack[i].question.indexOf(key) != -1)
          searchRes.push(this.QuestionsBack[i])
      }
      this.questions = searchRes;
    },
    handleDelete(index, row) {
      row.index = index
      this.currentDeleteItem = row
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    }
  },
  watch: {
    searchKey(key) {
      if (key == "") this.questions = this.QuestionsBack
      else this.search(key)
    }
  },
  mounted() {
    //获取问题数据
    this.getPage(1, 10)
  },
  props: ["review"],
}
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
