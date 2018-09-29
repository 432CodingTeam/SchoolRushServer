<template>
  <div class="sidebar">
    <el-menu :default-active="onRoutes" class="el-menu-vertical-demo" theme="dark" unique-opened router>
      <template v-for="item in items">
        <template v-if="item.subs">
          <el-submenu :index="item.index">
            <template slot="title">
              <i :class="item.icon"></i>{{ item.title }}</template>
            <el-menu-item v-for="(subItem,i) in item.subs" :key="i" :index="subItem.index">{{ subItem.title }}
            </el-menu-item>
          </el-submenu>
        </template>
        <template v-else>
          <el-menu-item :index="item.index">
            <i :class="item.icon"></i>{{ item.title }}
          </el-menu-item>
        </template>
      </template>
    </el-menu>
  </div>
</template>

<script>
export default {
  data() {
    return {
      items: [
        {
          icon: "el-icon-menu",
          index: "Situation",
          title: "概况"
        },
        {
          icon: "el-icon-document",
          index: "2",
          title: "问题管理",
          subs: [
            {
              index: "editQuestion",
              title: "添加 & 编辑问题"
            },
            {
              index: "allQuestions",
              title: "全部问题"
            },
            {
              index: "reviewQuestion",
              title: "待审核问题"
            }
          ]
        },
        {
          icon: "el-icon-tickets",
          index: "3",
          title: "用户管理",
          subs: [
            {
              icon: "el-icon-edit",
              index: "editUser",
              title: "添加 & 编辑用户"
            },
            {
              icon: "el-icon-edit",
              index: "userManage",
              title: "用户管理"
            }
          ]
        },
        // {
        //   icon: "el-icon-upload",
        //   index: "uploadPublish",
        //   title: "上传MD创作"
        // },
        // {
        //   icon: "el-icon-document",
        //   index: "article",
        //   title: "我的创作"
        // },
        {
          icon: "el-icon-tickets",
          index: "4",
          title: "小组管理",
          subs: [
            {
              icon: "el-icon-edit",
              index: "editGroup",
              title: "添加 & 编辑小组"
            },
            {
              icon: "el-icon-edit",
              index: "groupManage",
              title: "小组管理"
            }
          ]
        },
        // {
        //   icon: "el-icon-setting",
        //   index: "ManageBlog",
        //   title: "管理博客"
        // }
      ]
    };
  },
  computed: {
    onRoutes() {
      return this.$route.path.replace("/", "");
    }
  },
  created() {
    //TODO: 先判断是否已经登陆
    const that = this;
  }
};
</script>

<style scoped>
.sidebar {
  display: block;
  position: absolute;
  width: 250px;
  left: 0;
  top: 70px;
  bottom: 0;
  background: #2e363f;
}
.sidebar > ul {
  height: 100%;
}
</style>
