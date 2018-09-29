<template>
  <div id="bug">
    <header class="site-header">
      <div class="wrapper">
        <h1 class="site-header__title">School Rush 更新历史</h1>
      </div>
    </header>
    <section class="timeline">
      <div class="wrapper">
        <div class="timeline__item timeline__item--1 timeline__item-bg add">
          <div class="timeline__item__station" @click="addUpdate">
            +
          </div>
        </div>
        <div v-for="item in history" :key="item.id" :class="'timeline__item timeline__item--' + item.id%17 + ' timeline__item-bg'">
          <div class="timeline__item__station">
            <Avatar size="large" :src="item.avatar" />
          </div>
          <div class="timeline__item__content">
            <h2 class="timeline__item__content__date">{{getFormatTime(item.time)}}</h2>
            <p class="timeline__item__content__description">{{item.content}}</p>
          </div>
        </div>
      </div>
      <!-- 添加记录对话框 -->
      <Modal v-model="addHistory">
        <p slot="header" style="color:#7646b5;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>添加</span>
        </p>
        <div style="text-align:center">
            <Select v-model="type">
              <Option v-for="item in typeList" :value="item.id" :key="item.id">{{ item.value }}</Option>
            </Select>
            <Input v-model="content" type="textarea" :rows="4" :autosize="{minRows: 4}" placeholder="请输入内容..." />
        </div>
        <div slot="footer">
            <Button type="success" size="large" long :loading="addHistoryLoading" @click="add">确认</Button>
        </div>
    </Modal>
    </section>
  </div>
