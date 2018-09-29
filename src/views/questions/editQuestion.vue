<template>
  <div id="editQuestion">
    <Input class="title" v-model="Question.title" type="text" size="large" placeholder="问题标题，不超过100字"></Input>
    <mavon-editor class="editor" :class="{'close': isEditorClose}" placeholder="开始编辑问题内容..." :toolbars="toolbars" ref=md @imgAdd="$imgAdd" @change="updateMD"></mavon-editor>
    <Form ref="Question" :model="Question" :rules="ruleValidate" :label-width="80">
      <FormItem label="专业分类" prop="majorID">
        <Select
          size="large"
          v-model="Question.majorID"
          remote
          :remote-method="searchMajor"
          :loading="searchMajorLoading"
          placeholder="可搜索专业哦"
          filterable>
          <Option v-for="item in majorList" :value="item.id" :key="item.id">{{ item.name }}</Option>
        </Select>
      </FormItem>
      <FormItem label="标签" prop="labels">
        <Select
          size="large"
          v-model="Question.labels"
          remote
          :remote-method="searchLabel"
          :loading="searchLabelLoading"
          placeholder="可多选"
          multiple
          filterable>
          <Option v-for="item in labelList" :value="item.id" :key="item.id">{{ item.name }}</Option>
          <Button class="addLabel" type="primary" @click="isAddLabel = true">搜索不到，添加此标签</Button>
        </Select>
        
        <template>
          <Modal
            class="newLabel"
            v-model="isAddLabel"
            :closable="true"
            @on-ok="handleAddLabel">
            <Input v-model="newLabelName" placeholder="标签名">
              <Button type="info" slot="append">添加</Button>
            </Input>
          </Modal>
        </template>
      </FormItem>
      <FormItem label="题型" prop="type">
        <RadioGroup size="large" type="button" v-model="Question.type">
          <!-- <Radio label=4>主观题</Radio> TODO: 启用主观题 -->
          <Radio label=1>选择题</Radio>
          <Radio label=2>判断题</Radio>
          <Radio label=3>填空题</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem v-if="Question.type == 1" label="选项A" prop="A">
        <Input size="large" v-model="Question.A" placeholder="选项A"></Input>
      </FormItem>
      <FormItem v-if="Question.type == 1" label="选项B" prop="B">
        <Input size="large" v-model="Question.B" placeholder="选项B"></Input>
      </FormItem>
      <FormItem v-if="Question.type == 1" label="选项C" prop="C">
        <Input size="large" v-model="Question.C" placeholder="选项C"></Input>
      </FormItem>
      <FormItem v-if="Question.type == 1" label="选项D" prop="D">
        <Input size="large" v-model="Question.D" placeholder="选项D"></Input>
      </FormItem>
      <FormItem v-if="Question.type == 1" label="正确选项" prop="correct">
        <RadioGroup size="large" v-model="Question.correct">
          <Radio label="A">A</Radio>
          <Radio label="B">B</Radio>
          <Radio label="C">C</Radio>
          <Radio label="D">D</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem v-if="Question.type == 3" label="正确答案" prop="correct">
        <Input size="large" v-model="Question.correct" placeholder="正确答案"></Input>
      </FormItem>
      <FormItem v-if="Question.type == 2" label="正确选项" prop="correct">
        <RadioGroup size="large" v-model="Question.correct">
          <Radio label="T">正确</Radio>
          <Radio label="F">错误</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem>
        <Button size="large" type="primary" @click="handleSubmit('Question')">完成</Button>
        <Button size="large" type="ghost" @click="handleReset('Question')" style="margin-left: 8px">重置</Button>
      </FormItem>
    </Form>

  </div>
