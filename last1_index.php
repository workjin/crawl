<?php
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
	$destination = "http://118cs.com/post.php?fid=2";

	$eol = "\r\n";
	$data = '';

	$mime_boundary=md5(time());

	$boundary = '------WebKitFormBoundary'.getToken(16);

	$title= 'NEW TITLE';
	$body = 'NEW CONTENT';

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="magicname"' . $eol . $eol . $eol;
	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="magicid"' . $eol . $eol . $eol;
	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="verify"' . $eol . $eol;
	$data .= "3e1633d1" . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_title"'. $eol . $eol;
	$data .= $title . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_iconid"' . $eol . $eol;
	$data .= '0' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_content"' . $eol . $eol;
	$data .= $body . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_autourl"' . $eol . $eol;
	$data .= '1' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_usesign"' . $eol . $eol;
	$data .= '1' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_convert"' . $eol . $eol;
	$data .= '1' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_rvrc"' . $eol . $eol;
	$data .= '0' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_enhidetype"' . $eol . $eol;
	$data .= 'rvrc' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_money"' . $eol . $eol;
	$data .= '0' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="atc_credittype"' . $eol . $eol;
	$data .= 'money' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="step"' . $eol . $eol;
	$data .= '2' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="pid"' . $eol . $eol . $eol;
	
	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="action"' . $eol . $eol;
	$data .= 'new' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="fid"' . $eol . $eol;
	$data .= '2' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="tid"' . $eol . $eol . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="article"' . $eol . $eol;
	$data .= '0' . $eol;

	$data .= $boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="special"' . $eol . $eol;
	$data .= '0' . $eol;
	$data .= $boundary. '--;


	// echo $data;

	$data .= 'Content-Type: text/plain' . $eol;
	$data .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
	$data .= chunk_split(base64_encode("Some file content")) . $eol;
	// $data .= "--" . $mime_boundary . "--" . $eol . $eol; // finish with two eol's!!
	$data .= $boundary . $eol . $eol; // finish with two eol's!!

	$params = array('http' => array(
	                  'method' => 'POST',
	                  'header' => 'Content-Type: multipart/form-data; boundary=' . $boundary . $eol,
	                  'content' => $data
	               ));

	$ctx = stream_context_create($params);
	$response = @file_get_contents($destination, FILE_TEXT, $ctx);
?>