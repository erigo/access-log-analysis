#! /usr/bin/env php
<?php
date_default_timezone_set('PRC');
$log_file = "access.log";

$handle = fopen($log_file, "r");

if (!$handle) {
    echo "Open file failed!";
    exit(0);
}

while (($line = fgets($handle)) !== false) {
    //example: 140.207.54.73:48034|-    09/Dec/2016:18:15:19 +0800     HTTP/1.1        POST example.com/official/wx5a8906923df68d6e/callback/?signature=3c21ba25f1cebcbba6aaa9651578ca5136af7cff&timestamp=1481278519&nonce=1535289932&openid=o5FYFj7YBXW1dPWzv-PJ0FD2oJHc&encrypt_type=aes&msg_signature=0a2b16958a6c56dbfba3d21e07294d29941df732       |200|       638        "-" "Mozilla/4.0"      192.168.150.13:8006  0.011 200
    preg_match('/([\d+\.]+:\d+\|\-)\s+(.+ \+0800)\s+(.+)/', $line, $matchs);
    $request_time = date('YmdHi', strtotime($matchs[2]));
    if (isset($ret[$request_time])) {
        $ret[$request_time] = $ret[$request_time] + 1;
    } else {
        $ret[$request_time] = 1;
    }
}

fclose($handle);
arsort($ret);
echo "TOP 20 RPM:\n";
foreach ($ret as $req_time => $num) {
    $i++;
    echo $req_time . "\t" . $num . "\n";
    if ($i >= 20) {
        break;
    }
}
