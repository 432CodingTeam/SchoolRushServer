<template>
  <div id="markdown" v-html="html">
  </div>
</template>
<script>
import {mavonEditor} from 'mavon-editor'
let md = mavonEditor.mixins[0].data().s_markdown
export default {
  data() {
    return {
      html: "",
    }
  },
  mounted() {
    this.html = md.render(this.markdown)
  },
  watch: {
    markdown(now, old) {
      this.html = md.render(now)
    }
  },
  props: ["markdown"]
}
</script>
<style lang="sass">
#markdown
  p
    img
      display: inherit
      border-radius: .3rem
      margin: 2rem auto
      max-height: 30rem
  .hljs
    boder-radius: .3rem
</style>

