var Vue
var checkWhenChange = true  //每个输入框需要离焦即校验

// 给一个dom添加class
function addClass(dom, className){
  // if (dom.classList){
  //   dom.classList.add(className);
  // }else{
  //   dom.className += ' ' + className;
  // }

  var hasClass = !!dom.className.match(new RegExp('(\\s|^)' + _class + '(\\s|$)'))
  if(!hasClass){
    dom.className += ' ' + _class
  }
}

//常用正则表
var regList = {
  ImgCode: /^[0-9a-zA-Z]{4}$/,
  SmsCode: /^\d{4}$/,
  MailCode: /^\d{4}$/,
  UserName: /^[\w|\d]{4,16}$/,
  Password: /^[\w!@#$%^&*.]{6,16}$/,
  Mobile: /^1[3|4|5|7|8]\d{9}$/,
  RealName: /^[\u4e00-\u9fa5|·]{2,16}$|^[a-zA-Z|\s]{2,20}$/,
  BankNum: /^\d{10,19}$/,
  Money: /^([1-9]\d*|[0-9]\d*\.\d{1,2}|0)$/,
  Answer: /^\S+$/,
  Mail: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
}

// 断言函数
function assert(condition, message){
  if(!condition){
    console.error('[va-warn]:' + message)
  }
}

// Rule构造器
function Rule(ruleType, ruleValue, errMsg){
  this.ruleType = ruleType
  this.ruleValue = ruleValue
  this.errMsg = errMsg || ''
}

//VaForm构造器
function VaForm(el, finalRules, modifiers){
  this.ruleOrder = []
  this.rules = {}
  this.dom = el
  this.value = el.value   //值的副本
  this.validated = false  //是否被验证过
  this.tag = el.getAttribute('tag')   //提示的字段名
  // this.correctMsg = `${this.tag}输入正确！`
  this.correctMsg = ''
  this.modifiers = modifiers   //一些特殊的配置
  this.noCheck = false         //为true则不要校验

  this.ruleOrder = finalRules.map(item=>{
    this.rules[item.ruleType] = item
    return item.ruleType
  })
}

//rules中靠前的配置优先级最高
function mergeRule(...rules){
  var mergeResult = []
  var combineArr = Array.prototype.concat.apply([], rules)
  var hash = {}
  combineArr.forEach((rule)=>{
    if(hash[rule.ruleType] === undefined){
      mergeResult.push(rule)
      hash[rule.ruleType] = mergeResult.length - 1
    }else{
      var index = hash[rule.ruleType]
      Object.assign(mergeResult[index], rule)
    }
  })
  return mergeResult
}

//单个规则的验证结果
function VaResult(ruleType, ruleValue, isPass, errMsg){
  this.ruleType = ruleType
  this.ruleValue = ruleValue
  this.isPass = isPass
  this.errMsg = errMsg
}

// 显示结果的构造器
function DisplayResult(isPass, message){
  this.isPass = isPass
  this.message = message
}

//单个规则的校验，或者单个表单的校验
function validate(field, ruleType){
  assert(field, '未输入要验证的字段')
  var vaForm = this.forms[field]
  var {ruleOrder, rules} = vaForm

  if(ruleType === undefined){
    return this.checkForm(vaForm)
  }else{
    var rule = rules[ruleType] //规则
    return this.checkRule(vaForm, rule)
  }
  // vaForm.validated = true
}

// 获得不同的报错信息
function getErrMsg(vaForm, ruleType, ruleValue){
  var tag = vaForm.tag
  var errMsgs = {
    NonEmpty: `${tag}不能为空`,
    reg: `${tag}格式错误`,
    limit: `${tag}必须在${ruleValue[0]}与${ruleValue[1]}之间`,
    equal:`两次${tag}不相同`,
    length: `${tag}长度必须在${ruleValue[0]}与${ruleValue[1]}之间`,
    unique: `${tag}不能相同`
  }
  return errMsgs[ruleType]
}

//检测非空
function checkEmpty(ruleValue, vaForm, va){
  return vaForm.value.trim() ? true : false
}
//检测正则
function checkReg(ruleValue, vaForm, va){
  return ruleValue.test(vaForm.value) ? true : false
}
//检测数字区间
function checkLimit(ruleValue, vaForm, va){
  var value = vaForm.value
  return ((+value >= ruleValue[0]) && (+value <= ruleValue[1])) ? true : false
}
//检测相等
function checkEqual(ruleValue, vaForm, va){
  var target = va.forms[ruleValue]
  return target.value === vaForm.value ? true : false
}
//检测字符长度
function checkCharLength(ruleValue, vaForm, va){
  var length = vaForm.value.length
  return ((+length >= ruleValue[0]) && (+length <= ruleValue[1])) ? true : false
}

//几个输入框要各不相同
function checkUnique(ruleValue, vaForm, va){
  var uniqueGroup = va.uniqueGroup[ruleValue]
  var values = uniqueGroup.map(field=>va.forms[field].value)
  var uniqueValues = values.filter((item,index,arr)=>arr.indexOf(item) === index)
  return values.length === uniqueValues.length ? true : false
}

// 检测单个规则
function checkRule(vaForm, rule){
  var forms = this.forms
  var {ruleType, ruleValue, errMsg} = rule
  //如果有自定义报错就按自定义报错，没有就格式化报错
  errMsg = errMsg || getErrMsg(vaForm, ruleType, ruleValue)

  var ruleCheckers = {
    NonEmpty: checkEmpty,
    reg: checkReg,
    limit: checkLimit,
    equal: checkEqual,
    length: checkCharLength,
    unique: checkUnique
  }

  var ruleChecker = ruleCheckers[ruleType]
  var isPass = ruleChecker(ruleValue, vaForm, this)
  var vaResult = new VaResult(ruleType, ruleValue, isPass, isPass ? null : errMsg)
  return vaResult
}

//检测单个表单
function checkForm(vaForm){
  var results = vaForm.ruleOrder.map(ruleType=>{
    var rule = vaForm.rules[ruleType]
    return this.checkRule(vaForm,rule)
  })

  var errIndex = null
  for(var i = 0;i < results.length;i++){
    var result = results[i]
    if(result.isPass === false){
      errIndex = i
      break
    }
  }

  if(errIndex === null){
    return new DisplayResult(true,  vaForm.correctMsg)
  }else{
    return new DisplayResult(false, results[errIndex].errMsg)
  }
}

//刷新vaForm中的值的数据
function refreshValue(field, newValue){
  this.forms[field].value = newValue + ''
}

//更新所有表单的值
function refreshAllValue(){
  this.fieldOrder.forEach(field=>{
    var vaForm = this.forms[field]
    vaForm.value = vaForm.dom.value
  })
}

// 校验所有的表单，并弹出第一个错误。考虑可以为空的情况
function checkAll(){
  var firstErr = null
  this.fieldOrder.forEach(field=>{
    var vaForm = this.forms[field]
    var canNull = vaForm.ruleOrder.every(ruleType=>ruleType !== 'NonEmpty')  //输入框可以为空
    var noCheckEmpty = (vaForm.value === '' && canNull)   //该输入框可以为空，且输入为空

    if(vaForm.noCheck === false && noCheckEmpty === false){
      var result = this.setVmResult(field)
      // var result = this.validate(field)
      // this.vmResult[field] = result
      // vaForm.validated = true

      if(firstErr === null && result.isPass === false){
        firstErr = result.message
      }
    }

  })
  return firstErr
}

//验证单个字段，返回值，并弹出报错
function setVmResult(field){
  var result = this.validate(field) //本输入框结果
  this.vmResult[field] = result    //将报错弹出
  this.forms[field].validated = true  //校验过了
  return result
}

// 返回各个表单的值对象
function getValue(){
  var dataSet = {}
  for(var field in this.forms){
    dataSet[field] = this.forms[field].value
  }
  return dataSet
}

//添加一个规则
function addRule(field, index, Rule){
  var vaForm = this.forms[field]
  vaForm.ruleOrder.splice(index, 0, Rule.ruleType)
  vaForm.rules[Rule.ruleType] = Rule
}

// function resetAll(){
//   this.fieldOrder.forEach(field=>{
//     this.refreshValue(field, '')
//   })
// }

// 设置不校验的表单
function setNoCheck(field, bool){
  this.forms[field].noCheck = bool
}

function createVa(vm, field){
  var va = {
    vmResult:vm.va,
    fieldOrder:[],
    forms:{},
    group:{
      base:[],
    },
    equalGroup:{},                  //必须相等的字段
    uniqueGroup:{},                 //必须不同的字段
    Rule:Rule,                      //Rule构造器
    VaForm:VaForm,                  //VaForm构造器
    validate: validate,             //暴露的校验函数
    setVmResult: setVmResult,       //校验并报错
    checkRule: checkRule,           //内部的校验单条规则的函数
    checkForm: checkForm,           //内部的校验单个表单的函数
    refreshValue: refreshValue,     //更新某个表单的值
    checkAll: checkAll,             //检查所有的函数
    getValue: getValue,             //获取所有表单的当前值，得到一个对象
    setNoCheck:setNoCheck,          //设置为不校验
    addRule:addRule,                //给一个表单添加一个规则
    refreshAllValue:refreshAllValue //更新所有表单的值
    // resetAll: resetAll
  }

  if(vm.$va){
    return vm.$va
  }else{
    vm.$va = va
    return va
  }
}

//v-va:Password.canNull = "[{reg:/^\d{4}$/}]"
//arg = Password,  modifiers.canNull = true, value为后面相关的
//arg用来存字段名， modifiers用来存特殊配置， value为规则， tag是中文提示名， group 为分组
var main = {}
main.install = function(_Vue, options){
  Vue = _Vue

    Vue.directive('va',{
    bind:function(el, binding, vnode){
      var vm = vnode.context                         //当前的vue实例
      var field = binding.arg === 'EXTEND' ? el.getAttribute('name') : binding.arg // 当arg为EXTEND，从name属性获得值
      var option = binding.modifiers                    //特殊配置（允许非空，编辑新增共用等）
      var value = el.value                              //输入框的初始值
      var group = el.getAttribute('group') || 'base'    //分组，一个表单框在多个组呢？这个还没设，要兼容。 通过类似 'group1 group2 group3 group4'
      var tag = el.getAttribute('tag')
      var regMsg = el.getAttribute('regMsg') || ''   //针对正则的自定义报错
      var baseRule = []                              //默认的校验规则             --不用写，默认存在的规则（如非空），优先级最高
      var customRule = []                            //用户自定义的规则（组件中） --bingding.value
      var optionalRule = []                          //配置项中引申出来的规则，优先级最低

      assert(tag, '未设置输入框的tag')
      assert(vm.va, '实例的data选项上，未设置va对象')  //实例上如果没有设置结果则报错。
      assert(field, '未设置输入框字段')
      var va = createVa(vm, field)  //单例模式创建va，绑定在vm上
      va.fieldOrder.push(field)     //字段的检验顺序
      va.group[group].push(field)   //分组
      var NonEmpty = new Rule('NonEmpty', true, '')
      //默认非空
      if(option.CanNull === undefined){
        baseRule.push(NonEmpty)
      }

      //如果regList里有name对应的，直接就加进optionalConfig
      if(regList[field]){
        optionalRule.push(new Rule('reg', regList[field], regMsg))
      }

      //如果modefiers中的字段有在正则表里，将其加入optionalRule
      var regOptions = Object.keys(option);
      for(var i = 0;i < regOptions.length;i++){
        var regOption = regOptions[i]
        if(regList[regOptions[i]]){
          optionalRule.push(new Rule('reg', regList[regOption], regMsg))
        }
      }

      //用户自定义的规则
      if(binding.value !== undefined){
        customRule = binding.value.map(item=>{
          var ruleType = Object.keys(item)[0];
          var errMsg = ruleType === 'reg' ? regMsg : ''
          return new Rule(ruleType, item[ruleType], errMsg)
        })
      }

      var finalRules = mergeRule(baseRule, optionalRule, customRule)
      var hasUniqueRule = false
      //对联合校验的进行预处理
      finalRules.forEach(rule=>{
        var {ruleType, ruleValue} = rule
        if(ruleType === 'equal'){
          if(va.equalGroup[ruleValue] === undefined){
            va.equalGroup[ruleValue] = [field]
          }else{
            va.equalGroup[ruleValue].push(field)
          }
        }

        if(ruleType === 'unique'){
          hasUniqueRule = ruleValue
          if(va.uniqueGroup[ruleValue] === undefined){
            va.uniqueGroup[ruleValue] = [field]
          }else{
            va.uniqueGroup[ruleValue].push(field)
          }
        }
      })

      var vaForm = new VaForm(el, finalRules, option)
      va.forms[field] = vaForm

      if(checkWhenChange){
        function validateSingle(){
          va.refreshValue(field, el.value)  //更新值
          //如果允许为空的此时为空，不校验
          if(vaForm.value === '' && option.CanNull){
            va.vmResult[field] = {}   //如果为空，把界面显示上面的提示清掉
            return
          }

          if(vaForm.noCheck === false){
            va.setVmResult(field)
          }

          var isEqualTarget = false
          for(var index in va.equalGroup){
            if(index === field){
              isEqualTarget = true
            }
          }

          //相等框的联合校验
          if(isEqualTarget){
            va.equalGroup[field].forEach(item=>{va.setVmResult(item)})
          }

          //不同框的联合校验
          if(hasUniqueRule){
            va.uniqueGroup[hasUniqueRule].forEach(item=>{va.setVmResult(item)})
          }
        }

        //在change和blur上都绑定了处理事件
        el.addEventListener('change', validateSingle)
        el.addEventListener('blur', validateSingle)
      }

    },
  })
}

export default main
