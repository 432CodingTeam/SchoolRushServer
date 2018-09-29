<template>
    <div class="wrapper">
        <v-head></v-head>
        <div class="content">
            <transition name="move" mode="out-in"><router-view></router-view></transition>
        </div>
    </div>
</template>

<script>
    import vHead from './header.vue'
    export default {
        components:{
            vHead
        }
    }
</script>
