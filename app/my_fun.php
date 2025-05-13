<?php

use Illuminate\Support\Facades\Log;
//自訂 array_get
if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }

        if ($key === null) {
            return $array;
        }

        $keys = explode('.', $key);
        foreach ($keys as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }
}

//自訂 str_limit
if (!function_exists('str_limit')) {
    function str_limit($string, $limit = 100, $end = '...') {
        // 使用 mb_strlen 获取字符串长度，避免中文字符被误认为多个字符
        if (mb_strlen($string, 'UTF-8') <= $limit) {
            return $string; // 如果字符串长度小于或等于限制，则直接返回原始字符串
        }
        
        // 使用 mb_substr 截取字符串，并在末尾添加省略号
        return mb_substr($string, 0, $limit, 'UTF-8') . $end;
    }
}

//顯示某目錄下的檔案
if (!function_exists('get_files')) {
    function get_files($folder){
    // 确保传入的是有效目录
    if (!is_dir($folder)) {
        return [];  // 如果不是有效的目录，返回空数组
    }

    // 获取目录中的所有文件
    $files = scandir($folder);

    // 使用 array_filter 去除掉 . 和 ..
    $files = array_filter($files, function ($file) {
        return $file !== '.' && $file !== '..';
    });

    // 排序文件
    sort($files);

    // 返回排序后的文件
    return array_values($files);
    }
}

//刪除某目錄所有檔案
if (!function_exists('del_folder')) {
    function del_folder($folder)
    {
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != "..") {
                        //去除掉..跟.
                        unlink($folder . '/' . $file);
                    }
                }
                closedir($handle);
            }
            rmdir($folder);
        }
    }
}

//檢查是否為教育處、學校的一級A的管理人員(教育處審核公告、資料填報；學校審核資料填報)
if (!function_exists('check_a_user')) {
    function check_a_user($section_id, $user_id)
    {
        //信義國中小
        if ($section_id === "074774" or $section_id === "074541") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074774')->orWhere('section_id', '074541');
                })
                ->first();
            //原斗國中小
        } elseif ($section_id === "074745" or $section_id === "074537") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074745')->orWhere('section_id', '074537');
                })
                ->first();
            //民權國中小
        } elseif ($section_id === "074760" or $section_id === "074543") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074760')->orWhere('section_id', '074543');
                })
                ->first();
            //鹿江國中小
        } elseif ($section_id === "074542" or $section_id === "074778") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074542')->orWhere('section_id', '074778');
                })
                ->first();
        } else {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('section_id', $section_id)
                ->where('power_type', 'A')
                ->first();
        }
        if ($user_power) {
            return true;
        } else {
            return false;
        }
    }
}

//檢查是否為教育處、學校的二級B的人員(教育處發公告、資料填報；學校簽收公告、資料填報)
if (!function_exists('check_b_user')) {
    function check_b_user($section_id, $user_id)
    {
        //信義國中小
        if ($section_id === "074774" or $section_id === "074541") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074774')->orWhere('section_id', '074541');
                })
                ->first();
            //原斗國中小
        } elseif ($section_id === "074745" or $section_id === "074537") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074745')->orWhere('section_id', '074537');
                })
                ->first();
            //民權國中小
        } elseif ($section_id === "074760" or $section_id === "074543") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074760')->orWhere('section_id', '074543');
                })
                ->first();
            //鹿江國中小
        } elseif ($section_id === "074542" or $section_id === "074778") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074542')->orWhere('section_id', '074778');
                })
                ->first();
        } else {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('section_id', $section_id)
                ->where('power_type', 'B')
                ->first();
        }

        if ($user_power) {
            return true;
        } else {
            return false;
        }
    }
}


// 勾選的資料用 bit 的概念儲存成 5 個值
if (!function_exists('checkbox_val')) {
    function checkbox_val($value_array)
    {
        $item_value = array(0, 0, 0, 0, 0);
        foreach ($value_array as $value) {
            if ($value > 0) {
                $set_idx = floor(($value - 1) / 63);
                if ($set_idx > 0)
                    $value %= 63;
                if ($value == 0)
                    $value = 63;
                $item_value[$set_idx] += pow(2, $value - 1);
            }
        }
        return $item_value;
    }
}

