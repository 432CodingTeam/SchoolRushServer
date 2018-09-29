<template>
  <!-- 问题展示卡片组件 -->
  <div class="question-card">
    <div class="row content-container col-lg-9 col-md-9">
      <div class="card-container">
        <div class="card-left-container flex left">
          <div class="flex-container flex">
            <div class="label-container flex">
              <Tag class="Tag" v-for="item in questionInfo.labelsInfo" :key="item.id"> 
                <router-link :to="'/label/' + item.id">{{ item.name }}</router-link>
              </Tag>
            </div>
            <div class="breadcrumb-container flex">
              <Breadcrumb separator="-">
                <BreadcrumbItem to="/index">{{ questionInfo.majorParentName }}</BreadcrumbItem>
                <BreadcrumbItem to="/index">{{ questionInfo.majorName }}</BreadcrumbItem>
              </Breadcrumb>
            </div>
          </div>
        </div>
        <div class="card-right-container right">
          <p class="title">
            <router-link :to="'/question/'+questionInfo.id">{{ formatQ(questionInfo.title) }}</router-link>
          </p>
          <p class="q-author-say">
            <!-- <router-link to="/home"><Avatar size="small" shape="square" :src="questionInfo.useravatar" /></router-link> -->
            <span class="author-name">{{ questionInfo.q }}</span>
          </p>
          <p class="q-difficulty">
            <span>难度：</span>
            <Rate disabled allow-half v-model="questionInfo.levels"></Rate>
          </p>
          <p class="q-set-info">
            <span>{{ questionInfo.challenges }}人挑战过</span>
            <span>{{ questionInfo.passed }}人已通过</span>
            <span>通过率：{{ formatRate(questionInfo.passedrate) }}</span>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios"
export default {
  data() {
    this.questionInfo.levels = parseFloat(this.questionInfo.levels)
    return {
      labels: [],
    }
  },
  props:["tags", "question-info"],
  methods: {
    getColor(id) {
      const colors = ["#fff"]
      let color = colors[id % (colors.length)]
      return color
    },
    formatQ(text) {
      //将字数超出限制的去掉
      let maxLen = 30
      if(text.length < maxLen)
        return text
      return text.substr(0,maxLen) + "..."
    },
    formatRate(num) {
      if(num.indexOf('.') == -1) return num
      return num.substring(0,num.indexOf(".") + 3) + "%"
    },
    tounicode(data){
      if(data == '') return;
      var str =''; 
      for(var i=0;i<data.length;i++)
      {
          str+="\\u"+parseInt(data[i].charCodeAt(0),10).toString(16);
      }
      return str;
    },
  },
  created() {
  }
}
</script>
<style lang="sass">
.question-card
  .card-left-container
    .flex-container
      .label-container
        .Tag
          border-radius: 2rem
          padding: 0 1.3rem
          .ivu-tag-text
            a
              color: #666
        .Tag:hover
          border: .1rem solid #2d8cf0
          background: #fff
          .ivu-tag-text
            a
              color: #333
</style>
<style>
.a {
  background: #5cadff;
}
</style>


