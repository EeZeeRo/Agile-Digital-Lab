<?php
	require_once 'db.php';

	function sendFriendRequest($myself, $user) {
		R::exec("INSERT INTO `friends` VALUES (NULL, '".$myself."', '".$user."', 1)");
		return true;
	}

	function cancelFriendRequest($myself, $user) {
		R::exec("DELETE FROM `friends` WHERE (user_one = '".$myself."' AND user_two = '".$user."') OR (user_one = '".$user."' AND user_two = '".$myself."') AND status = 1");
		return true;
	}

	function acceptFriendRequest($myself, $user) {
		R::exec("UPDATE friends SET status = 2 WHERE (user_one = '".$myself."' AND user_two = '".$user."') OR (user_one = '".$user."' AND user_two = '".$myself."')");
		return true;
	}

	function deleteFromFriends($myself, $user) {
		R::exec("UPDATE friends SET status = 1 WHERE (user_one = '".$myself."' AND user_two = '".$user."') OR (user_one = '".$user."' AND user_two = '".$myself."')");
		return true;
	}

	function getAllUsernames(){
		$sql = R::getAll("SELECT `username` FROM `users`");
		return $sql;
	}

	function getAllFriends($user, $send_data) {
		$sql = R::getAll("SELECT * FROM `friends` WHERE (user_one = '".$user."' OR user_two = '".$user."') AND status = 2");

		if($send_data){

			$return_data = [];
			foreach ($sql as $row){
				if ($row['user_one'] == $user) {
					$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_two']."'");
					array_push($return_data, $get_user);
				} else {
					$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_one']."'");
					array_push($return_data, $get_user);
				}
			}

			return $return_data;
		} else {
			return count($sql);
		}
	}

	function getLastFriends($user, $count) {
		$sql = R::getAll("SELECT * FROM friends WHERE (user_one = '".$user."' OR user_two = '".$user."') AND status = 2 ORDER BY id DESC LIMIT ".$count."");
		$return_data = [];
		foreach ($sql as $row) {
			if ($row['user_one'] == $user) {
				$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_two']."'");
				array_push($return_data, $get_user);
			} else {
				$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_one']."'");
				array_push($return_data, $get_user);
			}
		}

		return $return_data;
	}

	function getAllRequests($myself, $send_data) {
		$sql = R::getAll("SELECT * FROM `friends` WHERE user_two = '".$myself."' AND status = 1");

		if($send_data){

			$return_data = [];
			foreach ($sql as $row){
				if ($row['user_one'] == $myself) {
					$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_two']."'");
					array_push($return_data, $get_user);
				} else {
					$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_one']."'");
					array_push($return_data, $get_user);
				}
			}

			return $return_data;
		} else {
			return count($sql);
		}
	}

	function getLastRequests($user, $count) {
		$sql = R::getAll("SELECT * FROM friends WHERE (user_one = '".$user."' OR user_two = '".$user."') AND status = 1 ORDER BY id DESC LIMIT ".$count."");
		$return_data = [];
		foreach ($sql as $row) {
			if ($row['user_one'] == $user) {
				$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_two']."'");
				array_push($return_data, $get_user);
			} else {
				$get_user = R::getRow("SELECT id, username FROM users WHERE username = '".$row['user_one']."'");
				array_push($return_data, $get_user);
			}
		}

		return $return_data;
	}

	function isArleadyFriends($myself, $user){
		$sql = R::getAll("SELECT * FROM `friends` WHERE ((user_one = '".$myself."' AND user_two = '".$user."') OR (user_one = '".$user."' AND user_two = '".$myself."')) AND status = 2");
		if (count($sql) === 1) {
			return true;
		} else {
			return false;
		}
	}

	function isRequestSended($myself, $user){
		$sql = R::getAll("SELECT * FROM `friends` WHERE (user_one = '".$myself."' AND user_two = '".$user."') AND status = 1");
		if (count($sql) === 1) {
			return true;
		} else {
			return false;
		}
	}

	function isRequestedMe($myself, $user){
		$sql = R::getAll("SELECT * FROM `friends` WHERE (user_one = '".$user."' AND user_two = '".$myself."') AND status = 1");
		foreach ($sql as $item) {
			if (($item['user_one'] == $user) && ($item['user_two'] == $myself)){
				return true;
			} else {
				return false;
			}
		}
	}

	function getUserAvatar($user) {
		$lowusr = strtolower($user);

	    $query = R::getAll("SELECT * FROM skins WHERE Nick = '".$lowusr."'");
	    foreach ($query as $item){
	        $value = $item['Value'];
	    }
	    $decoded = json_decode(base64_decode($value), true);
	    $profileID = $decoded['profileId'];

	    return $profileID;
	}

	function isUserOnline($user) {
		$sql = R::getAll("SELECT * FROM `users` WHERE `username` = '".$user."'");
  		foreach ($sql as $item) {
    		$online = $item['isLogged'];
  		}
  		if ($online == 1) {
  			return true;
  		} else {
  			return false;
  		}
	}

	function getUserLastLogin($user){
		$sql = R::getRow("SELECT `lastlogin` FROM `users` WHERE `username` = '".$user."'");
		return $sql['lastlogin'];
	}

	function isUserAdmin($user) {
		$sql = R::getRow("SELECT `nick` FROM `admins` WHERE nick = '".$user."'");
		foreach ($sql as $adm) {
			$admin = $adm;
		}
		if ($admin == $user) {
			return true;
		} else {
			return false;
		}
	}

	function createPost($type, $author, $date, $title, $desc) {
		R::exec("INSERT INTO `posts` VALUES (NULL, '".$type."', '".$author."', '".$date."', '".$title."', '".$desc."')");
		return true;
	}

	function generateCardNumber($user){
		$cnum = rand(100100, 999999);
		$sql = R::getAll("SELECT `card` FROM `bank` WHERE login != '".$user."'");
		foreach($sql as $item){
			$getcard = $item[0];
		}
		if ($getcard == $cnum){
			generateCardNumber($user);
		} else {
			return $cnum;
		}
	}

	function getUserRolesFull($user) {
		$sql = R::getAll("SELECT * FROM `roles` WHERE login = '".$user."'");
		return $sql;
	}

	function getColorByCodeInColorList($code, $array){
		$color = array_search($code, $array);
		return $color;
	}

	function hexToRGB($hex){
		if ($hex[0] == '#') {
			$hex = mb_substr($hex, 1);
		}
		$hex = str_split($hex);
		$rgb1 = $hex[0].$hex[1];
		$rgb2 = $hex[2].$hex[3];
		$rgb3 = $hex[4].$hex[5];

		$rgb1 = hexdec($rgb1);
		$rgb2 = hexdec($rgb2);
		$rgb3 = hexdec($rgb3);

		$rgb = $rgb1.', '.$rgb2.', '.$rgb3;
		return $rgb;
	}
?>