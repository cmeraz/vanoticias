<?php
/**
 * myEASYcom.php: common functions for the myEASYwp plugins serie
 *
 * Author: Ugo Grandolini aka "Camaleo"
 * Support site: http://myeasywp.com
 *
 * Copyright (C) 2010 Ugo Grandolini  (email : info@myeasywp.com)
*/
$version='1.1.3';

//define('MYEASYWP_DOMAIN', 'localhost'); # debug
//define('MYEASYWP_PATH', '/myEASYwp');   # debug

define('MYEASYWP_DOMAIN', 'myeasywp.com');
define('MYEASYWP_PATH', '');

//echo 'zend_loader_enabled['.zend_loader_enabled().']';


if(!function_exists('measycom_camaleo_links')) {

	function measycom_camaleo_links() {

		/**
		 * display the Camaleo links
		 */
		global $admin_email;    // @since 1.1.1

		if($admin_email == '') {

			global $current_user;
			get_currentuserinfo();

//			$admin_email = get_option('admin_email');   // 1.1.3
			$admin_email = $current_user->user_email;   // 1.1.3
		}
/*
		echo '<div align="right" style="margin:12px 0 0 0;">'
				.'<form method="post" action="http://feedmailpro.com/account/subscribers">'
					.'<img style="margin-right:8px;" src="http://myeasywp.com/common/img/camaleo.gif" align="absmiddle" /> '

					.'<a href="http://myeasywp.com" target="_blank">myeasywp.com: '._('myEASY Series official site').'</a>'
// 1.0.2: BEG
//						.' | '
//					.'<a href="http://wordpress.org/extend/plugins/profile/camaleo" target="_blank">'._('Camaleo&rsquo;s plugins page at WordPress.org').'</a>'
						.' | '
					._('Be the first to know what\'s going on! Subscribe our newsletter now:')
						.'<input name="subscriber[feed_id]" value="674" type="hidden" />'
						.'<input name="user_credentials" value="wanWqB41oGpzAAx3-w9u" type="hidden" />'
						.'<input name="subscriber[email]" size="15" type="text" value="'.$admin_email.'" />'
						.' <input class="button-primary" name="commit" value="Subscribe" type="submit" />'
				.'</form>'
// 1.0.2: END
			.'</div>'
		;
*/
	?><div align="right" style="margin:12px 0 0 0;">
		<span id="mc-response"><?php

			require_once('mc/inc/store-address.php');
			if($_GET['submit']) {

				echo storeAddress();
			}

		?></span>
		<form id="signup" action="" method="get">
			<img style="margin-right:8px;" src="http://myeasywp.com/common/img/camaleo.gif" align="absmiddle" />
			<a href="http://myeasywp.com" target="_blank">myeasywp.com: <?php _e('myEASY Series official site'); ?></a> | <?php
				_e('Be the first to know what\'s going on! Join Our Mailing List:');

				?><input type="text" name="email" id="email" value="<?php echo $admin_email; ?>" />
			<div style="margin:-10px 0 10px 0;">
				<div style="float:right;margin:0 0 0 20px;">
					<input class="button-primary" name="commit" value="Join" type="submit" />
				</div>
				<a href="http://services.myeasywp.com/?page=privacy" target="_blank"><?php
					_e('Your privacy is critically important to us!'); ?>
				</a>
			</div>
		</form>
		<script type="text/javascript">var myeasyplugin = '<?php echo myEASYcomCaller; ?>';</script>
		<script type="text/javascript" src="http://<?php echo MYEASYWP_DOMAIN.MYEASYWP_PATH; ?>/service/mc/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="http://<?php echo MYEASYWP_DOMAIN.MYEASYWP_PATH; ?>/service/mc/mailing-list.js"></script><?php
	}
}

if(!function_exists('measycom_sanitize_input')) {

	function measycom_sanitize_input($field,$usebr=false,$removespaces=false) {

		/**
		 * remove unwanted chars in a field
		 * @since 1.0.1
		 */
		$inp = array("\r\n","\n","\r");

		if($usebr) {

			$out = array('<br />','<br />','<br />');
		}
		else {

			$out = array('','','');
		}

		if($removespaces) {

			array_push($inp, ' ');
			array_push($out, '');
		}
		$clean = str_replace($inp, $out, $field);
		$clean = stripslashes($clean);
		return $clean;
	}
}