</template>
<script>
import { mavonEditor } from "mavon-editor";
import "mavon-editor/dist/css/index.css";
export default {
  data() {
    return {
      toolbars: {
        bold: false, // 粗体
        italic: false, // 斜体
        header: false, // 标题
        underline: false, // 下划线
        mark: true, // 标记
        strikethrough: true, // 中划线
        superscript: true, // 上角标
        subscript: true, // 下角标
        quote: false, // 引用
        ol: false, // 有序列表
        ul: false, // 无序列表
        link: false, // 链接
        imagelink: true, // 图片链接
        code: true, // code
        table: true, // 表格
        fullscreen: false, // 全屏编辑
        readmodel: true, // 沉浸式阅读
        htmlcode: false, // 展示html源码
        help: true, // 帮助
        /* 1.3.5 */
        undo: false, // 上一步
        redo: false, // 下一步
        trash: false, // 清空
        save: true, //TODO: 保存（触发events中的save事件）
        /* 1.4.2 */
        navigation: true, // 导航目录
        /* 2.1.8 */
        alignleft: true, // 左对齐
        aligncenter: true, // 居中
        alignright: true, // 右对齐
        /* 2.2.1 */
        subfield: true, // 单双栏模式
        preview: true // 预览
      },
      isEditorClose: false,
      isAddLabel: false,
      searchMajorLoading: false,
      searchLabelLoading: false,
      newLabelName: "",
      Question: {
        type: 4, //选择1 判断2 填空3 主观题4
        majorID: "",
        q: "",
        A: "",
        B: "",
        C: "",
        D: "",
        correct: "",
        toAnswer: "",
        labels: [],
        title: ""
      },
      QuestionBackup: {},
      ruleValidate: {
        type: [
          {
            required: true,
            message: "题型必选",
            trigger: "blur"
          }
        ],
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
        A: [
          {
            required: true,
            message: "选项A不能为空",
            trigger: "blur"
          }
        ],
        B: [
          {
            required: true,
            message: "选项B不能为空",
            trigger: "blur"
          }
        ],
        C: [
          {
            required: true,
            message: "选项C不能为空",
            trigger: "blur"
          }
        ],
        D: [
          {
            required: true,
            message: "选项D不能为空",
            trigger: "blur"
          }
        ],
        correct: [
          {
            required: true,
            message: "正确选项不能为空",
            trigger: "change"
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
      },
      majorList: [],
      labelList: [],
    };
  },
  methods: {
    $imgAdd(pos, $file) {
      // 将图片上传到服务器.
      const url = this.$API.getService("Upload", "base64UploadUPY");
      this.$refs.md.$img2Url(pos, "上传中...")
      this.$API
        .post(url, { img: $file.miniurl, name: $file.name })
        .then(url => {
          let res = url.data.data;
          if (res.hasOwnProperty("code") && res.code == 0) {
            this.$refs.md.$img2Url("上传中...", "上传失败！");
            return
          }
          this.$refs.md.$img2Url("上传中...", res)
        })
    },
    searchMajor(query) {
      if(query == "") return
      this.searchMajorLoading = true
      let url = this.$API.getService('Major', 'getBylike')

      this.$API.post(url, {query: query})
      .then( res => {
        this.majorList = res.data.data
        this.searchMajorLoading = false
      })
    },
    searchLabel(query) {
      if(query == "") return
      this.newLabelName = query
      this.searchLabelLoading = true
      let url = this.$API.getService('Label', 'getByQueryName')

      this.$API.post(url, {query: query})
      .then( res => {
        this.labelList = res.data.data
        
        this.searchLabelLoading = false
      })
    },
    handleSubmit(name) {
      if (this.Question.title.trim() == "" || this.Question.q.trim().length < 20) {
        this.$Notice.warning({
          title: "标题和内容不能为空",
          desc: "问题内容不能少于20字"
        });
        return
      }
      this.$refs[name].validate(valid => {
        if (!valid) {
          this.$Message.error("题目信息不全!");
          return;
        }
        let question = this.$emit("submitQ", this.Question);
        return true;
      });
    },
    handleAddLabel() {
      console.log(this.Question.labels)
      
      let url = this.$API.getService("Label", "add")

      this.$API.post(url, {name: this.newLabelName})
      .then(res => {
        console.log(res)
        this.searchLabel(this.newLabelName)
        this.newLabelName = ""
      })
    },
    updateMD(value, render) {
      this.Question.q = value;
    }
  },
  mounted() {},
  components: {
    mavonEditor
  },
};
</script>
<style lang="sass">
#editQuestion
  padding: 0 1rem
  padding-bottom: 15rem
  .title
    margin-bottom: 2.4rem
  .editor
    width: 100%
    height: 100%
    margin-bottom: 2.4rem
  .close
    height: 40rem
</style>
