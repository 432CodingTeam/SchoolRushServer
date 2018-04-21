create trigger after_insert
  after INSERT
  on usertoq
  for each row
  begin
    if (NEW.status = 1) then
      #通过题目
      #更新用户活跃表
      insert into `school-rush`.userliveness
        ( uid, action, targetID, `describe`)
      values
        (NEW.uid, 3, NEW.qid, "用户通过题目");
      #更新学校专业通过题目表
      set @majorID = (select majorID from `school-rush`.question where id = NEW.qid);
      set @campusID = (select campusID from `school-rush`.user where id = NEW.uid);
      set @haveItem = (select count(*) from `school-rush`.campusmajorpassed where campusID = @campusID and majorID = @majorID);
      if (@haveItem = 1) then
        #如果有 更新
        update `school-rush`.campusmajorpassed
        set aday = aday + 1, aweek = aweek + 1, amonth = amonth + 1 where campusID = @campusID and majorID = @majorID;
      else
        #没有 添加
        insert into `school-rush`.campusmajorpassed
        (majorID, campusID)
        VALUES
          (@majorID, @campusID);
      end if;
      #更新题目的挑战人数和通过人数
      update `school-rush`.question set challenges = challenges +1, passed = passed + 1 where id = NEW.qid;
    else
      #用户没有通过题目
      #更新用户活跃表
      insert into `school-rush`.userliveness
        ( uid, action, targetID, `describe`)
      values
        (NEW.uid, 2, NEW.qid, "用户正在解决题目");
      #更新题目的挑战人数和通过人数
      update `school-rush`.question set challenges = challenges +1 where id = NEW.qid;
    end if;
  end;




/*
 * 创建用户题目关系表更新时的触发器
 *
 */
create trigger `after_update`
  after update on usertoq for each row
  begin
    #因为前台的更新只可能将status从0变成1 所以
    if(NEW.status = 1) then
      #更新用户活跃表
      insert into `school-rush`.userliveness
        ( uid, action, targetID, `describe`)
      values
        (NEW.uid, 3, NEW.qid, "用户通过题目");
      #更新学校专业通过题目表
      set @majorID = (select majorID from `school-rush`.question where id = NEW.qid);
      set @campusID = (select campusID from `school-rush`.user where id = NEW.uid);
      set @haveItem = (select count(*) from `school-rush`.campusmajorpassed where campusID = @campusID and majorID = @majorID);
      if (@haveItem = 1) then
        #如果有 更新
        update `school-rush`.campusmajorpassed
        set aday = aday + 1, aweek = aweek + 1, amonth = amonth + 1 where campusID = @campusID and majorID = @majorID;
      else
        #没有 添加
        insert into `school-rush`.campusmajorpassed
        (majorID, campusID)
        VALUES
          (@majorID, @campusID);
      end if;
      #更新题目的挑战人数和通过人数
      update `school-rush`.question set passed = passed + 1 where id = NEW.qid;
    end if;
  end;

