CREATE TABLE campus
(
  id      BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  name    VARCHAR(30)     NOT NULL
  COMMENT '学校名字',
  members INT DEFAULT '0' NULL
  COMMENT '成员数',
  badge   VARCHAR(200)    NOT NULL
  COMMENT '校徽的图片地址',
  locate  VARCHAR(20)     NOT NULL
)
  COMMENT '高校表'
  ENGINE = InnoDB;

CREATE TABLE campusmajorpassed
(
  id       BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  majorID  BIGINT          NULL
  COMMENT '专业ID',
  campusID BIGINT          NULL
  COMMENT '学校ID',
  passed   INT DEFAULT '0' NULL
  COMMENT '通过人数'
)
  COMMENT '学校-分类-通过数
关系表 用于排行'
  ENGINE = InnoDB;

CREATE TABLE `group`
(
  id      BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  name    VARCHAR(15)  NULL
  COMMENT '群组名字',
  creator BIGINT       NULL
  COMMENT '创建者ID',
  members VARCHAR(500) NOT NULL
  COMMENT '群组成员ID 以“，”分隔'
)
  COMMENT '群组表 群组人数限制100'
  ENGINE = InnoDB;

CREATE TABLE label
(
  id   BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  name VARCHAR(10) NULL
  COMMENT '标签名 不可重复'
)
  COMMENT '标签表'
  ENGINE = InnoDB;

CREATE TABLE major
(
  id       BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  name     CHAR(20)     NULL
  COMMENT '专业名字',
  parent   BIGINT       NULL
  COMMENT '专业类的id',
  ranklist VARCHAR(100) NOT NULL
)
  COMMENT '专业表'
  ENGINE = InnoDB;

CREATE TABLE question
(
  id         BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  type       INT             NULL
  COMMENT '题型 选择1 判断2 填空3',
  q          VARCHAR(100)    NULL
  COMMENT '问题内容',
  A          CHAR            NULL
  COMMENT '选项A',
  B          CHAR            NULL
  COMMENT '选项B',
  C          CHAR            NULL
  COMMENT '选项C',
  D          CHAR            NULL
  COMMENT '选项D',
  TF         CHAR            NULL
  COMMENT '正确/错误选项 只可能有T/F两种值',
  correct    VARCHAR(10)     NOT NULL
  COMMENT '正确答案',
  majorID    BIGINT          NULL
  COMMENT '所在分类ID',
  challenges INT DEFAULT '0' NULL
  COMMENT '挑战人数',
  passed     INT DEFAULT '0' NULL
  COMMENT '通过人数',
  levels     INT             NULL
  COMMENT '问题难度星级',
  uid        BIGINT          NOT NULL
  COMMENT '出题人的id',
  balels     VARCHAR(255)    NULL
  COMMENT '标签 多个用逗号分开',
  toAnswer   INT             NULL
  COMMENT '给答题者的话'
)
  COMMENT '问题表'
  ENGINE = InnoDB;

CREATE TABLE user
(
  id       BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  name     VARCHAR(15)  NULL
  COMMENT '用户名',
  pass     VARCHAR(20)  NULL
  COMMENT '密码',
  identify INT          NULL
  COMMENT '管理员1 普通用户2',
  email    VARCHAR(40)  NOT NULL
  COMMENT '用户邮箱',
  tel      VARCHAR(20)  NOT NULL
  COMMENT '用户电话',
  campusID BIGINT       NOT NULL
  COMMENT '所在学校ID',
  major    BIGINT       NOT NULL
  COMMENT '所在专业ID',
  vice     INT          NOT NULL
  COMMENT '副专业 多个用“，”分隔',
  avatar   VARCHAR(200) NULL
  COMMENT '用户头像地址'
)
  COMMENT '用户表'
  ENGINE = InnoDB;

CREATE TABLE userliveness
(
  id     BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  time   DATE            NOT NULL
  COMMENT '记录的日期',
  uid    BIGINT          NOT NULL
  COMMENT '用户id',
  answer INT DEFAULT '0' NOT NULL
  COMMENT '回答数',
  quiz   INT DEFAULT '0' NOT NULL
  COMMENT '提问数'
)
  COMMENT '用户活跃度 记录用户在每一天 回答问题 提问数量'
  ENGINE = InnoDB;

CREATE TABLE usertoq
(
  id     BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  uid    BIGINT NOT NULL
  COMMENT '用户ID',
  qid    BIGINT NOT NULL
  COMMENT '通过的题目ID',
  status INT    NOT NULL
  COMMENT '0 未通过
	1 已通过'
)
  COMMENT '用户-通过的题目 关系表
用于统计用户通过了哪些题目
统计用户通过的题目分类占比'
  ENGINE = InnoDB;

CREATE TRIGGER user_passed_a_question
  BEFORE INSERT
  ON usertoq
  FOR EACH ROW
  BEGIN
    DECLARE cam_id BIGINT;
    DECLARE major_id BIGINT;
    #获取学校id
    SET cam_id = (SELECT campusID
                  FROM user
                  WHERE id = NEW.uid);
    #获取专业id
    SET major_id = (SELECT majorID
                    FROM question
                    WHERE id = NEW.qid);
    #如果有相同的学校对应相同的专业则不插入
    INSERT INTO campusmajorpassed
    (majorID, campusID, passed)
      SELECT
        major_id,
        cam_id,
        0
      FROM dual
      WHERE NOT exists(SELECT
                         majorID,
                         campusID
                       FROM campusmajorpassed
                       WHERE campusID = cam_id AND majorID = major_id);

    UPDATE campusmajorpassed
    SET passed = passed + 1
    WHERE campusID = cam_id AND majorID = major_id;
  END;


