<?php
    require_once '../bin/config.php';

    $url = 'http://oauth.vk.com/authorize';
    $params = [ 'client_id' => $vk_client_id, 'redirect_uri'  => $vk_redirect_uri, 'response_type' => 'code'];

    echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';

    if (isset($_GET['code'])) {
        $result = true;
        $params = [
            'client_id' => $vk_client_id,
            'client_secret' => $vk_client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => $vk_redirect_uri
        ];

        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        if (isset($token['access_token'])) {
            $params = [
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $token['access_token'],
                'v' => '5.101'];

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            if (isset($userInfo['response'][0]['id'])) {
                $userInfo = $userInfo['response'][0];
                $result = true;
            }
        }

        if ($result) {
            echo "ID пользователя: " . $userInfo['id'] . '<br />';
            echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
            echo "Ссылка на профиль: " . $userInfo['screen_name'] . '<br />';
            echo "Пол: " . $userInfo['sex'] . '<br />';
            echo "День Рождения: " . $userInfo['bdate'] . '<br />';
            echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
        }
    }
?>