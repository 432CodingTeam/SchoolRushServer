<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-date"></i> 问题管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加问题</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="plugins-tips">
      可在输入问题的时候点击右边按钮添加下划线。
    </div>
    <el-form label-width="80px">
      <!-- <el-form-item label="提问人:">
        <el-input type="text" v-model="question.uName"></el-input>
      </el-form-item> -->
      <el-form-item label="问题类型:">
        <el-select size="large" v-model="question.type" placeholder="请选择题型">
          <el-option key="选择题" label="选择题" value="1">
          </el-option>
          <el-option key="填空题" label="判断题" value="2">
          </el-option>
          <el-option key="判断题" label="填空题" value="3">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="所属专业:">
        <el-select filterable v-model="question.majorID" placeholder="请选择题型">
          <el-option key="专业1" label="计算机科学与技术" value="1">
          </el-option>
          <el-option key="专业2" label="软件工程" value="2">
          </el-option>
          <el-option key="专业3" label="信息管理与信息系统" value="3">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="问题：">
        <el-input v-model="question.title" placeholder="请输入问题...">
          <el-button slot="append" @click="addUnderLine">____</el-button>
        </el-input>
      </el-form-item>
      <!-- 当选择题的时候 -->
      <el-form-item v-if="question.type==1" prop="name">
        <el-input v-model="question.A">
          <template slot="prepend">选项A</template>
        </el-input>
      </el-form-item>
      <el-form-item v-if="question.type==1" prop="name">
        <el-input v-model="question.B">
          <template slot="prepend">选项B</template>
        </el-input>
      </el-form-item>
      <el-form-item v-if="question.type==1" prop="name">
        <el-input v-model="question.C">
          <template slot="prepend">选项C</template>
        </el-input>
      </el-form-item>
      <el-form-item v-if="question.type==1" prop="name">
        <el-input v-model="question.D">
          <template slot="prepend">选项D</template>
        </el-input>
      </el-form-item>
      <!-- 选项结束 -->
      <!-- 选择题的答案 -->
      <el-form-item v-if="question.type==1" label="正确选项:">
        <el-select size="large" v-model="question.correct" placeholder="请选择...">
          <el-option key="选项A" label="选项A" value="A">
          </el-option>
          <el-option key="选项B" label="选项B" value="B">
          </el-option>
          <el-option key="选项C" label="选项C" value="C">
          </el-option>
          <el-option key="选项D" label="选项D" value="D">
          </el-option>
        </el-select>
      </el-form-item>
      <!-- 判断题的答案 -->
      <el-form-item v-if="question.type==2" label="正确选项:">
        <el-radio v-model="question.correct" label="T">正确</el-radio>
        <el-radio v-model="question.correct" label="F">错误</el-radio>
      </el-form-item>
      <!-- 填空题的答案 -->
      <el-form-item v-if="question.type==3">
        <el-input size="large" v-model="question.correct" placeholder="请输入...">
          <template slot="prepend">正确答案</template>
        </el-input>
      </el-form-item>

      <el-form-item label="是否审核:">
        <el-radio v-model="question.status" label=1>直接发布</el-radio>
        <el-radio v-model="question.status" label=0>待审核</el-radio>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="publish">{{ isUpdate ? "更新" : "添加"}}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      question: {
        type    : "",
        question: "",
        A       : "",
        B       : "",
        C       : "",
        D       : "",
        correct : "",
        uName   : "",
        toAnswer: "",
        majorID : "",
        status  : 0
      },
      isUpdate: false,
      qBack   : {},
    };
  },
  mounted() {
    //DOM挂载之后 判断是否传入了已经发表的文章
    //如果是 就将此文章信息填入 开始编辑文章
    this.qBack = this.question
    if (this.$route.query.question) {
      this.isUpdate = true
      this.question = this.$route.query.question;
      console.log(this.question)
    }
    //获取全部分类
    this.getAllMajor();
  },
  methods: {
    getAllMajor() {
      //TODO: 获取所有分类
    },
    publish() {
      console.log(this.question);
      let that = this
      let url  = this.$API.getService("Question", "UpdateById")

      this.$API.post(url, this.question)
      .then((res) => {
        console.log(res.data.data)
        let result = res.data.data
        that.$message.success("更新成功！")
        this.question = this.qBack
      }).catch((err) => {
        that.$message.success("更新失败！")
      })
    },
    handleSelect(item) {
      this.article.cate = item.id;
    },
    addUnderLine() {
      this.question.question += "____";
    }
  },
  components: {}
};
</script>