// 多個儲存值(陣列形式)轉換成 1,2,3,8,9,22 ... 的字串
if (!function_exists('checkbox_str_num')) {
    function checkbox_str_num($value_array, $split = ', ')
    {

        $out = '';
        $set_idx = 0;
        foreach ($value_array as $value) {
            $mask = 1;
            for ($i = 1; $i <= 63; $i++) {
                if (($value & $mask) > 0) {
                    if ($out != '')
                        $out .= $split;
                    $out .= $i + $set_idx * 63;
                }
                $mask <<= 1;
            }
            $set_idx++;
        }
        return $out;
    }
}


//自動判斷url是否有http，否則自動補齊
if (!function_exists('transfer_url_http')) {
    function transfer_url_http($url)
    {
        if (!($url)) {
            return null;
        } else {
            if (substr($url, 0, 8) == 'https://') {
                return $url;
            } elseif (substr($url, 0, 7) == 'http://') {
                return $url;
            } else {
                return 'http://' . $url;
            }
        }
    }
}

//將userid轉成中文名稱
if (!function_exists('userid2name')) {
    function userid2name($user_id)
    {
        $name = \App\Models\User::where('id', $user_id)
            ->first();
        $name = $name['name'];
        return $name;
    }
}

//轉為kb
if (!function_exists('filesizekb')) {
    function filesizekb($file)
    {
        return number_format(filesize($file) / pow(1024, 1), 2, '.', '');
    }
}

//發email
if (!function_exists('send_mail')) {
    function send_mail($to, $subject, $body)
    {
        $data = array("subject" => $subject, "body" => $body, "receipt" => "{$to}");
        $data_string = json_encode($data);
        $ch = curl_init('https://school.chc.edu.tw/api/mail');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'AUTHKEY: #chc7237182#'
            )
        );
        $result = curl_exec($ch);
        $obj = json_decode($result, true);
        if ($obj["success"] == true) {
            //echo "<body onload=alert('已mail通知')>";
        };
    }
}

if (!function_exists('chk_ie_browser')) {
    function chk_ie_browser()
    {
        if ($_SERVER['HTTP_USER_AGENT']) {
            $userAgent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
            if (preg_match('~MSIE|Internet Explorer~i', $userAgent) || (strpos($userAgent, 'Trident/7.0') !== false && strpos($userAgent, 'rv:11.0') !== false)) {
                $usingie = true;
            } else {
                $usingie = false;
            }
            return $usingie;
        }

        return false;
    }
}

function get_tree($menus, $i)
{
    foreach ($menus as $menu) {
        if ($menu->type == 1) {
            for ($k = 0; $k < $i; $k++) {
                echo "　　　";
            }
            echo "<i class=\"fas fa-folder text-warning\"></i> (" . $menu->order_by . ")" . $menu->name . " <a href=\"" . route('menu_edit', $menu->id) . "\"><i class='fas fa-edit'></i></a> <a href=\"" . route('menu_del', $menu->id) . "\" onclick=\"return confirm('連同底下連結一起刪喔！？')\"><i class=\"fas fa-times-circle text-danger\"></i></a><br>";
            $menu2s = \App\Models\Menu::where('belong', $menu->id)
                ->orderBy('order_by')
                ->get();
            if ($menu2s->count() > 0) {
                get_tree($menu2s, $i + 1);
            }
        } elseif ($menu->type == 2) {
            for ($k = 0; $k < $i; $k++) {
                echo "　　　";
            }
            echo "<i class=\"fas fa-file-alt text-secondary\"></i> <a href=\"" . $menu->link . "\" target=\"_blank\"> (" . $menu->order_by . ")" . $menu->name . "</a> <a href=\"" . route('menu_edit', $menu->id) . "\"><i class='fas fa-edit'></i></a> <a href=\"" . route('menu_del', $menu->id) . "\" onclick=\"return confirm('確定刪除嗎？')\"><i class=\"fas fa-times-circle text-danger\"></i></a><br>";
        }
    }
}

