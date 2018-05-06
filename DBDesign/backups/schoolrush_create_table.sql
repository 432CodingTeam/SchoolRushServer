drop table if exists `campus`;
drop table if exists `campusmajorpassed`;
drop table if exists `comments`;
drop table if exists `follow`;
drop table if exists `group`;
drop table if exists `groupactivity`;
drop table if exists `label`;
drop table if exists `livenesscampus`;
drop table if exists `major`;
drop table if exists `majorrank`;
drop table if exists `operation`;
drop table if exists `question`;
drop table if exists `tipoff`;
drop table if exists `token`;
drop table if exists `user`;
drop table if exists `userliveness`;
drop table if exists `usertogroup`;
drop table if exists `usertoq`;

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
  aday      int default '1' not null
  comment '今天通过题数',
  aweek     int default '1' not null
  comment '本周通过题数',
  amonth    int default '1' not null
  comment '本月通过题数',
  lastday   int default '0' not null
  comment '上一天以及之前共通过数',
  lastweek  int default '0' not null
  comment '上一周以及之前共通过数',
  lastmonth int default '0' not null
  comment '上一天以及之前共通过数',
  total     int default '0' not null
)
  comment '学校-分类-通过数
关系表 用于排行'
  engine = InnoDB;

create table comments
(
  id       bigint auto_increment
    primary key,
  uid      bigint                             not null
  comment '评论用户',
  qid      bigint                             not null
  comment '在哪个问题下',
  content  varchar(10000)                     not null
  comment '评论内容 最多1w字',
  reply    bigint                             null
  comment '回复的评论id 空即为非回复评论',
  agree    int default '0'                    null
  comment '支持数',
  disagree int default '0'                    null
  comment '反对数',
  time     datetime default CURRENT_TIMESTAMP null
  comment '评论时间',
  constraint comments_id_uindex
  unique (id)
)
  comment '评论表 也是一些人的解题感谢感想'
  engine = InnoDB;

create table follow
(
  id     bigint auto_increment
    primary key,
  uid    bigint          null,
  type   int default '1' not null
  comment '1 关注用户 
2 关注标签 
3 关注专业
4 关注学校',
  target bigint          not null
  comment '被关注的用户/标签/学校/专业的ID',
  constraint follow_id_uindex
  unique (id)
)
  engine = InnoDB;

create table `group`
(
  id        bigint auto_increment
    primary key,
  name      varchar(15)   null
  comment '群组名字',
  creator   bigint        null
  comment '创建者ID',
  members   int           not null
  comment '群组成员数',
  introduce varchar(3000) not null
  comment '小组介绍',
  avatar    varchar(200)  null
  comment '群组头像地址'
)
  comment '群组表 群组人数限制100'
  engine = InnoDB;

create table groupactivity
(
  id          bigint auto_increment
    primary key,
  title       varchar(100)                       not null
  comment '标题',
  questionsID bigint                             not null
  comment '题集ID',
  gid         bigint                             not null
  comment '所属的小组id',
  starttime   datetime default CURRENT_TIMESTAMP null,
  content     bigint                             not null
  comment '活动的介绍',
  constraint groupactivity_id_uindex
  unique (id)
)
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

create table livenesscampus
(
  id       bigint auto_increment
    primary key,
  liveID   bigint not null,
  campusID bigint not null,
  constraint livenesscampus_id_uindex
  unique (id)
)
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
  comment '此专业下排行榜列表  将学校的id用","分隔开',
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
  type       int(1)                             null
  comment '题型 选择1 判断2 填空3
开放题目4',
  q          varchar(20000)                     not null
  comment '问题内容 最多2W字',
  A          varchar(20)                        null
  comment '选项A',
  B          varchar(20)                        null
  comment '选项B',
  C          varchar(20)                        null
  comment '选项C',
  D          varchar(20)                        null
  comment '选项D',
  F          varchar(20)                        null
  comment '正确/错误选项 只可能有T/F两种值',
  correct    varchar(20)                        not null
  comment '正确答案',
  majorID    bigint                             null
  comment '所在分类ID',
  challenges int default '0'                    null
  comment '挑战人数',
  passed     int default '0'                    null
  comment '通过人数',
  levels     double(2, 1) default '3.5'         null
  comment '问题难度星级',
  uid        bigint                             not null
  comment '出题人的id',
  labels     varchar(255)                       null
  comment '标签 多个用逗号分开 每个问题最多7个标签',
  status     int(1) default '1'                 null
  comment '0 未审核
1 审核完成
2 问题有误 待重新编辑',
  title      varchar(30)                        null
  comment '标题 用于开放式题目 其他题目没有标题',
  `like`     int default '0'                    null
  comment '题目赞数',
  createtime datetime default CURRENT_TIMESTAMP null
)
  comment '问题表'
  engine = InnoDB;

create trigger question_insert
  after INSERT
  on question
  for each row
  begin
    #用户提问时 添加一条用户活跃信息
    insert into `school-rush`.userliveness
    (uid, action, targetID, `describe`) values
      (NEW.uid, 5, NEW.id, "分享了问题");
  end;

create table tipoff
(
  id         bigint auto_increment
    primary key,
  type       int                                not null
  comment '1 举报用户
2 举报问题
3 举报评论',
  reason     varchar(20)                        null,
  target     bigint                             null
  comment '用户/问题/评论 ID',
  review     int                                null
  comment '是否已经审核
已审核 1
未审核 2',
  reviewuser bigint                             null
  comment '审核的用户',
  reviewtime datetime                           null
  comment '审核时间',
  time       datetime default CURRENT_TIMESTAMP null,
  constraint tipoff_id_uindex
  unique (id)
)
  comment '举报表'
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
  comment '管理员1普通用户2',
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

