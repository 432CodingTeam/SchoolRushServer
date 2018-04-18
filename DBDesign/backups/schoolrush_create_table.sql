DROP TABLE campus;

DROP TABLE campusmajorpassed;

DROP TABLE `group`;

DROP TABLE label;

DROP TABLE major;

DROP TABLE majorrank;

DROP TABLE question;

DROP TABLE user;

DROP TABLE userliveness;

DROP TABLE usertoq;

DROP TABLE token;



create table campus
(
  id      bigint auto_increment
    primary key,
  name    varchar(30)     not null
  comment '学校名字',
  members int default '0' null
  comment '成员数',
  badge   varchar(200)    not null
  comment '校徽的图片地址',
  locate  varchar(20)     not null
)
  comment '高校表'
  engine = InnoDB;

create table campusmajorpassed
(
  id        bigint auto_increment
    primary key,
  majorID   bigint          null
  comment '专业ID',
  campusID  bigint          null
  comment '学校ID',
  aday      int default '0' not null
  comment '今天通过题数',
  aweek     int default '0' not null
  comment '本周通过题数',
  amonth    int default '0' not null
  comment '本月通过题数',
  lastday   int default '0' not null
  comment '上一天以及之前共通过数',
  lastweek  int default '0' not null
  comment '上一周以及之前共通过数',
  lastmonth int default '0' not null
  comment '上一天以及之前共通过数'
)
  comment '学校-分类-通过数
关系表 用于排行'
  engine = InnoDB;

create table `group`
(
  id      bigint auto_increment
    primary key,
  name    varchar(15)  null
  comment '群组名字',
  creator bigint       null
  comment '创建者ID',
  members varchar(500) not null
  comment '群组成员ID 以“，”分隔'
)
  comment '群组表 群组人数限制100'
  engine = InnoDB;

create table label
(
  id   bigint auto_increment
    primary key,
  name varchar(10) null
  comment '标签名 不可重复'
)
  comment '标签表'
  engine = InnoDB;

create table major
(
  id     bigint auto_increment
    primary key,
  name   char(20) null
  comment '专业名字',
  parent bigint   null
  comment '专业类的id'
)
  comment '专业表'
  engine = InnoDB;

create table majorrank
(
  id   bigint auto_increment
    primary key,
  list varchar(300) not null
  comment '此专业下排行榜列表  将学校的id用，分隔开',
  type int          not null
  comment '1 日榜（统计前一天）
2 周榜 (上一周)
3 月榜  (上个月)'
)
  engine = InnoDB;

create table operation
(
  id          bigint auto_increment
    primary key,
  uid         bigint       not null
  comment '操作人',
  operatetime datetime     not null
  comment '操作时间',
  type        int          not null
  comment '操作类型
3 审核题目

其他的后面再加',
  `desc`      varchar(200) not null
  comment '操作说明',
  constraint operation_id_uindex
  unique (id)
)
  comment '操作表，当有管理员做了某个操作就记录下来'
  engine = InnoDB;

create table question
(
  id         bigint auto_increment
    primary key,
  type       int(1)          null
  comment '题型 选择1 判断2 填空3',
  q          varchar(100)    null
  comment '问题内容',
  A          char            null
  comment '选项A',
  B          char            null
  comment '选项B',
  C          char            null
  comment '选项C',
  D          char            null
  comment '选项D',
  F          char            null
  comment '正确/错误选项 只可能有T/F两种值',
  correct    varchar(10)     not null
  comment '正确答案',
  majorID    bigint          null
  comment '所在分类ID',
  challenges int default '0' null
  comment '挑战人数',
  passed     int default '0' null
  comment '通过人数',
  levels     int             null
  comment '问题难度星级',
  uid        bigint          not null
  comment '出题人的id',
  labels     varchar(255)    null
  comment '标签 多个用逗号分开',
  toAnswer   varchar(30)     null
  comment '给答题者的话',
  status     int(1)          null
  comment '0 未审核
1 审核完成
2 问题有误 待重新编辑',
  createtime datetime        null
  comment '题目创建时间'
)
  comment '问题表'
  engine = InnoDB;

create table token
(
  id         bigint auto_increment
    primary key,
  uid        bigint      not null
  comment '用户id',
  token      varchar(32) not null
  comment '签名',
  expiretime datetime    not null
  comment '签名过期时间',
  constraint token_id_uindex
  unique (id),
  constraint token_uid_uindex
  unique (uid)
)
  comment '用户签名验证'
  engine = InnoDB;

create table user
(
  id         bigint auto_increment
    primary key,
  name       varchar(15)     not null
  comment '用户名',
  pass       varchar(20)     not null
  comment '密码',
  identify   int default '2' null
  comment '管理员1 普通用户2',
  email      varchar(40)     not null
  comment '用户邮箱',
  tel        varchar(20)     null
  comment '用户电话',
  campusID   bigint          null
  comment '所在学校ID',
  majorID    bigint          null
  comment '所在专业ID',
  vice       varchar(20)     null
  comment '副专业 多个用“，”分隔',
  avatar     varchar(200)    null
  comment '用户头像地址',
  `describe` varchar(30)     null
  comment '一句话描述自己',
  gender     int(1)          null
  comment '0 女 1 男',
  constraint user_name_uindex
  unique (name),
  constraint user_email_uindex
  unique (email)
)
  comment '用户表'
  engine = InnoDB;

create table userliveness
(
  id         bigint auto_increment
    primary key,
  time       datetime    not null
  comment '记录的时间',
  uid        bigint      not null
  comment '用户id',
  action     int         not null
  comment '用户动作',
  targetID   bigint      null
  comment '目标ID
当用户关注某人/用户/题目时才会有此值',
  `describe` varchar(20) null
)
  comment '用户活跃度 记录用户的动作'
  engine = InnoDB;

create table usertoq
(
  id       bigint auto_increment
    primary key,
  uid      bigint   not null
  comment '用户ID',
  qid      bigint   not null
  comment '通过的题目ID',
  status   int      not null
  comment '0 未通过
1 已通过',
  passtime datetime not null
  comment '通过时间'
)
  comment '用户-通过的题目 关系表
用于统计用户通过了哪些题目
统计用户通过的题目分类占比'
  engine = InnoDB;