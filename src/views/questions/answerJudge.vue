<template>
  <div id="select">
    <p class="title">
      {{ question.title }}
      <Button class="tipoff-btn" @click="tipOff" type="warning"><Icon type="alert"></Icon></Button>
    </p>
    <Steps class="steps" :current="current">
      <Step title="读题" content="认真读题，分析作者意图">
      </Step>
      <Step title="分析" content="请写下你的解答过程"></Step>
      <Step title="给出答案" content="请选择/填写你的答案"></Step>
    </Steps>
    <p v-if="current >= 0" class="q-content">
      <markdown-html :markdown="question.q"></markdown-html>
    </p>
    <div v-if="current >= 1" class="container editor">
      <mavon-editor class="editor" v-model="analysis" :class="{'close': isEditorClose}" placeholder="记录自己的思考分析过程，不超过3K字..." :toolbars="toolbars" ref=md @imgAdd="$imgAdd" @change="updateMD"></mavon-editor>
    </div>
    <ul v-if="current == 2">
      <li class="T" :class="{wrong: wrongs.T,checked: answer=='T'}" @click="checked('T')"><Icon type="checkmark-round"></Icon>正确</li>
      <li class="F" :class="{wrong: wrongs.F,checked: answer=='F'}" @click="checked('F')"><Icon type="close-round"></Icon>错误</li>
    </ul>
    <p class="btn-wrap">
      <Button v-if="current != 0" class="btn" shape="circle" @click="lastStep">
        <span>上一步</span>
      </Button>
      <Button :disabled="btnDisable" class="btn" size="large" shape="circle" :type="btnType" @click="submit">
        <Spin size="large" v-if="loading"></Spin>
        <span v-if="!loading">{{ btnText }}</span>
      </Button>
    </p>
    
    
    
  </div>