function get_tree2($menus, $i)
{
    foreach ($menus as $menu) {
        if ($menu->type == 1) {
            echo "<li class='drop-down'><a href=''>" . $menu->name . "</a><ul>";
            $menu2s = \App\Models\Menu::where('belong', $menu->id)
                ->orderBy('order_by')
                ->get();
            if ($menu2s->count() > 0) {
                get_tree2($menu2s, $i + 1);
            }
            echo "</ul></li>";
        } elseif ($menu->type == 2) {
            echo "<li><a href='" . $menu->link . "' target='" . $menu->target . "'>" . $menu->name . "</a></li>";
        }
    }
}

function get_tree3($menus, $i)
{
    foreach ($menus as $menu) {
        if ($menu->type == 1) {
            echo "<li class='dropdown'><a href='#'><span>" . $menu->name . "</span> <i class='bi bi-chevron-down dropdown-indicator'></i></a><ul>";
            $menu2s = \App\Models\Menu::where('belong', $menu->id)
                ->orderBy('order_by')
                ->get();
            if ($menu2s->count() > 0) {
                get_tree3($menu2s, $i + 1);
            }
            echo "</ul></li>";
        } elseif ($menu->type == 2) {
            echo "<li><a href='" . $menu->link . "' target='" . $menu->target . "'>" . $menu->name . "</a></li>";
        }
    }
}

function logging($level, $event, $ip)
{
    $att['level'] = $level;
    $att['event'] = $event;
    $att['user_id'] = auth()->user()->id;
    $att['ip'] = $ip;
    \App\Models\Log::create($att);

    $message = $event . ' ' . auth()->user()->id . ' ' . $ip;
    switch ($level) {
        case 0:
            Log::emergency($message);
            break;
        case 1:
            Log::alert($message);
            break;
        case 2:
            Log::critical($message);
            break;
        case 3:
            Log::error($message);
            break;
        case 4:
            Log::warning($message);
            break;
        case 5:
            Log::notice($message);
            break;
        case 6:
            Log::info($message);
            break;
    }
}

function get_ip()
{
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (!empty($ip)) {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

function login_eroor_count($username)
{
    $dt = \Carbon\Carbon::now();
    $t = $dt->subMinutes(15)->format('Y-m-d H:i:s');
    $ip = get_ip();
    $check = \App\Models\LoginError::where('username', $username)
        ->where('ip', $ip)
        ->where('updated_at', '>', $t)
        ->first();
    if (empty($check)) {
        return 0;
    } else {
        return $check->error_count;
    }
}

function login_error_add($username)
{
    $dt = \Carbon\Carbon::now();
    $t = $dt->subMinutes(15)->format('Y-m-d H:i:s');
    $ip = get_ip();
    $check = \App\Models\LoginError::where('username', $username)
        ->where('ip', $ip)
        ->where('updated_at', '>', $t)
        ->first();
    $att['username'] = $username;
    $att['ip'] = get_ip();

    if (empty($check)) {
        $att['error_count'] = 1;
        \App\Models\LoginError::create($att);
    } else {
        if ($check->error_count < 3) {
            $att['error_count'] = $check->error_count + 1;
            $check->update($att);
        }
    }
}

function close_system(){
    if(file_exists(storage_path('app/privacy/close.txt'))){    
        $fp = fopen(storage_path('app/privacy/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/privacy/close.txt')));                
        fclose($fp);
        
        if($close == 1) \Illuminate\Support\Facades\Redirect::to('close')->send();
    }

}

function check_php($file){    
    $fileExtension = $file->getClientOriginalExtension();    
    if ($fileExtension === 'php') {
        return true;
    } 
        
    $mimeType = $file->getClientMimeType();

    if ($mimeType === 'text/x-php' || $mimeType === 'application/x-httpd-php') {
        return true;
    }
    
    $fileContent = file_get_contents($file->getRealPath());

    if (strpos($fileContent, '<?php') !== false) {
        return true;   
    }
}