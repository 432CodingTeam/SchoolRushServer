<template>
  <div class="error-container">
    <div class="nofound">
      <div class="error-panel">
        
        <div id="nofound-panel">
          <Row>
            <Col span="14">
            <div id="error-text">
              <h1>页面不见了?!</h1>
            </div>
            </Col>
            <Col span="6">
            <div id="rollback-btn">
            <router-link to="/index">
              <Button type="primary" size="large" id="btn">返回首页</Button>
            </router-link>     
            </div>
            </Col>
          </Row>
        </div>  
        
        <div class="img_nofound">
          <img src="../../static/img/formula.jpg" alt="" id="formula">
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  
};
</script>
<style>
  .error-container{
    overflow: hidden;
  }
  .nofound{
    background-color: rgb(246,246,246);
    margin          : 0 auto;
    border-radius   : 10px;
    overflow        : hidden;
  }
  .error-panel {
    position: relative;
  }
  .img_nofound{
    overflow: hidden;
  }
  #formula{ 
    width        : 100%;
    height       : 100%;
    border-radius: 15px;
  }
  #nofound-panel{
    width           : 100%;
    position        : absolute;
    top             : 0;
    bottom          : 0;
    left            : 0;
    right           : 0;
    background-color: rgba(246,246,246, .6);
    border-radius   : 15px;
    overflow        : hidden;
  }
  #nofound-panel .ivu-row {
    margin    : 0 -2rem;
    margin-top: 25rem;
    background: rgba(255,255,255, .9);
    border    : .3rem solid #e4e4e4;
  }
  #error-text{
    height     : 100px;
    font-weight: bolder;
  }
  #error-text h1{
    width        : 100%;
    line-height  : 10rem;
    text-align   : right;
    padding-right: 2rem;
  }
  #rollback-btn{
    height: 100px;
    float : left;
    }
  #btn{
    position : relative;
    top      : 50%;
    transform: translateY(-50%);
  }
</style>





