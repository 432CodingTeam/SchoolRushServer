import Vue from 'vue';
import iView from 'iview';
import VueRouter from 'vue-router';
import axios from 'axios'
import Routers from './router';
import Util from './libs/util';
import App from './app.vue';
import API from "../HTTP/main"
import Cookie from "./static/plugins/cookie"
import 'iview/dist/styles/iview.css';


Vue.use(VueRouter)
Vue.use(iView)

Vue.prototype.$axios = axios
Vue.prototype.$API = API


// 路由配置
const RouterConfig = {
    mode: 'history',
    //base: '/SchoolRushServer/',
    routes: Routers
};
const router = new VueRouter(RouterConfig);

//路由守卫
router.beforeEach((to, from, next) => {
    //进度条
    iView.LoadingBar.start()
    Util.title(to.meta.title)
    //如果是去登陆页，直接next
    if (to.path == "/login") {
        next()
        return
    }
    //判断当前登录身份
    let cookie = Cookie.getCookie('sr_token')
    if(cookie != null && cookie != "") {
        //有cookie
        API.checkCookie(cookie).then( res => {
            if(!res.data.data) {
                //cookie无效 跳转到登录页面
                next("/login")
                return
            } else {
                localStorage.setItem("uid",res.data.data.id)
                localStorage.setItem('userinfo', res.data.data)
                //正常next
                next()
                return
            }
        })
    } else {
        //无cookie 跳转到登录页面
        next({path: "/login"})
        return
    }
});

router.afterEach(() => {
    iView.LoadingBar.finish();
    window.scrollTo(0, 0);
});



new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});