if(!function_exists('measycom_advertisement')) {

	function measycom_advertisement($ref_code) {

		/**
		 * display the advertisment stuff
		 */
		$src = 'http://'.MYEASYWP_DOMAIN.'/'.MYEASYWP_PATH.'/service/myads.php?p='.$ref_code;

		$h = measycom_getIframe_height('/service/myads.php?h');
		if($h==0) {

			$h = (281-8);
		}

		?><div style="width:auto;height:<?php echo $h; ?>px;background:transparent;padding:0;margin:8px 0 0 0;">
			<iframe id="myFrame" width="100%" height="<?php echo $h; ?>px" scrolling="no" frameborder="0" border="0"
					style="background-color:#F7F6F1;padding:0;margin:0;border:0px solid #ffffff;height:<?php echo $h; ?>px" src="<?php echo $src; ?>"></iframe>
		</div><?php
	}
}

if(!function_exists('measycom_donate')) {

	function measycom_donate($ref_code) {

		/**
		 * display the donation stuff
		 */
		$src = 'http://'.MYEASYWP_DOMAIN.'/'.MYEASYWP_PATH.'/service/donate.php?p='.$ref_code;

		$h = measycom_getIframe_height('/service/donate.php?h');
		if($h==0) {

			$h = (281-8);
		}

		?><div style="width:auto;height:<?php echo $h; ?>px;background:transparent;padding:0;margin:20px 0 0 0;">
			<iframe id="myFrame" width="100%" height="<?php echo $h; ?>px" scrolling="no" frameborder="0" border="0"
					style="background-color:#F7F6F1;padding:0;margin:0;border:0px solid #ffffff;height:<?php echo $h; ?>px" src="<?php echo $src; ?>"></iframe>
		</div><?php
	}
}

if(!function_exists('measycom_getIframe_height')) {

	function measycom_getIframe_height($domain_path) {

		/**
		 * $domain_path = '/service/donate.php?h'
		 */
		$domain = MYEASYWP_DOMAIN;
		$domain_path = MYEASYWP_PATH.$domain_path;

		$h = 0;

		$fp = fsockopen($domain, 80, $errno, $errstr, 10);
		if(!$fp) {

			/**
			 * HTTP ERROR
			 */
			$h = 0;
		}
		else {

			/**
			 * get the info
			 */
			$header = "GET $domain_path HTTP/1.1\r\n"
						."Host: $domain\r\n"
						."Connection: Close\r\n\r\n"
						//."Connection: keep-alive\r\n\r\n"
			;
			fwrite($fp, $header);

			$result = '';
			while (!feof($fp)) {

				$result .= fgets($fp, 1024);
			}

			$needle = '[hi]';
			$p = strpos($result, $needle, 0);
			if($p!==false) {

				$beg = $p + strlen($needle);
				$end = strpos($result, '[he]', $p);
				$h = substr($result, $beg, ($end-$beg));
			}

			fclose($fp);
		}
		return $h;
	}
}

if(!function_exists('measycom_mimetype')) {

	function measycom_mimetype($path,$file) {

		/**
		 * Return the mimetype
		 */
		if(function_exists('mime_content_type')) {

			$file_name_type = mime_content_type($path.$file);
		}
		else {

			#	mime_content_type() is NOT installed on non-Linux servers!
			#
			$ext = explode('.',$file);
			$i = count($ext);
			switch($ext[($i-1)]) {

				case 'rar':
				case 'tgz':
				case 'gz':
				case 'zip':
					$file_name_type = 'application/zip';
					break;

				case 'pdf':
					$file_name_type = 'application/pdf';
					break;

				default:
					$file_name_type = 'text/plain';
			}
		}
		return($file_name_type);
	}
}

