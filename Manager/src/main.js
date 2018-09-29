import Vue from 'vue';
import App from './App';
import router from './router';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-default/index.css';    // 默认主题
// import '../static/css/theme-green/index.css';       // 浅绿色主题
import "babel-polyfill";
import API from "../static/API/main"

Vue.prototype.$API = API

Vue.use(ElementUI)

//每次路由跳转之前首先检查当前登录状态是否过期
router.afterEach((to, from) => {
    //TODO: 先判断是否已经登陆
})

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');