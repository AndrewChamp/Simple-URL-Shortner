<?php

	function randomize(){
		$length = '5';
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		$size = strlen($chars);
		for($i = 0; $i < $length; $i++):
			$str .= $chars[rand( 0, $size - 1 )];
		endfor;
		return $str;
	}

//##############################################################################################
	
	function sanitize($input){
		$input = filter_var($input, FILTER_SANITIZE_STRING);
		return $input;
	}

//##############################################################################################
	
	function allDomains(){
		global $db;
		$result = '<ul class="allDomains">';
		$result .= '<li class="title"><span>Original URL</span> Short URL';
		$all = "SELECT * FROM ".REDIRECT." LEFT JOIN ".STATS." ON ".REDIRECT.".id_redirect = ".STATS.".redirect ORDER BY created ASC";
		$total = $db->query($all);
		while($z = $db->fetch($total)):
			$bs[$z['url']][] = $z;
		endwhile;
		foreach($bs as $company => $x):
			$result .= '<li><span>'.$x[0]['url'].'</span> http://'.SITE_URL.'/'.$x[0]['short'].' <a class="delete" onclick="return confirm(\'Are you sure you want to delete URL?\')" href="?delete_id='.$x[0]['id_redirect'].'">[x]</a></li>';
		endforeach;
		$result .= '</ul>';
		return $result;
	}
	
//##############################################################################################	
	
	function redirectURL(){
		global $db, $doit;
		$sql = "SELECT * FROM ".REDIRECT." WHERE short = '$doit' AND active = '1'";
		$x = $db->query_first($sql);
		if($db->affected_rows == false):
			header("HTTP/1.0 404 Not Found");
			errors('No url found');
		else:
			$data['redirect'] = $x['id_redirect'];
			$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['visited'] = date('Y-m-d H:i:s');
			$data['visitor_ip'] = $_SERVER['REMOTE_ADDR'];
			$db->insert(STATS, $data);
			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location:'.$x['url']);
			exit();
		endif;
	}
	
//##############################################################################################	
	
	function newURL(){
		global $db;
		if(isset($_POST['shorten']) && !empty($_POST['url']) && ($_POST['URL'] != 'http://')):
			$url = $_POST['url'];	
			$random = randomize();
			$exist = "SELECT short FROM ".REDIRECT." WHERE short = '$random'";
			$x = $db->query_first($exist);
			if($db->affected_rows == false):
				$exist = "SELECT short, url FROM ".REDIRECT." WHERE url = '$url'";
				$x = $db->query_first($exist);
				if($db->affected_rows == false):
					$data['url'] = $url;
					$data['short'] = $random;
					$data['created'] = date('Y-m-d H:i:s');
					$db->insert(REDIRECT, $data);
					$result = 'http://'.SITE_URL.'/'.$data['short'].' = '.$url;
					header('Location:/admin/');
				else:
					if($x['active'] == '0'):
						errors('This link has been disabled.');
					endif;
				endif;
			else:
				errors('Please try again.');
			endif;
		//else:
		//	errors('Bad URL or empty URL.');
		endif;
	}

//##############################################################################################

	function deleteURL(){
		global $db;
		$id = $_GET['delete_id'];
		if($id):
			$sql = "DELETE FROM `".REDIRECT."` WHERE `id_redirect` = $id";
			$db->query($sql);
			$sql = "DELETE FROM `".STATS."` WHERE `redirect` = $id";
			$db->query($sql);
			header('Location:/admin/');
		endif;
	}

//##############################################################################################

	function logMeInFoo(){
		if(isset($_POST['login'])):
			if($_POST['password'] == ADMIN_PASSWORD):
				setcookie("password", 'true', time()+3600);
				header('Location:/admin/');
			else:
				errors('Password Invalid.');
			endif;
		endif;
		?>
		<?=errors($message);?>
			<form method="post" action="" id="beta">
				<fieldset>
					<label for="password">password</label>
					<input name="password" type="password" size="30" value="" id="password" maxlength="50" />
					<input type="submit" name="login" id="login" value="login" />
				</fieldset>
			</form>
		<?php
	}
	
//##############################################################################################
	
	function makeDomain(){
		print '<form method="post" action="" id="makeDomain">
				<fieldset>
					<label for="url"></label>
					<input name="url" type="text" size="30" value="http://" id="url" maxlength="255" />
					<input type="submit" name="shorten" id="shorten" value="shorten url" />
				</fieldset>
			</form>';
	}
	
//##############################################################################################

	function errors($message){
		print '<div class="message error">'.$message.'</div>';
	}
	
//##############################################################################################
	
?>