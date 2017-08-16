<?php
    header("Content-Type: text/html; charset=gbk");

    function login($url,$data){
        $login = curl_init();
        curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($login, CURLOPT_TIMEOUT, 40000);
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
?>

<?php
    //设置用户信息
    $usr = '小天使';
    $pwpwd = 'cai888';
    $encode = urlencode(iconv('utf-8', 'gb2312', $usr));

    //登陆
    $url = "jumpurl=index.php&lgt=0&step=2&pwuser=$encode&pwpwd=$pwpwd&submit.x=15&submit.y=12";
    $login = login("http://118cs.com/login.php", $url);

    // echo get_content($url2);

    //设置发文内容
    $url2 = "http://118cs.com/post.php?fid=2";
    $title= encode_cn('@@预测');
    $body = encode_cn('准准准 喜洋洋！');
    //用户固定令牌
    $verify = '3e1633d1';
    

    $data = build_data($title, $body, $verify);

    json_encode($data);


    //发表文章
    post_data($url2, $data);

    $write = $title;
    $write .= "\r\n". mb_internal_encoding();

    $file = 'OUTPUTFILE_arr.txt';
    file_put_contents($file, $write);

?>
