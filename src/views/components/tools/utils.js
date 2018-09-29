//浅拷贝对象
function copy(obj){
  var newobj = {}
  for ( var attr in obj) {
      newobj[attr] = obj[attr]
  }
  return newobj
}

//深拷贝对象
function deepCopy(obj) {
  if(typeof obj != 'object'){
    return obj
  }
  var newobj = {}
  for ( var attr in obj) {
      newobj[attr] = deepCopy(obj[attr])
  }
  return newobj
}

//将数字格式化为 1K 样式
function formatByK(num) {
  if(num < 1000) {
    return num
  }
  else{
    let float = num/1000
    let res = Math.floor(float * 1000) / 1000
    return res
  }
}

export default {
  copy,
  deepCopy,
  formatByK,
}