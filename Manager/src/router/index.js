import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: 'history',
    // base: '/SchoolRushServer/admin/',
    routes: [
        {
            path: '/',
            redirect: '/login'
        },
        {
            path: '/Situation',
            component: resolve => require(['../components/common/Home.vue'], resolve),
            children:[
                {
                    path: '/',
                    component: resolve => require(['../components/page/Situation.vue'], resolve)
                },
                {
                    path: '/manageBlog',
                    component: resolve => require(['../components/page/ManageBlog.vue'], resolve)
                },
                {
                    path: '/publish',
                    component: resolve => require(['../components/page/Publish.vue'], resolve)     // Vue-Quill-Editor组件
                },
                {
                    path: '/publishSuccess',
                    name: 'publishSuccess',
                    component: resolve => require(['../components/page/PublishSuccess.vue'], resolve)     // Vue-Quill-Editor组件
                },
                {
                    path: '/uploadPublish',
                    component: resolve => require(['../components/page/UploadPublish.vue'], resolve)       // Vue-Core-Image-Upload组件
                },
                {
                    path: '/basecharts',
                    component: resolve => require(['../components/page/BaseCharts.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/cate',
                    component: resolve => require(['../components/page/ManageCate.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/allQuestions',
                    component: resolve => require(['../components/page/question/allQuestions.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/editQuestion',
                    component: resolve => require(['../components/page/question/editQuestion.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/reviewQuestion',
                    component: resolve => require(['../components/page/question/reviewQuestion.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/userManage',
                    component: resolve => require(['../components/page/user/userManage.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/editUser',
                    component: resolve => require(['../components/page/user/editUser.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/editGroup',
                    component: resolve => require(['../components/page/group/editGroup.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/groupManage',
                    component: resolve => require(['../components/page/group/groupManage.vue'], resolve)   // vue-schart组件
                },
                {
                    path: '/chart',
                    component: resolve => require(['../components/page/BaseCharts.vue'], resolve)   // vue-schart组件
                },
            ]
        },
        {
            path: '/login',
            component: resolve => require(['../components/page/Login.vue'], resolve)
        },
    ]
})
