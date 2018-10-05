<?php
namespace App\Common;
//绘制图形的库
use App\Model\User as UserModel;
use App\Common\Upload;

class GD {
    private $colors = array( //四种默认颜色
        array(
            "R" => "45",
            "G" => "140",
            "B" => "240"
        ),
        array(
            "R" => "25",
            "G" => "190",
            "B" => "107"
        ),
        array(
            "R" => "255",
            "G" => "153",
            "B" => "0"
        ),
        array(
            "R" => "237",
            "G" => "63",
            "B" => "20"
        ),
    );
    private $bgColor = array(
        "R" => "255",
        "G" => "255",
        "B" => "255"
    );

    public function getUserDefaultAvatarByName($str) {
        $text = $str[0]; //截取第一个字符

        if($text > '@' && $text < '{') //英文字符
            $text = strtoupper($text);
        else {
            $words = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $text = $words[rand(0,25)];
        }
        //图片宽高
        $width = 300;
        $height = 300;
        //创建画布
        $canvas = imagecreate($width, $height);
        //字体颜色
        $color = rand(0, 3);
        //设置背景颜色 白色
        $background_color = ImageColorAllocate($canvas, 255, 255, 255);
        //设置字体
        //设置字体大小
        $fontSize = 190;
        //设置字体颜色
        $paint = imagecolorallocate($canvas, $this->colors[$color]["R"], $this->colors[$color]["G"], $this->colors[$color]["B"]);
        //字体高度
        $textWidth = imagefontwidth($fontSize);
        //字体宽度
        $textHeight = imagefontheight($fontSize);
        //绘制文字
        imagettftext($canvas, $fontSize, 0, 65, 230, $paint, $font, $text);

        ob_start();
            imagepng($canvas);
            $img_base64 = ob_get_contents();
            //销毁图片
            imagedestroy($canvas);
        ob_end_clean();
        $res = 'data:image/png;base64,';
        $res .= chunk_split(base64_encode($img_base64));

        return $res;
    }

    /**
     * @desc   生成随机验证码
     * @$num   生成验证码的位数
     * @return 返回一个数组，包含生成的验证码以及对应的base64格式的验证码图片信息
     */
    public function getUserVerificationCodeRandom($num){
        //putenv('GDFONTPATH=' . realpath('../../public/font'));
        /* 创建画布 */
        $width     = 150;
        $height    = 40;
        $canvas    =  imagecreate($width, $height);

        // 将默认的背景有黑色改为白色
        $bgColor   = ImageColorAllocate($canvas,255,255,255);
        imagefill($canvas, 0, 0, $bgColor);

        /* 验证码设计 */
        $codeArr   = []; // 保存验证码，用来与用户验证码对比
        $code      = "";
        $fontStyle = '/font/Exo-ExtraBold';   //TODO: 更改为相对路径
        $codeDataSource      = 'abcdefghijklmnopqrsguvwxyz0123456789'; // 用来生成随机的数字与字母的混合验证码
        $fontSize  = 18;
        for ($i = 0; $i < $num; $i++) {
            $fontColor = ImageColorAllocate($canvas,10,10,10);
            $letter      = substr($codeDataSource, rand(0, strlen($codeDataSource) - 1),1);
            $code .= $letter;
            // 每个验证码之间的间隔
            $x     = ($i*$width/4) + rand(5,10);
            $y     = rand(15,25);
            imagettftext($canvas, $fontSize, 0, $x, $y, $fontColor, $fontStyle, $letter);
        }
        $codeArr['code'] = $code;

        /* 生成3条线干扰 */
        for($i = 0;$i < 3; $i++){
            $lineColor = imageColorAllocate($canvas, rand(80,255), rand(80,255), rand(80,255));
            if($i == 2){
                imagesetthickness($canvas,3);
                imageline($canvas, 1, rand(10,20), 149, rand(10,22),$lineColor);
            }else{
                imageline($canvas, rand(1,149), rand(10,20), rand(1,149), rand(10,20),$lineColor);
            }
        }

        /* 截取base64 */
        ob_start();
            imagepng($canvas);
            $img_base64 = ob_get_contents();
            //销毁图片
            imagedestroy($canvas);
        ob_end_clean();
        $res = 'data:image/png;base64,';
        $res .= chunk_split(base64_encode($img_base64));
        $codeArr['pic'] = $res;

        return $codeArr;
    }

    //生成随机头像 类似github
    public function getUserDefaultAvatarRandom() {
        $width =300;
        $height = 300;

        $canvas = imagecreate($width, $height);

        //设置背景颜色 白色
        $background_color = ImageColorAllocate($canvas, 255, 255, 255);

        $row = 5; //5行5列的小格子
        $colum = 5;
        //格子60 * 60
        $cellHeight = 60;
        $cellWidth = 60;
        //格子与格子之间的间隙
        $border = 5;

        //绘制格子
        for($i = 0; $i < $row; $i++) {
            for($j = 0; $j < $colum; $j++) {
                //生成随机颜色
                $rand = rand(0,4);
                $color = $rand == 4 ? $this->bgColor : $this->colors[$rand];

                $paint = imagecolorallocate($canvas,$color["R"], $color["G"], $color["B"]);
                $startX = $i * $cellWidth + $border;
                $startY = $j * $cellHeight + $border;
                $endX = $startX + $cellWidth - 2*$border;
                $endY = $startY + $cellHeight - 2*$border;
                imagefilledrectangle($canvas, $startX, $startY, $endX, $endY, $paint);
            }
        }

        // 截取base64
        ob_start();
            imagepng($canvas);
            $img_base64 = ob_get_contents();
            //销毁图片
            imagedestroy($canvas);
        ob_end_clean();
        $res = 'data:image/png;base64,';
        $res .= chunk_split(base64_encode($img_base64));

        return $res;
    }

    /**
     * 将base64转换成图片 返回图片信息
     */
    public function uploadToLocal($base64, $name) {
        $userModel = new UserModel();
        $toImgFile = $userModel -> base64toImg($base64, $name);
        if(!$toImgFile)
            return array("res" => false, "msg" => "base64转换为图片时失败");
        return $toImgFile;
    }

    //返回上传的base64图片在本地的地址
    public function base64Upload($base64, $name) {
        $userModel = new UserModel();
        $Upload = new Upload();

        $toImgFile = $userModel -> base64toImg($base64, $name);

        if(!$toImgFile)
            return array("res" => false, "msg" => "base64转换为图片时失败");

        return $Upload->uploadToQNY($toImgFile["filePath"],$toImgFile["fileName"]);
    }
}