create trigger user_insert
  after INSERT
  on user
  for each row
  begin
    # 更新学校的成员数量
    update `school-rush`.campus
    set members = members + 1
    where id = NEW.campusID;
    insert into `school-rush`.userliveness
    (uid, action, targetID, `describe`)
    values (NEW.id, 4, NEW.campusID, "加入了SchoolRush");
  end;

create trigger user_update
  before UPDATE
  on user
  for each row
  begin
    #当用户更改所在高校时 更新高校成员数
    set @oldCampusID = OLD.campusID;
    set @newCampusID = NEW.campusID;
    if (@oldCampusID <> @newCampusID)
    then
      update `school-rush`.campus
      set members = members + 1
      where id = @newCampusID;
      update `school-rush`.campus
      set members = members - 1
      where id = @oldCampusID;
    end if;
  end;

create table userliveness
(
  id         bigint auto_increment
    primary key,
  uid        bigint                             not null
  comment '用户id',
  action     int(3)                             not null
  comment '用户动作
1 加入SchoolRush
2 正在解决题目
3 通过题目
4 关注用户
5 用户分享问题
6 关注标签
7 关注学校
8 关注专业
9 加入小组',
  targetID   bigint                             null
  comment '目标ID
当用户关注某人/用户/题目时才会有此值',
  `describe` varchar(20)                        null,
  time       datetime default CURRENT_TIMESTAMP null
)
  engine = InnoDB;

create trigger usertoq_after_insert
  after INSERT
  on userliveness
  for each row
  begin
    set @campusID = (select campusID
                     from `school-rush`.user
                     where user.id = NEW.uid);
    insert into livenesscampus (liveID, campusID) values (new.id, @campusID);
  end;

create table usertogroup
(
  id       bigint auto_increment
    primary key,
  uid      bigint                             not null,
  gid      int                                null
  comment '群组id',
  jiontime datetime default CURRENT_TIMESTAMP not null,
  constraint usertogroup_id_uindex
  unique (id)
)
  engine = InnoDB;

create table usertoq
(
  id        bigint auto_increment
    primary key,
  uid       bigint                             not null
  comment '用户ID',
  qid       bigint                             not null
  comment '通过的题目ID',
  status    int                                not null
  comment '0 未通过
1 已通过',
  passtime  datetime                           null
  comment '通过时间',
  starttime datetime default CURRENT_TIMESTAMP null
)
  comment '用户-通过的题目 关系表
用于统计用户通过了哪些题目
统计用户通过的题目分类占比'
  engine = InnoDB;

create trigger after_insert
  after INSERT
  on usertoq
  for each row
  begin
    if (NEW.status = 1)
    then
      #通过题目
      #更新用户活跃表
      insert into `school-rush`.userliveness
      (uid, action, targetID, `describe`)
      values
        (NEW.uid, 3, NEW.qid, "用户通过题目");
      #更新学校专业通过题目表
      set @majorID = (select majorID
                      from `school-rush`.question
                      where id = NEW.qid);
      set @campusID = (select campusID
                       from `school-rush`.user
                       where id = NEW.uid);
      set @haveItem = (select count(*)
                       from `school-rush`.campusmajorpassed
                       where campusID = @campusID and majorID = @majorID);
      if (@haveItem = 1)
      then
        #如果有 更新
        update `school-rush`.campusmajorpassed
        set aday = aday + 1, aweek = aweek + 1, amonth = amonth + 1, total = total + 1
        where campusID = @campusID and majorID = @majorID;
      else
        #没有 添加
        insert into `school-rush`.campusmajorpassed
        (majorID, campusID)
        VALUES
          (@majorID, @campusID);
      end if;
      #更新题目的挑战人数和通过人数
      update `school-rush`.question
      set challenges = challenges + 1, passed = passed + 1
      where id = NEW.qid;
    else
      #用户没有通过题目
      #更新用户活跃表
      insert into `school-rush`.userliveness
      (uid, action, targetID, `describe`)
      values
        (NEW.uid, 2, NEW.qid, "用户正在解决题目");
      #更新题目的挑战人数和通过人数
      update `school-rush`.question
      set challenges = challenges + 1
      where id = NEW.qid;
    end if;
  end;

create trigger after_update
  after UPDATE
  on usertoq
  for each row
  begin
    #因为前台的更新只可能将status从0变成1 所以
    if (NEW.status = 1 and OLD.status = 0)
    then
      #更新用户活跃表
      insert into `school-rush`.userliveness
      (uid, action, targetID, `describe`)
      values
        (NEW.uid, 3, NEW.qid, "用户通过题目");
      #更新学校专业通过题目表
      set @majorID = (select majorID
                      from `school-rush`.question
                      where id = NEW.qid);
      set @campusID = (select campusID
                       from `school-rush`.user
                       where id = NEW.uid);
      set @haveItem = (select count(*)
                       from `school-rush`.campusmajorpassed
                       where campusID = @campusID and majorID = @majorID);
      if (@haveItem = 1)
      then
        #如果有 更新
        update `school-rush`.campusmajorpassed
        set aday = aday + 1, aweek = aweek + 1, amonth = amonth + 1, total = total + 1
        where campusID = @campusID and majorID = @majorID;
      else
        #没有 添加
        insert into `school-rush`.campusmajorpassed
        (majorID, campusID)
        VALUES
          (@majorID, @campusID);
      end if;
      #更新题目的挑战人数和通过人数
      update `school-rush`.question
      set passed = passed + 1
      where id = NEW.qid;
    end if;
  end;



