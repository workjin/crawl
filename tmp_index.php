<?php
 header("Content-Type: text/html; charset=gbk");
 //模拟登录
 function login_post($url, $cookie, $post) {
	     $curl = curl_init();//初始化curl模块
//        $this_header = array(
// "content-type: application/x-www-form-urlencoded; 
// charset=gbk"
// );
//        curl_setopt($curl, CURLOPT_HTTPHEADER,$this_header);
	     curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
	     curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
	     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//是否自动显示返回的信息
	     // curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
       curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
       curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");
	     curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
	     curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息
	     curl_exec($curl);//执行cURL
	     curl_close($curl);//关闭cURL资源，并且释放系统资源
	 }

	 // 登录成功后获取数据
 function get_content($url, $cookie) {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie
     $rs = curl_exec($ch); //执行cURL抓取页面内容
     curl_close($ch);
     return $rs;
 }

 // 登录成功后模拟发帖
 function post_thread($url, $cookie, $post)
 {
   $curl = curl_init();//初始化curl模块
   curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
   curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//是否自动显示返回的信息
   curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie); //读取cookie
   curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
   curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息
   curl_exec($curl);//执行cURL
   curl_close($curl);//关闭cURL资源，并且释放系统资源
 }

 function grab_page($site){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($ch, CURLOPT_URL, $site);
    ob_start();
    return curl_exec ($ch);
    ob_end_clean();
    curl_close ($ch);
}

  function raw_post($site, $post){
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL,            $site );
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt($ch, CURLOPT_POST,           TRUE );
      curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
      curl_setopt($ch, CURLOPT_POSTFIELDS,     $post ); 
      // curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: multipart/form-data')); 


      //自加
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

      $result=curl_exec ($ch);
      return $result;
  }



  function bound_post($site, $post, $boundary){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data; boundary=$boundary"));
      curl_setopt($ch, CURLOPT_URL, $site);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
      $response = curl_exec($ch);

      return $response;
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
  

 //设置post的数据
$usr = "小心脏";
$encode = urlencode(iconv('utf-8', 'gb2312', $usr));
$encode1 = urlencode($usr);


echo $usr;
echo '<br>'; 
echo $encode;
echo "<br>"; 
echo $encode1;

// $usr = $encode1;

$post = array (
    'pwuser' => $usr,
    'pwpwd' => 'cai888',
    'jumpurl' => 'index.php',
    'lgt' => '0',
    'step' => '2',
    'forward' => '',
    // 'goto_page' => 'http://118cs.com/login.php',
    'submit' => '登录',
    'cktime' => 31536000,
);

//登录地址
$url = "http://118cs.com/login.php";

//设置cookie保存路径
$cookie = dirname(__FILE__) . '/cookie_curl.txt';

//登录后要获取信息的地址
$url2 = "http://118cs.com/post.php?fid=2";

// 1.模拟登录
login_post($url, $cookie, $post);

// 2.获取登录页的信息
$content = get_content($url2, $cookie);
 // echo grab_page("http://118cs.com/post.php?fid=2");

//匹配页面信息
// $preg = "/<td class='portrait'>(.*)<\/td>/i";
// preg_match_all($preg, $content, $arr);
// $str = $arr[1][0];
//输出内容
// echo $content;

// 3.模拟发帖
// $thread_info = array(
//   'action'   => 'pub',
//   'title'    => 'Test curl',
//   'content'  => 'Hello, world.',
//   't'        => time(),
// );
// $pub_thread_url = 'http://m.app.cn/thread/api/pub_thread.php';

// $ret = post_thread($pub_thread_url, $cookie, $thread_info);
// print_r($ret);

//删除cookie文件
@ unlink($cookie);


//   $token = getToken(16);
//     $contentBoundary = "----WebKitFormBoundary".$token;
//     $boundary = '------WebKitFormBoundary'.$token;
//     $eol = "\r\n";

//     $data1 = "POST /post.php? HTTP/1.1
// Host: 118cs.com
// Connection: keep-alive
// Content-Length: 1997
// Cache-Control: max-age=0
// Origin: http://118cs.com
// Upgrade-Insecure-Requests: 1
// User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36
// Content-Type: multipart/form-data; boundary=$contentBoundary
// Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8
// Referer: http://118cs.com/post.php?fid=2
// Accept-Encoding: gzip, deflate
// Accept-Language: zh-CN,zh;q=0.8,en;q=0.6
// Cookie: UM_distinctid=15ddf6ded24af3-0add4665260e24-791c30-1fa400-15ddf6ded25c45; dc4ca_ipstate=1502764498; cck_lasttime=1502764579455; cck_count=1; dc4ca_ck_info=%2F%09; dc4ca_winduser=UgQBbFdQXVFUVlIHBlZSAAkPXAUIA1RUA1cNAVJXVgUDVwdWbQ%3D%3D; dc4ca_ol_offset=194; td_cookie=18446744073408568176; dc4ca_lastpos=F; dc4ca_lastvisit=3606%091502772638%09%2Findex.php%3F; dc4ca_threadlog=%2C2%2C; a1110_pages=3; a1110_times=4; CNZZDATA1263004274=1503882446-1502689735-%7C1502768960; Hm_lvt_a7d9ee293df774e60779bb41d62ea6df=1502692112,1502708755; Hm_lpvt_a7d9ee293df774e60779bb41d62ea6df=1502772719" .$eol;


//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="magicname"' . $eol . $eol . $eol;
//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="magicid"' . $eol . $eol . $eol;
//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="verify"' . $eol . $eol;
//     $data .= $verify . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_title"'. $eol . $eol;
//     $data .= $title . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_iconid"' . $eol . $eol;
//     $data .= '0' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_content"' . $eol . $eol;
//     $data .= $body . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_autourl"' . $eol . $eol;
//     $data .= '1' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_usesign"' . $eol . $eol;
//     $data .= '1' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_convert"' . $eol . $eol;
//     $data .= '1' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_rvrc"' . $eol . $eol;
//     $data .= '0' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_enhidetype"' . $eol . $eol;
//     $data .= 'rvrc' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_money"' . $eol . $eol;
//     $data .= '0' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="atc_credittype"' . $eol . $eol;
//     $data .= 'money' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="step"' . $eol . $eol;
//     $data .= '2' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="pid"' . $eol . $eol . $eol;
    
//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="action"' . $eol . $eol;
//     $data .= 'new' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="fid"' . $eol . $eol;
//     $data .= '2' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="tid"' . $eol . $eol . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="article"' . $eol . $eol;
//     $data .= '0' . $eol;

//     $data .= $boundary . $eol;
//     $data .= 'Content-Disposition: form-data; name="special"' . $eol . $eol;
//     $data .= '0' . $eol;
//     $data .= $boundary. "--$eol";
?>