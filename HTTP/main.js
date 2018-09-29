import axios from 'axios'

const isTest    = true
const test_root = "http://localhost/SchoolRushServer/PhalAPI/public/?s="
//const test_root = "http://localhost/SchoolRushServer/public/?s="
const pub_root = "http://api.iimt.me/public/?s="
let   root     = isTest ? test_root : pub_root
//设置axios为form-data
axios.defaults.headers.post['Content-Type']                 = 'application/x-www-form-urlencoded';
axios.defaults.headers.get  ['Content-Type']                = 'application/x-www-form-urlencoded';
                            axios.defaults.transformRequest = [function (data) {
    let ret = ''
    for (let it in data) {
        ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
    }
    return ret
}]


//接口地址定义
var API = {
    User: {
        root            : "User",
        add             : "Add",
        updateById      : "updateById",
        getAll          : "getAll",
        login           : "login",
        logout          : "logout",
        getById         : "getById",
        GetGoodAtRankTop: "GetGoodAtRankTop",
        GetRankAtcampus : "GetRankAtcampus",    //获取校园排名
        getUserByToken  : "getUserByToken",
        
    },
    Campus: {
        root   : "Campus",
        getAll : "getAll",
        getById: "getById",
    },
    Major: {
        root       : "Major",
        getAll     : "getAll",
        getBylike  : "getBylike",
        getByParent: "getByParent"
    },
    Question: {
        root                  : "Question",
        GetPageInformation    : "GetPageInformation",
        getTypeById           : "getTypeById",
        getById               : "getById",
        add                   : "add",
        getUserQuestion       : "getUserQuestion",
        getUserSolvedQuestion : "getUserSolvedQuestion",
        getUserSolvingQuestion: "getUserSolvingQuestion",
        GetQByKey             : "GetQByKey",
        search                : "search",
        searchSimple          : "searchSimple",
        getPageByLid          : "getPageByLid",
    },
    Usertoq: {
        root          : "Usertoq",
        Add           : "Add",
        PassedQuestion: "PassedQuestion",
        SolveQuestion : "SolveQuestion",
        Userto        : "Usertoq",
        isPassed      : "isPassed",
        getPassedNum  : "getPassedNum",
        GetPassed     : "GetPassed",
    },
    Label: {
        root          : "Label",
        add           : "add",
        getAll        : "getAll",
        getById       : "getById",
        getByQueryName: "getByQueryName",
    },
    Upload: {
        root           : "Upload",
        UploadImg      : "UploadImg",
        base64UploadQNY: "base64UploadQNY",
        base64UploadUPY: "base64UploadUPY",
    },
    Comment: {
        root           : "Comments",
        add            : "add",
        GetCommentsPage: "GetCommentsPage",
    },
    Userliveness: {
        root            : "Userliveness",
        getLivenessById : "getLivenessById",
        getUserSharedNum: "getUserSharedNum",
    },
    Campusmajorpassed: {
        root              : "Campusmajorpassed",
        getCampusPassedNum: "getCampusPassedNum",
        GetTopFiveMajor   : "GetTopFiveMajor",
    },
    Livenesscampus: {
        root                 : "Livenesscampus",
        getLivenessByCampusId: "getLivenessByCampusId",
    },
    Group: {
        root        : "Group",
        add         : "add",
        GetById     : "GetById",
        getPageByMid: "getPageByMid",
    },
    Groupactivity: {
        root                    : "Groupactivity",
        add                     : "add",
        getById                 : "getById",
        getActivityUserPassInfo : "getActivityUserPassInfo",
        getActivityCompleteInfo : "getActivityCompleteInfo",
        getSiteActivityByPage   : "getSiteActivityByPage",
        GetGroupActivityPageById: "GetGroupActivityPageById",
    },
    Usertogroup: {
        root          : "Usertogroup",
        Inquery       : "Inquery",
        Add           : "Add",
        Delete        : "Delete",
        getRecentJionU: "getRecentJionU",
        getUserGroup  : "getUserGroup",
    },
    Follow: {
        root          : "Follow",
        GetFollowerNum: "GetFollowerNum",
        GetFollowNum  : "GetFollowNum",
        Add           : "Add",
        IsFollowed    : "IsFollowed",
        UnFollow      : "UnFollow",
        getFollowedIds: "getFollowedIds",
        getFollowerIds: "getFollowerIds",
    },
    Tipoff: {
        root: "Tipoff",
        add : "add",
    },
    Bugs: {
        root        : "Bugs",
        add         : "add",
        getAllByPage: "getAllByPage"
    },
    UserMark: {
        root     : "UserMark",
        add      : "add",
        hasMarked: "hasMarked",
    },
    Analysis: {
        root           : 'Analysis',
        addAgreeById   : 'addAgreeById',
        cancelAgreeById: 'cancelAgreeById',
        getPageByQid   : 'getPageByQid',
        hasAgree       : 'hasAgree',
    }
}

//根据类名与动作名获取接口地址
function getService(cls, action) {
    return root + API[cls].root + "." + API[cls][action];
}
//返回axios对象

function getAxios() {
    return axios
}

//get方法
function get(url, params) {
    return axios.get(url, params)
}
//post方法
function post(url, params) {
    return axios.post(url, params)
}

//--------------其他方法

function getUserInfo(uid) {
    let url = getService("User", "getById")
    return post(url, { id: uid})
}

/**
 * 检查cookie是否有效
 */
function checkCookie(token) {

    let url = getService("User", "getUserByToken")

    return post(url, {token: token})
}

export default {
    getService,
    get, 
    post,
    getUserInfo,
    getAxios,
    checkCookie,
}