<template>
  <!-- 小专业排行榜页面 -->
  <div class="app">
    <div class="dewrap container grid-container">
      <div class="grid-no-padding col-lg-11 col-md-12 col-sm-12 col-xs-12">
        <div class="rank-container">
          <Row :gutter="10">
            <Col class="rank-row" v-for="j in 4" :lg="8" :md="8" :sm="12" :xs="24" :key="j">
              <Col class="rank-item-container card">
                <div class="rank-item-title">
                  工学
                </div>
                <Col span="24">
                  <Col class="top-three" span="24">
                    <Col class="top-second" span="8">
                      <div class="tops-wrap">
                        <p class="second">
                          <img src="../../static/img/shufe.jpg" alt="">
                        </p>
                        <p class="top-q-num">4210题</p>
                        <p>上海财经大学</p>
                      </div>
                    </Col>
                    <Col class="top-first" span="8">
                      <div class="tops-wrap">
                        <p class="first">
                          <img src="../../static/img/shufe.jpg" alt="">
                        </p>
                        <p class="top-q-num">4562题</p>
                        <p>上海财经大学</p>
                      </div>
                    </Col>
                    <Col class="top-third" span="8">
                      <div class="tops-wrap">
                        <p class="third">
                          <img src="../../static/img/avatar.jpg" alt="">
                        </p>
                        <p class="top-q-num">3956题</p>
                        <p>上海财经大学</p>
                      </div>
                    </Col>
                  </Col>
                  <Col span="24">
                    <ul class="rank-others">
                      <li v-for="i in 6">
                        <p>
                          <span class="rank-num">{{3+i}}</span>
                          <span class="campus-name">学校名</span>
                          <span class="q-num">45121</span>
                        </p>
                      </li>
                    </ul>
                  </Col>
                </Col>
              </Col>
            </Col>
          </Row>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {};
</script>
<style lang="sass">
.rank-container
  .top-first
    background: url("../../static/img/rank-1-r.png") no-repeat
    background-size: 45%
    background-position: 100% 4%
    color: #FDD75A
    font-weight: bold
    letter-spacing: .1rem
    font-size: 1.5rem
    text-shadow: 0 .2rem .5rem #e5e5e5
  .top-second
    background: url("../../static/img/rank-2-r.png") no-repeat
    background-size: 40%
    background-position: 85% 19%
    color: #CAD0D5
    font-weight: bold
    font-size: 1.4rem
    letter-spacing: .1rem
    text-shadow: 0 .2rem .4rem #e9e9e9
  .top-third
    background: url("../../static/img/rank-3-r.png") no-repeat
    background-size: 40%
    background-position: 85% 19%
    color: #F3AB85
    font-weight: bold
    font-size: 1.4rem
    text-shadow: 0 .2rem .4rem #e9e9e9
  .rank-item-container
    overflow: hidden
    background: #fff
    padding-bottom: 1rem
  .rank-item-title
    padding: 1rem 2rem
    border-bottom: .1rem solid #e9e9e9
  .tops-wrap
    padding-top: 3rem
    p.top-q-num
      padding-top: .5rem
      font-size: 1.6rem
    p
      text-align: center
      img
        width: 6rem
        border-radius: 50%
        border: .2rem solid #fff
        box-shadow: 0 .3rem .5rem 0 #63B8FF
    $first-height: 6rem
    $other-height: 4rem
    p.first
      img
        width: 8rem
    p.second, p.third
      padding-top: $first-height - $other-height
  ul.rank-others
    margin-top: 1.5rem
    li.rank-more
      text-align: center
      line-height: 3.5rem
      div
        font-size: 1.4rem
        margin-right: .5rem
        line-height: 3.5rem
        display: inline-block
        height: 3.5rem
        width: 10rem
        background: #ccc
        border-radius: 1.5rem
    li
      padding: 1rem 0
      p
        span
          clear: both
        .rank-num
          width: 2rem
          height: 2rem
          padding: .7rem 1.1rem
          padding-bottom: .6rem
          background: #E9E9E9
          border-radius: 50%
          clear: both
          overflow: hidden
          font-size: 1.7rem
          font-weight: bold
        .campus-name
          font-size: 1.3rem
          margin-left: 1.5rem
        .q-num
          font-size: 1.5rem
          float: right
        .q-num:after
          content: "题"
          font-size: 1.4rem
  .rank-row
    margin-top: 1rem
.app
  .dewrap
    margin-top: 0
</style>
