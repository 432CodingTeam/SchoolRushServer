<template>
  <div class="solving-card">
      <Row class="liveness-item solving" :gutter="16">
      <Col class="liveness-item-user" :lg="2" :md="2" :sm="2" :xs="5">
        <Poptip class="poptip" trigger="hover" placement="bottom-start">
          <img :src="user.avatar">
          
          <div class="api" slot="content">
            <user-card :uid="user.id"></user-card>
          </div>
        </Poptip>
      </Col>
      <Col :lg="20" :md="20" :sm="22" :xs="19">
        <p class="head">
          <router-link :to="'/home/' + user.id" class="p-link link">{{ user.name }}</router-link>
          正在解决
          <!-- <router-link to="" class="p-link link">{{ target.title }}</router-link> -->
        </p>
        <p class="time">{{ formatTime.getDateDiff(time) }}</p>
        <div class="card-container liveness-content-card">
          <Col class="right-content" :lg="20" :md="20" :sm="21" :xs="17">
            <p class="title">
              <router-link class="link user" :to="'/home/' + user.id">{{ user.name }}</router-link>
              /
              <router-link class="link question" :to="'/question/' + target.id">{{ target.title }}</router-link>
            </p>
            <p class="toAnswer">{{ target.q }}</p>
            <p class="desc">
              <span><Icon type="eye"></Icon> {{ target.challenges }} 浏览</span>
              <span><Icon type="checkmark"></Icon>{{ target.passed }} 已解决</span>
              <span><Icon type="ios-clock"></Icon>{{ formatTime.getDateDiff(target.createtime) }} 发布</span>
            </p>
          </Col>
        </div>
      </Col>
    </Row>
  </div>
</template>
<script>
import formatTime from "../../components/tools/formatTime.js"
import userCard from "../../components/msgCart"
export default {
  data() {
    return {
      formatTime: formatTime
    }
  },
  props: ["target", "user", "time"],
  components: {
    userCard
  }
}
</script>