if(!function_exists('measycom_emailer')) {

	function measycom_emailer(
						$to,
						$subject,
						$body,
						$reply		= '',
						$cc			= '',
						$bcc		= '',
						$from		= '',
						$text_type	= 'html',
						$x_prio		= '3',
						$attach_path= '',
						$attach_file= '',
						$_CHARSET   = 'utf-8'
	) {

		/**
		 * email sender wrapper
		 */
		//define('_CR_',"\n");					#	05/05/2010
		define('_CR_',"\r\n");					#	05/05/2010
		define('_TAB_',"\t");

		$user_body = $body;

		/**
		 * Initializations
		 */
		$myHOST = str_replace('www.','',$_SERVER['HTTP_HOST']);

		if($reply=='')				{ $reply = 'noreply@'.$myHOST; }

		if($from=='')				{ $from  = 'myEASYrobot#robot@'.$myHOST; }
		list($user_id, $from_id)	= explode('#', $from);
		if($user_id=='')			{ $user_id ='myEASYrobot'; }
		if($from_id=='')			{ $from_id ='robot@'.$myHOST; }

		$domain = $_SERVER['SERVER_NAME'];
		$mime_boundary = '------------{'.md5(uniqid(time())).'}';

		/**
		 * Set the common headers
		 */
		$headers = 'MIME-Version: 1.0'._CR_;
		$headers .= 'Reply-To:'.$reply._CR_;

		if(is_array($cc)) {

			/**
			 * copy to
			 */
			$t = count($cc);
			$headers .= 'Cc:';
			for($i=0;$i<$t;$i++) { $headers .= $cc[$i].', '; }
			$headers = substr($headers,0,-2)._CR_;
		}
		else { if($cc) { $headers .= 'Cc:'.$cc._CR_; } }

		if(is_array($bcc)) {

			/**
			 * blind copy to
			 */
			$t = count($bcc);
			$headers .= 'Bcc:';
			for($i=0;$i<$t;$i++) { $headers .= $bcc[$i].', '; }
			$headers = substr($headers,0,-2)._CR_;
		}
		else { if($bcc) { $headers .= 'Bcc:'.$bcc._CR_; } }

		$headers .= 'User-Agent: '.$_SERVER['HTTP_USER_AGENT']._CR_;
		$headers .= 'From: '.$user_id.' <'.$from_id.'>'._CR_;
		$headers .= 'Message-ID: <'.md5(uniqid(time())).'@'.$domain.'>'._CR_;

		switch($x_prio) {

			/**
			 * priority
			 */
			case '1':	$x_prio .= ' (Highest)';	break;
			case '2':	$x_prio .= ' (High)';		break;
			case '3':	$x_prio .= ' (Normal)';		break;
			case '4':	$x_prio .= ' (Low)';		break;
			case '5':	$x_prio .= ' (Lowest)';		break;

			default:
				$x_prio = '3 (Normal)';
		}

		if($x_prio)	{ $headers .= 'X-Priority: '.$x_prio._CR_; }

		/**
		 * Message Priority for Exchange Servers
		 *
		 * $headers .=	'X-MSmail-Priority: '.$x_prio_des._CR_;
		 *
		 * !!! WARNING !!!---# Hotmail and others do NOT like PHP mailer...
		 * $headers .=	'X-Mailer: PHP/'.phpversion()._CR_;---#
		 *
		 * $headers .= 'X-Mailer: Microsoft Office Outlook, Build 11.0.6353'._CR_;
		 * $headers .= 'X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2900.2527'._CR_;
		 *
		 */
		$headers .= 'X-Sender: '.$user_id.' <'.$from_id.'>'._CR_;

		$headers .= 'X-AntiAbuse: This is a solicited email for - '.$to.' - '._CR_;
		$headers .= 'X-AntiAbuse: Servername - {'.$domain.'}'._CR_;

		$headers .= 'X-AntiAbuse: User - '.$from_id._CR_;

		/**
		 * Set the right start of header
		 */
		if($attach_path && $attach_file) {

			if(!is_array($attach_path) || !is_array($attach_file)) {

				$_attach_path = array();
				$_attach_file = array();

				$_attach_path[] = $attach_path;
				$_attach_file[] = $attach_file;
			}
			else {

				$_attach_path = $attach_path;
				$_attach_file = $attach_file;
			}

			$a = 0;
			foreach($_attach_file as $key=>$attach_file) {

				$attach_path = $_attach_path[$key];

				$file_name_type = measycom_mimetype($attach_path, $attach_file);
				$file_name_name = $attach_file;

				/**
				 * Read the file to be attached
				 */
				$data = '';
				$file = @fopen($attach_path.$attach_file,'rb');
				if($file) {

					while(!feof($file)) { $data .= @fread($file, 8192); }
					@fclose($file);
				}

				/**
				 * Base64 encode the file data
				 */
				$data = chunk_split(base64_encode($data));

				if($a==0) {											/* send the body only once */

					/**
					 * Complete headers
					 */
					$headers .= 'Content-Type: multipart/mixed;'._CR_;
					$headers .= ' boundary="'.$mime_boundary.'"'."\n\n";

					/**
					 * Add a multipart boundary above the text message
					 */
					$mail_body_attach  = 'This is a multi-part message in MIME format.'._CR_;
					$mail_body_attach .= '--'.$mime_boundary."\n";
					$mail_body_attach .= 'Content-Type: text/'.$text_type.'; charset='.$_CHARSET.';'."\n";
					$mail_body_attach .= 'Content-Transfer-Encoding: 8bit'."\n\n";
					$mail_body_attach .= $body."\n";

					$body = $mail_body_attach;
				}

				/**
				 * Add the file attachment
				 */
				$mail_file_attach = '--'.$mime_boundary."\n";
				$mail_file_attach .= 'Content-Type: '.$file_name_type.";\n";
				$mail_file_attach .= ' name="'.$file_name_name.'"'."\n";
				$mail_file_attach .= 'Content-Disposition: attachment;'."\n";
				$mail_file_attach .= ' filename="'.$file_name_name.'"'."\n";
				$mail_file_attach .= 'Content-Transfer-Encoding: base64'."\n\n";
				$mail_file_attach .= $data."\n";

				$body .= $mail_file_attach;
				$a++;
			}
		}
		else {

			if($text_type=='plain') {

				$headers .= 'Content-Type: text/'.$text_type.'; charset='.$_CHARSET.';'."\n";
				$headers .= 'Content-Transfer-Encoding: 8bit'._CR_;
			}

			if($text_type=='html') {

				$headers .= 'Content-Type: multipart/alternative;'._CR_;
				$headers .= ' boundary="'.$mime_boundary.'"'."\n\n";

				$mail_body_multipart  = 'This is a multi-part message in MIME format.'._CR_;

				/**
				 * plain version
				 */
				$inp = array();
				$out = array();

				$inp[] = '<br>';        $out = "\n";
				$inp[] = '<br />';      $out = "\n";
				$inp[] = '<hr>';        $out = "\n------------------------------------------\n";
				$inp[] = '<hr />';      $out = "\n------------------------------------------\n";

				$plain = str_replace($inp, $out, $body);
				$plain = strip_tags($plain);

				$mail_body_multipart .= '--'.$mime_boundary."\n";
				$mail_body_multipart .= 'Content-Type: text/plain; charset='.$_CHARSET."\n";
				$mail_body_multipart .= 'Content-Transfer-Encoding: 8bit'."\n\n";
				$mail_body_multipart .= $plain."\n";

				/**
				 * html version
				 */
				$mail_body_multipart .= '--'.$mime_boundary."\n";
				$mail_body_multipart .= 'Content-Type: text/html; charset='.$_CHARSET.'; format=flowed'."\n";
				$mail_body_multipart .= 'Content-Transfer-Encoding: 8bit'."\n\n";
				$mail_body_multipart .= $body."\n\n";

				$body = $mail_body_multipart."\n".'--'.$mime_boundary."--\n";
			}
		}

		#
		#	$extra_header = '-fwebmaster@{'.$domain.'}'; # this is the User of the machine or hosting account
		#
//echo 'Subject:'.$subject
//	.'<br>Reply:'.$reply
//	.'<br>cc:'.$cc
//	.'<br>To:'.$to
//	.'<br>Body:<br>'.$body
//	.'<br>From_id:'.$from_id
//	.'<br>headers:'.$headers
//	.'<br>Mail Server:'.$_SESSION['misc']['MAILSRV'].':'.$_SESSION['misc']['MAILSRVPORT']
//	.'<br>E sender:'.$_SESSION['misc']['E_SENDER']
//	;
//die();

//$tmp = false;	#debug

		$tmp = @mail($to, $subject, $body, $headers); #, $extra_header);

		if($tmp==true) {

			return '*OK*';
		}
		else {

			$html = '<hr>There has been a mail error sending to:'.$to.'<hr>';

			$html .= 'Subject:'.$subject
					.'<br>Reply:'.$reply
					.'<br>cc:'.$cc
					.'<br>Body:<br>'.$body
					.'<br>From_id:'.$from_id
//					.'<br>Mail Server:'.$_SESSION['misc']['MAILSRV'].':'.$_SESSION['misc']['MAILSRVPORT']
					.'<br>Headers:'.$headers
			;

			echo $html;
			return $html;
		}
	}
}

if(!function_exists('measycom_get_real_path')) {

	/**
	 * @since 1.1.1
	 *
	 * required to calculate paths when running on servers with linked paths configuration
	 *
	 */
	function measycom_get_real_path($docPth, $filePth) {

		$docAry = explode('/', $docPth);
		$pthAry = explode('/', $filePth);

		$docLastId = count($docAry)-1;
		$docLast = $docAry[$docLastId];
		$pthLastId = count($pthAry)-1;

		$e = 0;
		$f = 0;
		for($i=$pthLastId;$i>=0;$i--) {

			if($pthAry[$i]==$docLast) {

				$e = $i;
			}
			if($pthAry[$i]=='wp-content') {

				$f = $i;
			}
		}

		if($e==0) { $e = $docLastId-1; }
		if($f==0) { $f = $pthLastId; }

		$e++;
		$pth = '';
		for($i=$e;$i<$f;$i++) {

			$pth .= $pthAry[$i].'/';
		}

		if(substr($pth,-1)!='/') { $pth = $pth.'/'; }
		if(substr($pth,0,1)!='/') { $pth = '/'.$pth; }

		return $pth;
	}
}

?>