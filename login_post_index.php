<?php
    header("Content-Type: text/html; charset=gbk");

    function basic_post($url,$data){
        $login = curl_init();
        curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($login, CURLOPT_TIMEOUT, 4000);
        curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($login, CURLOPT_URL, $url);
        curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($login, CURLOPT_POST, TRUE);
        curl_setopt($login, CURLOPT_POSTFIELDS, $data);
        ob_start();
        return curl_exec ($login);
        ob_end_clean();
        curl_close ($login);
        unset($login);    
    }

         // 登录成功后获取数据
     function get_content($url) {
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
         curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt"); //读取cookie
         $rs = curl_exec($ch); //执行cURL抓取页面内容
         curl_close($ch);
         return $rs;
     }
     
    function post_data($site,$data){
        $datapost = curl_init();
        // $headers = array("Expect:");
        curl_setopt($datapost, CURLOPT_URL, $site);
        curl_setopt($datapost, CURLOPT_TIMEOUT, 4000);
        curl_setopt($datapost, CURLOPT_HEADER, TRUE);
        // curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($datapost, CURLOPT_HTTPHEADER,     array("Content-Type: multipart/form-data")); 
        curl_setopt($datapost, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($datapost, CURLOPT_POST, TRUE);
        curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
        curl_setopt($datapost, CURLOPT_COOKIEFILE, "cookie.txt");
        ob_start();
        return curl_exec ($datapost);
        ob_end_clean();
        curl_close ($datapost);
        unset($datapost);    
    }

    function build_data($title, $body, $verify){
        $data = array(
            'magicname' => '',
            'magicid' => '',
            'verify' => $verify,
            'atc_title' => $title,
            'atc_iconid' => '0',
            'atc_content' => $body,
            'atc_autourl' => '1',
            'atc_usesign' => '1',
            'atc_convert' => '1',
            'atc_rvrc' => '0',
            'atc_enhidetype' => 'rvrc',
            'atc_money' => '0',
            'atc_credittype' => 'money',
            'step' => '2',
            'pid' => '',
            'action' => 'new',
            'fid' => '2',
            'tid' => '',
            'article' => '0',
            'special' => '0'       
            );
        return $data;
    }

    function encode_cn($string){
        return iconv("UTF-8","GB2312", $string);
    }

    function getVerifyToken($html){
        $target= "verifyhash = '";
        $tmp = strstr($html, $target);
        $tmp = substr($tmp, strlen($target));
        $tmp = strstr($tmp, "'", true);
        return $tmp;
    }

    function login($user, $pwpwd, $url){
        $encode = urlencode(iconv('utf-8', 'gb2312', $user));
        $request = "jumpurl=index.php&lgt=0&step=2&pwuser=$encode&pwpwd=$pwpwd&submit.x=15&submit.y=12";
        return basic_post($url, $request);
    }

    function register($user, $pwpwd, $email, $url){
        $encode = urlencode(iconv('utf-8', 'gb2312', $user));
        $request = "forward=&step=2&regname=$encode&regpwd=$pwpwd&regpwdrepeat=$pwpwd&regemail=$email&rgpermit=1";
        return basic_post($url, $request);
    }

    function getToken($length){
       $token = "";
       $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
       $codeAlphabet.= "0123456789";
       $max = strlen($codeAlphabet); // edited

      for ($i=0; $i < $length; $i++) {
          $token .= $codeAlphabet[random_int(0, $max-1)];
      }

      return $token;
    }

?>

<?php
        $url_register = "http://118cs.com/register.php";
        $url_post = "http://118cs.com/post.php?fid=2";
        $url_login = "http://118cs.com/login.php";
        $pwpwd = 'cai888';
        // $usr_arr = array('醉醉', '屁屁', '虫虫');
        $usr_arr = array('醉醉', '啊啊啊哦哦哦', '屁屁');
        
        foreach($usr_arr as $usr)
        {

            //登陆
            $login = login($usr, $pwpwd, $url_login);

            //如果用户不存在
            $no_user = encode_cn('用户'.$usr. " 不存在");
            if(strpos($login, $no_user) == true)
            {
                //注册
                $reg_email = getToken(9).'@'.getToken(4).'.com';
                register($usr, $pwpwd, $reg_email, $url_register);
                $login = login($usr, $pwpwd, $url_login);


                $file = 'OUTPUTFILE_register.txt';
                file_put_contents($file, $no_user."\r\n".$reg_email);
            }

            //获取用户令牌
            $verify = getVerifyToken($login);

            // 设置发文内容
            $title= encode_cn(getToken(5));
            $body = encode_cn(getToken(40));

            //构造包
            $data = build_data($title, $body, $verify);
            json_encode($data);

           //发表文章
            post_data($url_post, $data);

            // 登出
            $logout = basic_post("http://118cs.com/login.php", "action=quit");
        }

    //测试输出
    // $write = $title;
    // $write .= "\r\n". mb_internal_encoding();
    // $file = 'OUTPUTFILE_arr.txt';
    // file_put_contents($file, $write);
?>