</template>
<script>
import { mavonEditor } from "mavon-editor"
import markdownHtml from "../common/markdown-html"
export default {
  data() {
    return {
      answer: "",
      isSubmit: false,
      isRight: false,
      isFalse: false,
      wrongs: {
        T: false,
        F: false,
      },
      loading: false,
      btnType: "info",
      btnText: "下一步",
      btnDisable: false,
      isEditorClose: false,
      analysis: "",
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
      current: 0,
      passed: false,
    }
  },
  methods: {
    $imgAdd(pos, $file) {
      // 将图片上传到服务器.
      const url = this.$API.getService("Upload", "base64UploadUPY");
      this.$API
        .post(url, { img: $file.miniurl, name: $file.name })
        .then(url => {
          let res = url.data.data;
          if (res.code == 0) {
            console.log("上传失败！");
            return;
          }
          this.$refs.md.$img2Url(pos, res);
        });
    },
    updateMD(v) {
      this.analysis = v
    },
    lastStep() {
      this.current--
    },
    checked(val) {
      if(this.isRight || this.btnDisable) return
      this.resetBtn()
      this.resetItem()
      if(val == this.answer){
        this.answer = ""
        return
      }
      this.answer = val
    },
    submit() {
      if(this.current != 2) {
        this.current++
        if(this.current == 2 && this.passed) this.btnText = "你已通过，返回"
        return
      }
      if(this.isRight || this.current == 2 && this.passed) {
        this.back()
        return
      }
      if(this.answer == "") {
        this.btnDisable = true
        let num = 2
        this.btnText = "请选择选项(3)"
        let timer = setInterval(() => {
          if(num == 0) {
            this.resetBtn()
            this.resetItem()
            clearInterval(timer)
            return
          }
          this.btnText = "请选择选项"
          this.btnText = this.btnText + "(" + num + ")"
          num--
        }, 1000)
        return
      }

      if(this.analysis.trim().length < 10) {
        //没有解答过程或者解答过程小于10个字
        this.current = 1
        this.$Message.warning({
          content: '解答过程不得小于10个字',
          duration: 2
        })
        return
      }

      
      this.isSubmit = true
      let uid = localStorage.getItem("uid")
      let data = {
        uid: uid,
        qid: this.question.id,
      }
      if(this.answer == this.question.correct) {
        //用户答题正确
        data.result = true
        this.isRight = true
      } else {
        //用户答题错误
        data.result = false
        this.isFalse = true
      }
      //将解题过程也带进去
      data.analysis = this.analysis
      this.$emit("onAnswer", data)
    },
    tipOff() {
      this.$emit("onTipoff")
    },
    resetBtn() {
      this.isSubmit     = false
      this.isRight      = false
      this.isFalse      = false
      this.btnType      = "info"
      this.btnText      = "提交"
      this.btnDisable   = false
      //将选项的红色标记去掉
      this.wrongs[this.answer] = false
    },
    resetItem() {
      this.$set(this.wrongs, this.answer, "")
    },
    back() {
      this.$router.go(-1)
    },
    setRight() {
      this.answer = this.question.correct
      this.btnType = "success"
      this.isRight = true
      if(this.current != 2)
        this.btnText = "下一步(已通过)"
      else
        this.btnText = "你已通过，返回"
    },
    getPassedStatus() {
      let uid = localStorage.getItem("uid")
      let qid = this.$route.params.id
      let url = this.$API.getService("Usertoq", "isPassed")

      this.$API.post(url, {
        uid: uid,
        qid: qid
      }).then(res => {
        let result = res.data.data
        this.passed = result
      })
    },
  },
  props: ["question", "handeling"],
  mounted() {
    this.getPassedStatus()
  },
  watch: {
    current(now) {
      if(now == 2) {
        if(this.passed) return
        this.btnText = "提交"
      } else {
        this.btnText = "下一步"
      }
    },
    passed(now) {
      if(now) {
        this.setRight()
      }
    },
    answer(now, old) {
      if(!this.passed) return
      this.checked(now)
    },
    handeling(now, old) {
      if(old == false && now == true) {
        //由false变为true
        this.loading = true
        return
      }
      this.loading = false
      if(this.isFalse)
        this.wrongs[this.answer] = true
    },
    loading(now, old) {
      if(!now && this.isRight) {
        this.btnType = "success"
        this.btnText = "回答正确，返回"
      }
      if(!now && this.isFalse) {
        this.btnType = "error"
      }
    },
    isFalse(now, old) {
      //选择错误之后要等待5才能继续
      if(!old && now) {
        this.btnText = "回答错误，请重新选择(5)"
        let num = 5
        this.btnDisable = true
        let timer = setInterval(() => {
          if(num == 0) {
            this.resetBtn()
            clearInterval(timer)
            return
          }
          this.btnText = "回答错误，请重新选择" + "(" + num + ")"
          num--
        }, 1000)
      }
    }
  },
  components: {
    markdownHtml,
    mavonEditor
  },
}
</script>
<style lang="sass">
#select
  .editor
    width: 100%
    height: 100%
    margin-bottom: 2.4rem
  .steps
    margin: 2rem auto
  .ivu-steps
    .ivu-steps-title
      cont-size: 1.6rem
  padding: 2rem
  p.title
    font-size: 2.2rem
    padding-bottom: 1rem
    border-bottom: .1rem solid #e7e7e7
  ul
    li
      width: 100%
      margin: 1rem 0
      font-size: 2rem
      border: .2rem solid #fff
      background: #2d8cf0
      color: #fff
      padding: 1.5rem 3rem
      border-radius: 4rem
      cursor: pointer
    li:hover
      background: #5cadff
    li.checked
      background: #19be6b
    li.checked:hover
      background: #19be6b
    li.wrong
      background: #ed3f14
    li.wrong:hover
      background: #ed3f14
    li
      i
        margin-top: .3rem
        padding-right: .5rem
        margin-right: .5rem
  p.btn-wrap
    text-align: center
    button.btn
      margin-top: 2rem
      padding: 1rem 3rem
      font-size: 2rem
      height: 5.1rem
      overflow: hidden
  .toAnswer
    margin-top: 1rem
    padding: 1.5rem 0
    font-size: 1.8rem
    border-top: .1rem solid #e9e9e9
    span
      background: #e9e9e9
      font-size: 1.7rem
      padding: 1rem 2rem
      border-radius: .5rem
  .tipoff-btn
    margin-left: 1rem
</style>

