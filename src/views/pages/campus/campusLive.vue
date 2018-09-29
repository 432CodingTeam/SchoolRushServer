<template>
  <div class="app campus-live">
    <div v-for="item in data" :key="item.id">
      <join-card v-if="item.action=='1'" :target="item.targetInfo" :user="item.userInfo" :time="item.time"></join-card>
      <solving-card v-if="item.action=='2'" :target="item.targetInfo" :user="item.userInfo" :time="item.time"></solving-card>
      <shared-card v-if="item.action=='5'" :target="item.targetInfo" :user="item.userInfo" :time="item.time"></shared-card>
      <solved-card v-if="item.action=='3'" :target="item.targetInfo" :user="item.userInfo" :time="item.time"></solved-card>
      <followed-card v-if="item.action=='4'" :target="item.targetInfo" :user="item.userInfo" :time="item.time"></followed-card>
    </div>
  </div>
</template>
<script>
import joinCard from "./join-card"
import followedCard from "./followed-card"
import solvedCard from "./solved-card"
import solvingCard from "./solving-card"
import sharedCard from "./shared-card"
import lazyCard from "../../components/tools/lazy-question-card"
export default {
  data() {
    return {
      isLoading: false,
      nomore: false,
    }
  },
  methods: {
     handleReachBottom() {
       this.$emit("loadMore")
    }
  },
  props: ["data"],
  components: {
    joinCard,
    followedCard,
    solvedCard,
    solvingCard,
    sharedCard,
    lazyCard
  }
}
</script>
