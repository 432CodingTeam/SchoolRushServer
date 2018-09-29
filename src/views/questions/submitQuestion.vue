<template>
  <div id="submit-panel">
    <Row>
      <Col span="24" class="title">
      <router-link :to="'question/'+id" id="question-title">
        {{title}}</router-link>
      </Col>
    </Row>
    <Row>
      <Col span="24">
      <Icon type="checkmark-round" size="15" color="#2d8cf0"></Icon>
      <router-link :to="'question/'+id" id="question-msg">
        提交成功，点击查看问题</router-link>
      </Col>
    </Row>
    <Row>
      <Col span="24">
      <ul id="share-list">
        <li>
          <Button type="primary" shape="circle" size="large" @click="shareToWB">
            <span class="question-share">微博分享</span>
          </Button>
        </li>
        <li>
          <Button type="primary" shape="circle" size="large" @click="shareToDB">
            <span class="question-share">豆瓣分享</span>
          </Button>
        </li>
        <li>
          <Button type="primary" shape="circle" size="large" @click="shareToQZone">
            <span class="question-share">空间分享</span>
          </Button>
        </li>
        <li>
          <Button type="primary" shape="circle" size="large" @click="copySelf">
            <span class="question-share">复制链接</span>
          </Button>
        </li>
        <input type="text" id="question-url">
      </ul>
      </Col>
    </Row>
    <Row id="edge">
      <Col span="24">

      </Col>
    </Row>
  </div>
</template>

<script>
export default {
  data() {
    console.log(this.$route.query)
    let title = this.$route.query.title;
    let id = this.$route.query.id;
    return {
      title: title,
      id: id
    };
  },
  methods: {
    shareToQZone() {
      window.open(
        "https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=schoolrush.iimt.me/qustion/" +
          this.id,
        "_blank"
      );
    },
    shareToWB() {
      window.open(
        "http://service.weibo.com/share/share.php?appkey=1881139527&url=https://schoolrush.iimt.me/qustion/" +
          this.id +
          "&title=" +
          this.title,
        "_blank"
      );
    },
    shareToDB() {
      window.open(
        "https://www.douban.com/share/service?name=" +
          this.title +
          "&url=schoolrush.iimt.me/qustion/" +
          this.id,
        "_blank"
      );
    },
    copySelf() {
      var input = document.getElementById("question-url");
      input.value = "schoolrush.iimt.me/question/" + this.id; // 修改文本框的内容
      input.select(); // 选中文本
      document.execCommand("copy"); // 执行浏览器复制命令
      alert("复制成功");
    }
  },
};
</script>
<style>
#submit-panel {
  margin-top: 9rem;
  text-align: center;
  color: #495060;
}
#question-title {
  font-weight: bolder;
  color: black;
  font-size: 1.75em;
}
#question-msg {
  font-weight: bold;
}
#submit-panel .ivu-col {
  margin: 1.75rem 0;
}
#share-list {
  text-align: center;
}
#share-list li {
  display: inline-block;
  margin: 0 1.5rem;
  cursor: pointer;
}
.question-share {
  padding: 0 1.5rem;
  font-weight: bolder;
}
#edge {
  background: rgb(250, 250, 250);
  height: 8rem;
  border-radius: 50% 50% 0 0;
}
#question-url {
  position: absolute;
  top: -9999px;
  left: -9999px;
}
</style>

