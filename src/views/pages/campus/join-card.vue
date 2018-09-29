<template>
  <div class="join-card">
      <Row class="liveness-item join" :gutter="16">
      <Col class="liveness-item-user" :lg="2" :md="2" :sm="2" :xs="4">
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
          加入了
          <router-link :to="'/campus/' + target.id" class="p-link link">{{ target.name }}</router-link>
        </p>
        <p class="time">{{ formatTime.getDateDiff(time) }}</p>
        <div class="card-container liveness-content-card">
          <Col class="left-badge" :lg="3" :md="3" :sm="3" :xs="7">
            <img :src="target.badge" :alt="target.name">
          </Col>
          <Col class="right-content" offset="1" :lg="19" :md="19" :sm="20" :xs="16">
            <p class="title">
              <router-link class="link campus" to="">{{ target.name }}</router-link>
              <router-link class="link major" to="">{{ user.majorName }}</router-link>
            </p>
            <p class="desc">
              <span><Icon type="person-stalker"></Icon> {{ target.mambers }} 成员</span>
              <span><Icon type="paintbrush"></Icon>{{ target.shared }} 问题</span>
              <span><Icon type="checkmark"></Icon>{{ target.solved }} 解决</span>
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



