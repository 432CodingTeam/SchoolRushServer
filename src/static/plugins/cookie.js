/**
 * 设置gookie
 * @param {*} c_name cookie名称
 * @param {*} value cookie的值
 * @param {*} expiredays 过期天数
 */
function setCookie(c_name,value,expiredays) {
  let exdate=new Date()
  exdate.setDate(exdate.getDate()+expiredays)
  document.cookie=c_name+ "=" +escape(value)+
    ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}

/**
 * 获取cookie
 * @param {*} c_name cokie名称
 */
function getCookie(c_name){
  if (document.cookie.length>0) {
    let c_start = document.cookie.indexOf(c_name + "=")
    if (c_start!=-1) { 
      c_start = c_start + c_name.length+1 
      let c_end=document.cookie.indexOf(";",c_start)
      if (c_end==-1) c_end=document.cookie.length
      return unescape(document.cookie.substring(c_start,c_end))
    }
  }
  return ""
}


module.exports = {
  getCookie,
  setCookie
}