</template>
<script>
import formatTime from "../components/tools/formatTime.js"
export default {
  data() {
    return {
      page: 1,
      addHistory: false,
      addHistoryLoading: false,
      type: 0,
      content: '',
      typeList: [
        {
          id: 1,
          value: '更新'
        },
        {
          id: 2,
          value: 'BUG提交'
        },
        {
          id: 3,
          value: '建议'
        }],
      history: []
    }
  },
  methods: {
    customWayPoint(className, addClassName, customOffset) {
      console.log(window.$);
      
      $ = window.$
      var waypoints = $(className).waypoint({
        handler: function(direction) {
          if (direction == "down") {
            $(className).addClass(addClassName);
          } else {
            $(className).removeClass(addClassName);
          }
        },
        offset: customOffset
      });
    },
    add() {
      this.addHistoryLoading = true

      let url = this.$API.getService("Bugs", "add")
      let uid = localStorage.getItem('uid')
      let data = {
        type: this.type,
        uid: uid,
        content: this.content
      }

      this.$API.post(url,data)
      .then( res => {
        window.location.reload()
      })
    },
    addUpdate() {
      this.addHistory = true
    },
    getUpdatePage(page) {
      let url = this.$API.getService("Bugs", "getAllByPage")

      this.$API.post(url, {page: page})
      .then(res => {
        console.log(res)
        let data = res.data.data
        for (let i in data) {
          this.history.push(data[i])
        }
        this.page++
      })
    },
    getFormatTime(time) {
      return formatTime.getDateDiff(time)
    }
  },
  mounted() {
    this.getUpdatePage(this.page)
  }
}
</script>
<style>
  @import url('https://fonts.googleapis.com/css?family=Nunito:300,400,700');
  * {
    box-sizing: border-box;
  }
  
  #bug {
    font-family: 'Nunito', sans-serif;
    background: #292929;
  }
  
  img {
    max-width: 100%;
    height: auto;
  }
  
  /* _site-header.css */
  .site-header {
    text-align: center;
    padding: 40px 0;
  
  }
  .site-header__title {
    font-size: 36px;
    color: #fff;
  }
  
  /* _wrapper.css */
  .wrapper {
    padding-left: 18px;
    padding-right: 18px;
    max-width: 1236px;
    margin-left: auto;
    margin-right: auto;
  }
  
  
  /* _timeline.css */
  .timeline {
    position: relative;
    margin: 30px auto;
    padding: 60px 0;
    padding-bottom: 100px;
    margin-bottom: 0;
  }
  .timeline::before {
    content: "";
    position: absolute;
    top: 0;
    left: 10%;
    width: 4px;
    height: 100%;
    background-color: #8d94b1;
  }

  .ivu-modal .ivu-select {
    width: 100%;
    margin-bottom: 1rem;
  }
  @media (min-width: 800px){
    .timeline::before{
      left: 50%;
      margin-left: -2px;
    }
  }
  .timeline__item {
    margin-bottom: 100px;
    position: relative;
  }
  .timeline__item::after{
    content: "";
    clear: both;
    display: table;
  }
  .timeline__item:nth-child(2n) .timeline__item__content {
    float: right;
  }
  .timeline__item:nth-child(2n) .timeline__item__content::before {
    content: '';
    right: 40%;
  }
  @media (min-width: 800px){
    .timeline__item:nth-child(2n) .timeline__item__content::before{
      left: inherit;
    }
  }
  .timeline__item:nth-child(2n) .timeline__item__content__date {
    background-color: #b292c5;
  }
  .timeline__item:nth-child(2n) .timeline__item__content__description {
    color: #b292c5;
  }
  .timeline__item:last-child {
    margin-bottom: 0;
  }
  .timeline__item-bg {
    -webkit-transition: all 1s ease-out;
    transition: all 1s ease-out;
    color: #fff;
  }
  .timeline__item-bg:nth-child(2n) .timeline__item__station {
    background-color: #7646b5;
  }
  .timeline__item-bg:nth-child(2n) .timeline__item__content {
    background-color: #7646b5;
  }
  .timeline__item-bg:nth-child(2n) .timeline__item__content::before {
    background-color: #b292c5;
  }
  .timeline__item-bg:nth-child(2n) .timeline__item__content__description {
    color: #fff;
  }
  .timeline__item-bg .timeline__item__station {
    background-color: #0072e8;
  }
  .timeline__item-bg .timeline__item__content {
    background-color: #0072e8;
  }
  .timeline__item-bg .timeline__item__content::before {
    background-color: #65adb7;
  }
  .timeline__item-bg .timeline__item__content__description {
    color: #fff;
  }
  .timeline__item__station {
    background-color: #9aa0b9;
    position: absolute;
    border-radius: 50%;
    padding: 10px;
    top: 0;
    left: 10%;
    z-index: 2;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
    line-height: .65;
    margin-left: -33px;
    border: 4px solid #8d94b1;
    -webkit-transition: all .3s ease-out;
    transition: all .3s ease-out;
    cursor: pointer;
  }
  .add {
    height: 100px;
  }
  .add:hover {
    transform: scale(1.07);
  }
  .timeline__item__station .ivu-avatar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }
  @media (min-width: 800px){
    .timeline__item__station{
      left: 50%;
      margin-left: -30px;
      width: 60px;
      height: 60px;
      padding: 15px;
      border-width: 6px;
    }
  }
  .timeline__item__content {
    width: 80%;
    background: #fff;
    padding: 20px 30px;
    border-radius: 6px;
    float: right;
    -webkit-transition: all .3s ease-out;
    transition: all .3s ease-out;
  }
  @media (min-width: 800px){
    .timeline__item__content{
      width: 40%;
      float: inherit;
      padding: 30px 40px;
    }
  }
  .timeline__item__content::before {
    content: '';
    position: absolute;
    left: 10%;
    background: #8d94b1;
    top: 20px;
    width: 10%;
    height: 4px;
    z-index: 1;
    -webkit-transition: all .3s ease-out;
    transition: all .3s ease-out;
  }
  @media (min-width: 800px){
    .timeline__item__content::before{
      left: 40%;
      top: 30px;
      height: 4px;
      margin-top: -2px;
    }
  }
  .timeline__item__content__date {
    margin: 0;
    padding: 8px 12px;
    font-size: 15px;
    margin-bottom: 10px;
    background-color: #65adb7;
    color: #fff;
    display: inline-block;
    border-radius: 4px;
    border: 2px solid #fff;
  }
  .timeline__item__content__description {
    margin: 0;
    padding: 0;
    font-size: 16px;
    line-height: 24px;
    font-weight: 300;
    color: #65adb7;
  }
  @media (min-width: 800px){
    .timeline__item__content__description{
      font-size: 19px;
      line-height: 28px;
    }
  }
  
  /* _site-footer.css */
  .site-footer {
    padding: 50px 0 200px 0;
  
  }
  .site-footer__text {
    color: #e6e6e6;
    font-size: 14px;
    text-align: center;
  }
  .site-footer__text__link {
    color: #8287a9;
  }
</style>