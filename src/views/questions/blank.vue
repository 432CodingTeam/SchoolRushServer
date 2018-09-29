<template>
  <div>
    <!-- 提问表单开始 -->
    <Form ref="Question" :model="Question" :rules="ruleValidate" :label-width="80">
      <FormItem label="专业分类" prop="majorID" resetFields>
        <Col span="24">
          <Select v-model="Question.majorID" filterable>
            <Option v-for="item in majorData" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </Col>
      </FormItem>
      <FormItem label="标签" prop="labels">
        <Select class="q-title" placeholder="可搜索标签哦" v-model="Question.labels" filterable multiple>
          <Option v-for="item in labelData" :value="item.id" :key="item.id">{{ item.name }}</Option>
        </Select>
        <!-- TODO: 添加标签 <Button class="addLabel" type="primary" @click="handelAddLabel">添加标签</Button>
        <template>
          <Modal
            class="newLabel"
            v-model="isAddLabel"
            :closable="false"
            @on-ok="addLabel">
            <Input v-model="newLabelName" placeholder="标签名">
            <Button type="info" slot="append">添加</Button>
            </Input>
            <div class="Modal-footer" slot="footer"></div>
          </Modal>
        </template> -->
      </FormItem>
      <FormItem label="问题" prop="q">
        <Input class="q-title" v-model="Question.q" type="textarea" ref="qInput" :autosize="true" 
        placeholder="问题标题，不超过100字" width="50px"></Input>
        <Button class="addUnderline" type="primary" @click="addUnderLine">____</Button>
      </FormItem>
      <FormItem label="正确答案" prop="correct">
        <Input v-model="Question.correct" placeholder="正确答案"></Input>
      </FormItem>
      <FormItem label="给答题者" prop="toAnswer">
        <Input v-model="Question.toAnswer" type="textarea"
        :autosize="true"
        placeholder="想要对答题者说的话..."></Input>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="handleSubmit('Question')">完成</Button>
        <Button type="ghost" @click="handleReset" style="margin-left: 8px">重置</Button>
      </FormItem>
    </Form>
    <!-- 提问表单结束 -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      Question: {
        type: 3, //选择1 判断2 填空3
        q: "",
        majorID: "",
        correct: "",
        toAnswer: "",
        labels: [],
      },
      QuestionBackup: {},
      ruleValidate: {
        q: [
          {
            required: true,
            message: "问题不能为空",
            trigger: "blur"
          }
        ],
        majorID: [
          {
            required: true,
            message: "专业不能为空",
            trigger: "change"
          }
        ],
        
        correct: [
          {
            required: true,
            message: "正确选项不能为空",
            trigger: "blur"
          }
        ],
        toAnswer: [
          {
            required: true,
            min: 10,
            message: "不能少于10个字",
            trigger: "blur"
          }
        ]
      }
    };
  },
   methods: {
     addUnderLine() {
       this.$set(this.Question, "q", this.Question.q + "____")
       this.$refs.qInput.focus()
     },
     handleSubmit(name) {
       this.$refs[name].validate((valid) => {
            if (!valid) {
              this.$Message.error('题目信息不全!');
              return;
            }
            if(this.Question.q.indexOf("____") == -1) {
              this.$Message.error("填空题题目中必须要有空格")
              return
            }
            this.$emit("submitQ", this.Question)
        })
     },
     handleReset() {
       for(var i in this.Question) {
        this.$set(this.Question, i, this.QuestionBackup[i])
      }
     },
   },
   mounted() {
     this.QuestionBackup = {
        type: 3, //选择1 判断2 填空3
        q: "",
        majorID: "",
        correct: "",
        toAnswer: "",
        labels: [],
      }
   },
   props: ["major-data", "label-data"],
};
</script>

<style lang="sass">
  .q-title
    width: 80%
  .addUnderline
    width: 19%
</style>

