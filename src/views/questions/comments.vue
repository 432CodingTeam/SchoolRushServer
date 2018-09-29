<template>
  <div id="comment">
    <!-- 问题页面的评论组件 -->
    <p class="total"><span></span> 经验</p>
    <div class="container editor">
      <Button class="submit-btn" type="primary" shape="circle" size="large" @click="submitComment" icon="paper-airplane">发表</Button>
      <mavon-editor class="editor" :class="{'close': isEditorClose}" placeholder="发表自己的经验与见解，20-1W字..." :toolbars="toolbars" ref=md @imgAdd="$imgAdd" @change="updateMD"></mavon-editor>
    </div>
    <div class="container">
      <a-comment v-for="item in comments" :key="item.id" :user="item.userInfo" :comment="item"></a-comment>
    </div>
  </div>
</template>
<script>
import aComment from "./aComment"
import { mavonEditor } from "mavon-editor";
import "mavon-editor/dist/css/index.css";
export default {
  data() {
    return {
      newComment: "",
      user: {
        avatar: "https://i.loli.net/2017/08/21/599a521472424.jpg",
        name: "iimT"
      },
      comments: [],
      comment: [{
        id: "1",
        content: "![5aafbbaaa15657001baf1821.png](http://p6a87gauo.bkt.clouddn.com/user_5a5f315d6a107efd3fa838b0909c1fc8.png)图中的字母是什么？【大小写敏感】",
        time: "2018-04-16 19:36:32",
        agree: 45,
        reply: "",
        disagree: 3,
        userInfo: {
          avatar: "",
        }
      }],
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
    updateMD(value, render) {
      this.newComment = value;
    },
    submitComment() {
      if(this.newComment.trim().length < 10) {
        this.$Message.warning("字数太少，至少10字")
        return
      }
      let uid = parseInt(localStorage.getItem("uid"))
      let data = {
        uid: uid,
        qid: this.qid,
        content: this.newComment.trim()
      }
      let url = this.$API.getService("Comment", "add")

      this.$API.post(url, data).then( res => {
        console.log(res)
        if(res.data.data.id != undefined) {
          this.$Message.success("发表成功！")
          this.newComment = ""
          //置空编辑器，获取最新评论
          this.$refs.md.d_value = ""
          this.getComments()
        }else{
          this.$Message.error("发表失败，请检查网络与最大字数")
        }
      }).catch(err => {
        this.$Message.error("发表失败，请检查网络与最大字数")
        console.log(err)
      })
    },
    getComments() {
      let that = this
      let url  = this.$API.getService("Comment", "GetCommentsPage")

      this.$API.post(url, {qid: this.qid, page: 1, num: 20}).then((res)=> {
        that.comments = res.data.data
      }).catch((err) => {
        console.log(err)
      })
    },
  },
  mounted() {
    this.getComments()
  },
  components: {
    aComment,
    mavonEditor
  },
  props: ["qid"]
}
</script>

<style lang="sass">
#comment
  padding: 2rem
  .container
    margin-top: 0
  .a-comment
    padding: 2rem 0
    border-bottom: .1rem solid #eee
  .total
    padding-bottom: 1rem
    font-size: 1.8rem
    font-weight: bold
  .name
    margin-left: .5rem
    color: #495060
  .name:hover
    color: #2d8cf0
  .time
    text-align: right
  .content
    padding: 2rem
    padding-bottom: 1rem
    p.coolapse
      font-size: 1.7rem
      background: #fff
  .editor
    width: 100%
    height: 100%
    margin-bottom: 2.4rem
  .close
    height: 20rem
  .editor
    position: relative
    .submit-btn
      position: absolute
      bottom: 3rem
      right: .5rem
      z-index: 1501
</style>

