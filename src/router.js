const routers = [
    {
        path: '/',
        redirect: '/login',
        meta: {
            title: '请登陆'
        },
        component: (resolve) => require(['./views/pages/login.vue'], resolve)
    },
    {
        path: '/index',
        meta: {
            title: '首页'
        },
        component: (resolve) => require(['./views/common/home.vue'], resolve),
        children: [
            {
                path: '/',
                meta: {
                    title: 'SchoolRush - 首页'
                },
                component: (resolve) => require(['./views/pages/index.vue'], resolve)
            },
            {
                path: '/solved',
                meta: {
                    title: 'SchoolRush - 已解决问题'
                },
                component: (resolve) => require(['./views/pages/index.vue'], resolve)
            },
            {
                path: '/solving',
                meta: {
                    title: 'SchoolRush - 解决中问题'
                },
                component: (resolve) => require(['./views/pages/index.vue'], resolve)
            },
            {
                path: '/group',
                meta: {
                    title: 'SchoolRush - 我的小组'
                },
                component: (resolve) => require(['./views/pages/group.vue'], resolve)
            },
            {
                path: '/solvedQuestion',
                meta: {
                    title: 'SchoolRush - 已解决'
                },
                component: (resolve) => require(['./views/pages/solvedQuestion.vue'], resolve)
            },
            {
                path: '/solvingQuestion',
                meta: {
                    title: 'SchoolRush - 正在解决'
                },
                component: (resolve) => require(['./views/pages/solvingQuestion.vue'], resolve)
            },
            {
                path: '/agroup/:id',
                meta: {
                    title: 'SchoolRush - 小组'
                },
                component: (resolve) => require(['./views/pages/group/a-group.vue'], resolve)
            },
            {
                path: '/group/:gid/activity/:id',
                meta: {
                    title: 'SchoolRush - 活动'
                },
                component: (resolve) => require(['./views/pages/group/activity.vue'], resolve)
            },
            {
                path: '/challenge',
                meta: {
                    title: 'SchoolRush - 挑战'
                },
                component: (resolve) => require(['./views/pages/challenge/index.vue'], resolve)
            },
            {
                path: '/home/:id',
                meta: {
                    title: 'SchoolRush - 个人主页'
                },
                component: (resolve) => require(['./views/pages/home.vue'], resolve)
            },
            {
                path: '/rank',
                meta: {
                    title: 'SchoolRush - 排行榜'
                },
                component: (resolve) => require(['./views/pages/rank.vue'], resolve)
            },
            {
                path: '/campus/:id',
                meta: {
                    title: 'SchoolRush - 高校'
                },
                component: (resolve) => require(['./views/pages/campus.vue'], resolve)
            },
            {
                path: '/search/:key',
                meta: {
                    title: 'SchoolRush - 搜索结果'
                },
                component: (resolve) => require(['./views/pages/search.vue'], resolve)
            },
            {
                path: '/settings',
                meta: {
                    title: 'SchoolRush - 设置'
                },
                component: (resolve) => require(['./views/pages/settings.vue'], resolve)
            },
            {
                path: '/majorRank',
                meta: {
                    title: 'SchoolRush - 专业排行'
                },
                component: (resolve) => require(['./views/pages/majorRank.vue'], resolve)
            },
            {
                path: '/question/:id',
                meta: {
                    title: 'SchoolRush - 问题页面'
                },
                component: (resolve) => require(['./views/pages/question.vue'], resolve),
            },
            {
                path: '/label/:id',
                meta: {
                    title: 'SchoolRush - 标签'
                },
                component: (resolve) => require(['./views/pages/label.vue'], resolve)
            },
            {
                path: '/404',
                meta: {
                    title: 'SchoolRush - 404'
                },
                component: (resolve) => require(['./views/pages/404.vue'], resolve),
            },
            {
                path: '/msgCart',
                meta: {
                    title: 'SchoolRush - msgCart'
                },
                component: (resolve) => require(['./views/components/msgCart.vue'], resolve),
            },
            {
                path: '/setup',
                meta: {
                    title: 'SchoolRush - 分享题目'
                },
                component: (resolve) => require(['./views/common/setup.vue'], resolve)
            },
            {
                path: '/submit',
                meta: {
                    title: 'SchoolRush - 分享问题成功'
                },
                component: (resolve) => require(['./views/questions/submitQuestion.vue'], resolve)
            },
            {
                path: '/editQ',
                meta: {
                    title: 'SchoolRush - 分享题目'
                },
                component: (resolve) => require(['./views/questions/editQuestion.vue'], resolve)
            },
            {
                path: '/submitSuccess',
                meta: {
                    title: 'SchoolRush - 分享成功~'
                },
                component: (resolve) => require(['./views/questions/submitQuestion.vue'], resolve)
            },
            {
                path: '/group/:gid/activity/:aid/statistics',
                meta: {
                    title: 'SchoolRush - 查看活动统计~'
                },
                component: (resolve) => require(['./views/pages/group/statistics.vue'], resolve)
            },
            {
                path: '/agroup/:gid/publishActivity',
                meta: {
                    title: 'SchoolRush - 发布活动~'
                },
                component: (resolve) => require(['./views/pages/group/publishActivity.vue'], resolve)
            },
            {
                path: '/followed/:id',
                meta: {
                    title: 'SchoolRush - 关注的用户~'
                },
                component: (resolve) => require(['./views/pages/follow/followedUser.vue'], resolve)
            },
            {
                path: '/follower/:id',
                meta: {
                    title: 'SchoolRush - 关注我的用户~'
                },
                component: (resolve) => require(['./views/pages/follow/follower.vue'], resolve)
            },
            {
                path: '/groupSquare',
                meta: {
                    title: 'SchoolRush - 小组广场~'
                },
                component: (resolve) => require(['./views/pages/group/groupSquare.vue'], resolve)
            },
            {
                path: '/groupSquare/:id',
                meta: {
                    title: 'SchoolRush - 二级 - 小组广场~'
                },
                component: (resolve) => require(['./views/pages/group/subGroupSquare.vue'], resolve)
            },
            {
                path: '/groups/:id',
                meta: {
                    title: 'SchoolRush - 三级 - 小组广场~'
                },
                component: (resolve) => require(['./views/pages/group/trdGroupSquare.vue'], resolve)
            },
        ]
    },
    {
        path: '/login',
        meta: {
            title: 'SchoolRush - 登陆'
        },
        component: (resolve) => require(['./views/pages/login.vue'], resolve)
    },
    {
        path: '/bug',
        meta: {
            title: 'SchoolRush - BUG提交/建议/更新记录~'
        },
        component: (resolve) => require(['./views/pages/bug.vue'], resolve)
    },
    {
        path: '*',
        meta: {
            title: 'SchoolRush - 404找不到页面~'
        },
        component: (resolve) => require(['./views/pages/404.vue'], resolve)
    },
];
export default routers; 