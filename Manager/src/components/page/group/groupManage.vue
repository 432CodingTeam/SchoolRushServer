<template>
  <div class="table">
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-menu"></i> 小组管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="handle-box">
      <el-input v-model="searchKey" placeholder="搜索小组名" class="handle-input mr10"></el-input>
      <el-button type="primary" icon="search" @click="searchAll">搜索</el-button>

      <!-- 筛选条件 -->
      <el-collapse class="filter">
        <el-collapse-item  title="筛选条件" name="1">
          <el-form ref="form" v-model="filterData" label-width="80px">
            <el-form-item label="专业">
              <el-select v-model="filterData.majorID" filterable placeholder="筛选专业">
                <el-option v-for="item in majorData" :key=item.id :label=item.name :value=item.id>
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="filter">筛选</el-button>
              <el-button type="primary" @click="resetFilter">重置筛选条件</el-button>
            </el-form-item>
          </el-form>
        </el-collapse-item>
      </el-collapse>
    </div>
    <el-table :data="formData" border ref="multipleTable" :empty-text="emptyText" @selection-change="handleSelectionChange">
      <el-table-column prop="id" label="编号" width="65px">
      </el-table-column>
      <el-table-column prop="name" label="小组名">
        <template scope="scope">
          <a class="article-title" @click="editgroup(scope.row)">{{ scope.row.name }}</a>
        </template>
      </el-table-column>
      <el-table-column prop="identify" label="身份" width="85px">
        <template scope="scope">
          {{ scope.row.identify==1? "小组":"管理员" }}
        </template>
      </el-table-column>
      <el-table-column prop="gender" label="性别" width="85px">
        <template scope="scope">
          {{ scope.row.gender==1? "男":"女" }}
        </template>
      </el-table-column>
      <el-table-column prop="campusName" label="高校">
      </el-table-column>
      <el-table-column prop="majorName" label="专业">
      </el-table-column>
      <el-table-column label="操作" width="80px">
        <template scope="scope">
          <el-popover ref="deleteConfirm" placement="top" width="60px" v-model="scope.row.confirmDeleteVisible">
            <p>确定删除吗？</p>
            <div style="text-align: right; margin: 0">
              <el-button size="mini" type="text" @click="deleteCalcel">取消</el-button>
              <el-button type="primary" size="mini" @click="deleteSubmit">确定</el-button>
            </div>
          </el-popover>
          <el-button size="small" type="danger" v-popover:deleteConfirm @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination">
      <el-pagination @current-change="handleCurrentChange" layout="prev, pager, next" :total="totalNum" :page-size="perPageNum">
      </el-pagination>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPageNum: 20,
      totalNum: 0,
      formData: [],
      formDataBack: [],
      tableData: [],
      curPage: "",
      del_list: [],
      searchKey: "",
      currentDeleteItem: {},
      confirmDeleteVisible: false,
      filterData: {},
      emptyText: "暂无数据",
      campusData: [],
      majorData: [],
    };
  },
  methods: {
    deleteCalcel() {
      console.log(this.currentDeleteItem)
      this.formData[this.currentDeleteItem.index].confirmDeleteVisible = false
      this.currentDeleteItem = {}
      console.log("取消")
    },
    deleteSubmit() {
      let id = this.currentDeleteItem.id
      const that = this
      let url = this.$API.getService("group", "deleteById")

      this.$API
        .post(url, {
          id: this.formData[this.currentDeleteItem.index].id
        })
        .then(res => {
          console.log(res.data.data)

          if (res.data.data == 1) {
            that.$message.success("删除成功!")
            that.formData.splice(that.currentDeleteItem.index, 1)
            return
          }
          that.$message.error("删除失败！")
        })
        .catch(err => {
          console.log(err)
          that.$message.error("删除失败！")
        })
      this.formData[this.currentDeleteItem.index].confirmDeleteVisible = false
      this.currentDeleteItem = {}
    },
    editgroup(row) {
      row.type = 1
      this.$router.push({ path: "/editGroup", query: { group: row } })
    },
    getQuestionTotal() {
      //获取问题总数
      let that = this
      let url = this.$API.getService("Group", "getTotalNum")

      this.$API.get(url).then(res => {
        that.totalNum = parseInt(res.data.data)
      })
    },
    getPage(page, pageNum) {
      let that = this
      let url = this.$API.getService("Group", "getPage")

      this.$API
        .post(url, {
          page: page,
          num: pageNum
        })
        .then(res => {
          console.log(res.data.data)
          that.formData = res.data.data
          that.formDataBack = that.formData
          console.log(this.formData)
          
        })
    },
    resetEditFlag(flag) {
      for (var i = 0; i < this.cate.length; i++) this.cate[i].editFlag = flag
    },
    handleCurrentChange(val) {
      this.getPage(val, this.perPageNum)
    },
    search(key) {
      if (!key) key = this.searchKey
      key = key.trim()
      let searchRes = []
      for (var i = 0; i < this.formDataBack.length; i++) {
        if (this.formDataBack[i].name.indexOf(key) != -1)
          searchRes.push(this.formDataBack[i])
      }
      this.formData = searchRes
      if(this.formData.length == 0)
        this.emptyText = "本页没有搜索到，点击搜索按钮全局搜索看看~"
    },
    handleDelete(index, row) {
      row.index = index
      this.currentDeleteItem = row
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    },
    searchAll(){
      let data = {
        name: this.searchKey,
        page: 1,
        num: 20,
      }
      
      const that = this
      const url  = this.$API.getService("Group", "searchByName")

      this.$API.post(url, data).then((res) => {
        console.log(res)
        that.formData = res.data.data
      })
    },
    resetFilter() {
      this.filterData = {}
      this.getQuestionTotal()
      this.formData = this.formDataBack
    },
    filter() {
      const that = this
      const url = this.$API.getService("Group", "getFilterPage")
      let Data = this.filterData
      Data.page = 1
      Data.num = this.perPageNum
      this.formData = []
      this.emptyText = "正在搜索..."

      this.$API.post(url, Data).then((res) => {
        that.formData = res.data.data
        this.emptyText = "暂无数据"
      })
    },
    getAllMajor() {
      //TODO: 获取所有分类
      const that = this
      const url = this.$API.getService("Major", "getAll")

      this.$API.post(url).then((res) => {
        console.log(res)

        that.majorData = res.data.data
      })
    },
  },
  watch: {
    searchKey(key) {
      if (key == "") {
        this.formData = this.formDataBack
        this.emptyText = "暂无数据"
      }
      else this.search(key)
    },
  },
  mounted() {
    //获取问题数据
    this.getAllMajor()
    this.getPage(1, 20)
  },
}
</script>

<style scoped>
.handle-box {
  margin-bottom: 20px;
}
.handle-select {
  width: 120px;
}
.handle-input {
  width: 300px;
  display: inline-block;
}
.article-title:hover {
  cursor: pointer;
  color: #20a0ff;
}
.article-title:visited {
  color: black;
}
.filter {
  margin-top: 1rem;
}
</style>