<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Accept, Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$_ISPC = true;

if (isset($_GET['box_mac'])) {
    $_ISPC = false;
    foreach ($_GET['cookie'] as $k => $v) {
        $_COOKIE[$k] = $v;
    }
}

$ip   = $_SERVER['REMOTE_ADDR'];
$cat  = $_GET['cat'];
$p    = $_GET['p'];
$resp = $_GET['resp'];
if (isset($_GET['search']) && strpos($_GET['search'], ';&#') !== false) {
    $_GET['search'] = str_replace('%20', ' ', html_entity_decode($_GET['search']));
    header('decsearch: ' . $_GET['search']);
}
$search        = $_GET['search'];
$siteurl       = 'http://p.lnka.ru/kinopub';
$siteicon      = 'http://p.lnka.ru/kinopubicon';
$CLIENT_ID     = 'xbmc';
$CLIENT_SECRET = 'cgg3gtifu46urtfp2zp1nqtba0k2ezxh';
$logged        = 0;

$_PL     = [];
$_CH     = [];
$_MENU   = [];
$_MENU[] = ['title' => 'kinopub', 'logo_30x30' => 'https://kino.pub/images/logo.png', 'playlist_url' => "$siteurl/"];
$_MENU[] = [
  'title'        => 'Поиск',
  'search_on'    => 'Название или имя',
  'logo_30x30'   => "$siteicon/search.png",
  'playlist_url' => "$siteurl/?cat=search"
];

$_MENU[] = [
  'title'        => 'ТВ',
  'logo_30x30'   => "$siteicon/sport.png",
  'parser'       => "https://api.service-kp.com/v1/tv/index?access_token=$_COOKIE[access_token]",
  'playlist_url' => "$siteurl/?cat=tv&resp=postmd5"
];

$_MENU[] = [
  'title'        => 'Новинки',
  'logo_30x30'   => "$siteicon/new_releases.png",
  'playlist_url' => "$siteurl/?cat=" . urlencode("type=popular") . "&ttl=" . urlencode("Новинки")
];
$_MENU[] = [
  'title'        => 'Подборки',
  'logo_30x30'   => "$siteicon/list.png",
  'playlist_url' => "$siteurl/?cat=collections&ttl=" . urlencode("Подборки")
];
$_MENU[] = ['title' => 'Спидтест', 'logo_30x30' => 'none', 'playlist_url' => "$siteurl/?cat=speedtest"];
//$_MENU[]=['title'=>"Test ACE Stream",'logo_30x30'=>'none','playlist_url'=>"$siteurl/?do=othertest&mbaceip=MB_ACE_IP&mbparser=md5mbhash","local"=>2,"mb_parser"=>"http://MB_ACE_IP:6878/webui/api/service?method=get_version&format=jsonp&callback=mycallback"];

$SUB        = [];
$biblioteka = [
  'Фильмы|type=movie|movie.png',
  'Сериалы|type=serial|tv.png',
  '4K|type=4k|4k.png',
  '3D|type=3d|3d_rotation.png',
  'Концерты|type=concert|library_music.png',
  'Докуфильмы|type=documovie|archive.png',
  'Докусериал|type=docuserial|description.png',
  'ТВ Шоу|type=tvshow|live_tv.png'
];
foreach ($biblioteka as $k => $v) {
    $t     = explode('|', $v);
    $SUB[] = [
      'title'        => (string)$t[0],
      'logo_30x30'   => "$siteicon/$t[2]",
      'playlist_url' => "$siteurl/?cat=" . urlencode($t[1]) . "&ttl=" . urlencode($t[0])
    ];
}
$_MENU[] = [
  'title'        => 'Библиотека',
  'logo_30x30'   => "$siteicon/drop_down.png",
  'playlist_url' => 'submenu',
  'submenu' => $SUB
];

$_PL['style']['cssid']['menu']['backgroundColor'] = "#171a23";

$_PL['style']['cssid']['site']['color']           = 'white';
$_PL['style']['cssid']['site']['backgroundColor'] = "#1c202b";
//$_PL['style']['cssid']['site']["fontFamily"]='sans-serif';

$_PL['style']['channels']['chnumber']['default']['display']  = 'none';
$_PL['style']['channels']['chnumber']['selected']['display'] = 'none';

//$_PL['style']['channels']['parent']['default']["fontSize"]=$_PL['style']['channels']['parent']['selected']["fontSize"]=$_PL['style']['cssid']['menu']["fontSize"]="85%";

$_PL['style']['channels']['contmenu']['selected']['background'] = 'none #02b875';

$_PL['style']['channels']['parent']['default']['background']  = 'none';
$_PL['style']['channels']['parent']['selected']['background'] = 'none #02b875';
$_PL['style']['channels']['parent']['default']['color']       = 'white';
$_PL['style']['channels']['parent']['selected']['color']      = 'white';

$_PL['style']['menu']['parent']['default']['background']    = 'none #171a23';
$_PL['style']['menu']['parent']['selected']['background']   = 'none #02b875';
$_PL['style']['menu']['parent']['selected']['borderColor']  = 'transparent';
$_PL['style']['menu']['parent']['selected']['borderRadius'] = '2px';
$_PL['style']['menu']['parent']['default']['borderRadius']  = '2px';
$_PL['style']['menu']['parent']['default']['color']         = 'white';
$_PL['style']['menu']['parent']['selected']['color']        = 'white';

$_PL['color'] = '#b0b1b5';
$_PL['icon']  = 'https://kino.pub/images/logo.png';

if (isset($_GET['speedtestserv'])) {
    $iframe = '<body style="background-color:black;color:white;text-align:center;">
		<div id="resspeed" style="font-size:140%;">Измерение скорости к серверу {loc}, ожидайте...</div>
		<script>
		var downloadSize = 2; //Mb
		var fileURL = "https://{loc}-speed.streambox.in/garbage.php?r="+Math.random()+"&ckSize="+downloadSize;

var request = new XMLHttpRequest();
request.open("GET", fileURL, true);
var startTime = (new Date()).getTime();
var endTime = startTime;
request.onreadystatechange = function () {
    if (request.readyState == 2)
    {
        //ready state 2 is when the request is sent
        startTime = (new Date().getTime());
    }
    if (request.readyState == 4)
    {
        endTime = (new Date()).getTime();

        var time = (endTime - startTime) / 1000;
        var sizeInBits = downloadSize * 8;
        var speed = ((sizeInBits / time)).toFixed(2);
        console.log(downloadSize, time, speed);
		document.getElementById("resspeed").innerHTML="{loc}: "+speed+"Mbit/s";
    }
}
request.send();
</script>
</body>';
    print str_replace('{loc}', $_GET['speedtestserv'], $iframe);
    exit;
}
if ($_GET['code'] === 'exit') {
    $_PL['setcookie']['expires_in']    = '';
    $_PL['setcookie']['access_token']  = '';
    $_PL['setcookie']['refresh_token'] = '';
    $_COOKIE                           = [];
    unset($_GET['code']);
} elseif (isset($_GET['code'])) {
    if ($_GET['code'] === 'prolong') {
        $res = request(
          'https://api.service-kp.com/oauth2/device',
          $_COOKIE = [
            'grant_type'    => 'refresh_token',
            'client_id'     => $CLIENT_ID,
            'client_secret' => $CLIENT_SECRET,
            'refresh_token' => $_COOKIE['refresh_token']
          ]
        );
        unset($_GET['code']);
    } else {
        $res = request(
          'https://api.service-kp.com/oauth2/device',
          $data = [
            'grant_type'    => 'device_token',
            'client_id'     => $CLIENT_ID,
            'client_secret' => $CLIENT_SECRET,
            'code'          => $_GET['code']
          ]
        );
    }
    //print_r($res);
    if (!empty($res['error'])) {
        $_PL['info'] = "Устройство не активировано!<br>Убедитесь что ввели код на сайте<br> $res[error]";
    } else {
        $res['expires_in'] = time() + $res['expires_in'];
        $_COOKIE           = $res;
        $_PL['setcookie']  = $res;
        valid_auth($res);
    }
} else {
    if (!empty($_COOKIE['access_token']) && $_COOKIE['expires_in'] < time()) {
        header('valid_auth: reauth');
        $_CH[]           = [
          'logo_30x30'   => '',
          'location'     => 3,
          'title'        => 'Продление токена.',
          'playlist_url' => "$siteurl/?code=prolong&cat=$cat&id=$id&act=$act"
        ];
        $_PL['channels'] = $_CH;

        return;
    }
    valid_auth($_COOKIE);
}

if (!$logged && !isset($_GET['code'])) {
    $_PL['navigate'] = "Кинопаб Активация";
    $res             = request(
      "https://api.service-kp.com/oauth2/device",
      $data = [
        'grant_type'    => "device_code",
        'client_id'     => $CLIENT_ID,
        'client_secret' => $CLIENT_SECRET
      ]
    );
    $_CH[]           = [
      'logo_30x30'   => "$siteicon/new_releases.png",
      'title'        => "Проверить активацию",
      'playlist_url' => "$siteurl/?code=$res[code]",
      "description"  => '<style>
 h1,h4,h6 {
     text-align:center;
 }
 .device_code {
     color: #b8a125;
 }
</style>
<div>
    <h4>Активация устройства</h4>
    <h6>код активации</h6>
    <h1 class="device_code">' . $res["user_code"] . '</h1>
    <div>
        Посетите <b>' . $res["verification_uri"] . '</b> и введите там код активации.
        Когда на сайте появится "Ожидание устройства", на пульте нажмите выбор "Проверить активацию"
    </div>
    </div>'
    ];
} else {
    $SUB     = [];
    $SUB[]   = ['title' => "Мои закладки", 'logo_30x30' => '', 'playlist_url' => "$siteurl/?cat=bookmarks"];
    $SUB[]   = ['title' => "Я смотрю", 'logo_30x30' => '', 'playlist_url' => "$siteurl/?cat=watching&n=serials"];
    $_MENU[] = [
      'title'        => "Закладки",
      'logo_30x30'   => "$siteicon/drop_down.png",
      'playlist_url' => "submenu",
      "submenu"      => $SUB
    ];
    $_MENU[] = ['title' => "Выйти", 'logo_30x30' => 'none', 'playlist_url' => "$siteurl/?code=exit"];

    if (empty($_GET["cat"])) {
        $_PL["typeList"] = 'start';
        $_MENU[]         = [
          'title'        => "Поиск",
          "search_on"    => "Название или имя",
          'logo_30x30'   => "$siteicon/search.png",
          'playlist_url' => "$siteurl/?cat=search"
        ];
        $_PL['navigate'] = "Кинопаб (kinopub)";
        request(
          "https://api.service-kp.com/v1/device/notify?access_token=$_COOKIE[access_token]",
          $data = [
            'title'    => "ForkPlayer Portal",
            "hardware" => "$_GET[box_hardware]",
            "software" => "ForkPlayer2.5"
          ]
        );
        $_CH[] = [
          'logo_30x30'   => 'none',
          'title'        => "Добавить в закладки / стартовое меню",
          'playlist_url' => "AddFavorite(Кинопаб,https://kino.pub/images/logo.png,http://p.lnka.ru/kinopub/);",
          "position"     => "hlink"
        ];
        $_CH[] = [
          'logo_30x30'   => 'none',
          'title'        => "Добавить в Глобальный поиск",
          'playlist_url' => "AddSearch(Кинопаб,https://kino.pub/images/logo.png,http://p.lnka.ru/kinopub/?cat=search);",
          "position"     => "hlink"
        ];

        $main = [
          "Популярные фильмы"  => "type=movie&sort=views-&conditions=" . urlencode("year=" . date("Y")),
          "Новые фильмы"       => "type=movie&sort=created-",
          "Популярные сериалы" => "type=serial&sort=watchers-",
          "Новые сериалы"      => "type=serial&sort=created-",
          "Новые концерты"     => "type=concert&sort=created-",
          "Новое в 3D"         => "type=3D&sort=created-",
          "Новые ДокуФильмы"   => "type=documovie&sort=created-",
          "Новые Докусериалы"  => "type=docuserial&sort=created-",
          "Новые ТВ шоу"       => "type=tvshow&sort=created-"
        ];


        $res = request("https://api.service-kp.com/v1/watching/serials?subscribed=1");

        if (count($res["items"])) {
            $_CH[] = [
              'logo_30x30'   => 'none',
              'title'        => "Продолжить просмотр",
              "position"     => "label",
              'playlist_url' => "$siteurl/?cat=watching&n=serials"
            ];
            for ($i = 0; $i < count($res["items"]) && $i < 18; $i++) {
                $el                            = $res["items"][$i];
                $_CH[]                         = itemToCh($el);
                $_CH[count($_CH) - 1]['title'] .= " ($el[new])";
                $_CH[count(
                  $_CH
                ) - 1]["description"]          = "Новых серий: $el[new]<br>Всего серий: $el[total]<br>Просмотрено: $el[watched]<br>";
            }
        }
        foreach ($main as $k => $v) {
            $_CH[] = [
              'logo_30x30'   => 'none',
              'title'        => "$k",
              "position"     => "label",
              'playlist_url' => "$siteurl/?cat=" . urlencode($v)
            ];
            $res   = request("https://api.service-kp.com/v1/items?$v", 7200);
            for ($i = 0; $i < count($res["items"]) && $i < 18; $i++) {
                $_CH[] = itemToCh($res["items"][$i]);
            }
        }
    } elseif ($cat == "speedtest") {
        $TITLE = "Speedtest ";
        $loc   = ["de", "nl", "ru"];

        foreach ($loc as $k => $v) {
            $_CH[] = [
              'logo_30x30'   => '',
              'title'        => "$v - сервер",
              'playlist_url' => "cmd:setdescription($k,<iframe src='$siteurl/?speedtestserv=$v' width=500 height=400></iframe>);",
              "description"  => "Нажмите, чтобы измерить"
            ];
        }
    } elseif ($cat == "tv") {
        $TITLE                                                       = "TV ";
        $_PL['style']['channels']['chnumber']['default']['display']  = '';
        $_PL['style']['channels']['chnumber']['selected']['display'] = '';
        $_PL["is_iptv"]                                              = 1;
        if (!empty($resp)) {
            $res = json_decode($resp, true);
        } else {
            $res = request("https://api.service-kp.com/v1/tv/index", 3600);
        }
        foreach ($res['channels'] as $k => $v) {
            $_CH[] = ['logo_30x30' => $v["logos"]["s"], 'title' => $v['title'], "stream_url" => $v["stream"]];
        }
    } elseif ($cat == "view") {
        $res = request("https://api.service-kp.com/v1/items/$_GET[id]");
        //print_r($res);
        $_CH[]                  = itemToCh($res["item"]);
        $_CH[0]['title']        = "Трейлер";
        $_CH[0]['playlist_url'] = '';
        if (isset($res["item"]["trailer"]["url"])) {
            $_CH[0]["stream_url"] = $res["item"]["trailer"]["url"];
        }
        if ($res["item"]["type"] != "serial" && $res["item"]["type"] != "docuserial") {
            foreach ($res["item"]["videos"] as $k => $v) {
                $SUB       = [];
                $audio     = '';
                $subtitles = [];
                foreach ($v["subtitles"] as $kk => $vv) {
                    if (!empty($vv["url"])) {
                        $subtitles[] = [$vv["lang"], $vv["url"]];
                    }
                }
                foreach ($v["audios"] as $kk => $vv) {
                    $audio .= $vv["type"]['title'] . " " . $vv["author"]['title'] . "<br>";
                }
                foreach ($v["files"] as $kk => $vv) {
                    //$SUB2=[];
                    foreach ($vv["url"] as $kkk => $vvv) {
                        $SUB[] = [
                          "ident"       => "kinopub$_GET[id]",
                          "start_time"  => 0,
                          'logo_30x30'  => $_CH[0]['logo_30x30'],
                          'title'       => "$vv[quality] $kkk " . $res["item"]['title'],
                          "stream_url"  => $vvv,
                          "subtitles"   => $subtitles,
                          "description" => "$audio<br>Качество: $vv[quality]<br>Тип видеопотока: $kkk<br>" . $res["item"]['title'],
                          "event"       => [
                            "onstartvideo" => "$siteurl/?cat=watching&id=$_GET[id]&time=0&event=onstartvideo&video=$v[number]",
                            "onstopvideo"  => "$siteurl/?cat=watching&id=$_GET[id]&curTime=[curTime]&totalTime=[totalTime]&event=onstopvideo&video=$v[number]"
                          ]
                        ];
                    }
                    //$SUB[]=['logo_30x30'=>'none','title'=>"$vv[quality]",'playlist_url'=>"submenu","submenu"=>$SUB2];
                }
                if (empty($v['title'])) {
                    $v['title'] = "Файлы и папки";
                }
                if (count($res["item"]["videos"]) == 1) {
                    $_CH = array_merge($_CH, $SUB);
                } else {
                    $_CH[] = [
                      'logo_30x30'   => "$siteicon/pl.png",
                      'title'        => "$v[title]",
                      'playlist_url' => "submenu",
                      "submenu"      => $SUB
                    ];
                }
            }
        } else {
            if ($res["item"]["in_watchlist"]) {
                $_CH[] = [
                  'logo_30x30'   => "$siteicon/bookmark_empty.png",
                  'title'        => "Я смотрю. Отписаться?",
                  'playlist_url' => "$siteurl/?cat=watching&id=$_GET[id]&act=togglewatchlist"
                ];
            } else {
                $_CH[] = [
                  'logo_30x30'   => "$siteicon/bookmark_empty.png",
                  'title'        => "Подписаться?",
                  'playlist_url' => "$siteurl/?cat=watching&id=$_GET[id]&act=togglewatchlist"
                ];
            }
            $q   = [];
            $SUB = [];
            $qq  = ["http", "hls", "hls4", "hls2"];
            foreach ($res["item"]["seasons"] as $sk => $sv) {
                foreach ($sv["episodes"] as $ek => $ev) {
                    $subtitles = [];
                    $event     = [];
                    if ($ev["watched"] != 1) {
                        $t                     = [" &bull; "];
                        $event["onstartvideo"] = "$siteurl/?cat=watching&id=$_GET[id]&event=watched&video=$ev[number]&season=$sv[number]";
                    } else {
                        $t = [" "];
                    }
                    //$event["onstartvideo"]="$siteurl/?cat=watching&id=$_GET[id]&time=0&event=onstartvideo&video=$ev[number]&season=$sv[number]";
                    foreach ($ev["subtitles"] as $kk => $vv) {
                        if (!empty($vv["url"])) {
                            $subtitles[] = [$vv["lang"], $vv["url"]];
                        }
                    }
                    foreach ($ev["files"] as $kk => $vv) {
                        $q[$vv["quality"]] = $qq;
                        foreach ($vv["url"] as $kkk => $vvv) {
                            $SUB[$vv["quality"] . $kkk][] = [
                              "ident"       => "kinopub$_GET[id]_$sv[number]_$ev[number]",
                              'logo_30x30'  => $_CH[0]['logo_30x30'],
                              'title'       => "$t[0]Сезон $sv[number] серия  $ev[number] $ev[title] " . "$t[1]",
                              "stream_url"  => $vvv,
                              "subtitles"   => $subtitles,
                              "description" => "Качество: $vv[quality]<br>Тип видеопотока: $kkk<br>" . $res["item"]['title'],
                              "event"       => $event
                            ];
                        }
                    }
                }
            }
            foreach ($q as $k => $v) {
                $SUB2 = [];
                foreach ($v as $kk => $vv) {
                    $SUB2[] = [
                      'logo_30x30'   => "$siteicon/pl.png",
                      'title'        => "$k $vv",
                      'playlist_url' => "submenu",
                      "submenu"      => $SUB[$k . $vv]
                    ];
                }
                $_CH[] = [
                  'logo_30x30'   => "$siteicon/pl.png",
                  'title'        => "$k",
                  'playlist_url' => "submenu",
                  "submenu"      => $SUB2
                ];
                //$_CH[]=['logo_30x30'=>"$siteicon/pl.png",'title'=>"$v[title]",'playlist_url'=>"submenu","submenu"=>$SUB];
            }
        }
        $res = request("https://api.service-kp.com/v1/items/similar?id=$_GET[id]", 7200);
        for ($i = 0; $i < count($res["items"]) && $i < 5; $i++) {
            $_CH[] = itemToCh($res["items"][$i]);
        }

        $res2 = request("https://api.service-kp.com/v1/items/comments?id=$_GET[id]", 7200);
        foreach ($res2["comments"] as $k => $v) {
            $_CH[] = [
              'logo_30x30'  => $v["user"]["avatar"],
              'title'       => $v["user"]["name"] . ": $v[message]",
              "description" => $v["user"]["name"] . " <small>" . date(
                  "d.m.Y H:i",
                  $v["created"]
                ) . "</small><br>$v[message]"
            ];
        }
    } elseif ($cat == "watching") {
        if ($_GET["act"] == "togglewatchlist") {
            $res = request("https://api.service-kp.com/v1/watching/togglewatchlist?id=$_GET[id]");
            if ($res["watching"]) {
                $_PL["cmd"] = "settitle(1,Я смотрю. Отписаться?);stop();";
            } else {
                $_PL["cmd"] = "settitle(1,Подписаться?);stop();";
            }
            //print_r($res);
        } elseif (!empty($_GET["event"])) {
            if ($_GET["event"] == "watched") {
                $res = request(
                  "https://api.service-kp.com/v1/watching/toggle?id=$_GET[id]&video=$_GET[video]&season=$_GET[season]"
                );
                if (!$res["watched"]) {
                    $res = request(
                      "https://api.service-kp.com/v1/watching/toggle?id=$_GET[id]&video=$_GET[video]&season=$_GET[season]"
                    );
                }
            } else {
                $res = request(
                  "https://api.service-kp.com/v1/watching/marktime?id=$_GET[id]&time=$_GET[time]&video=$_GET[video]&season=$_GET[season]"
                );
            }

            print_r($res);
            exit;
        } else {
            $TITLE = "Я смотрю ";
            $res   = request("https://api.service-kp.com/v1/watching/$_GET[n]?subscribed=1");

            for ($i = 0; $i < count($res["items"]); $i++) {
                $el                            = $res["items"][$i];
                $_CH[]                         = itemToCh($el);
                $_CH[count($_CH) - 1]['title'] .= " ($el[new])";
                $_CH[count(
                  $_CH
                ) - 1]["description"]          = "Новых серий: $el[new]<br>Всего серий: $el[total]<br>Просмотрено: $el[watched]<br>";
            }
        }
    } elseif ($cat == "search") {
        $TITLE = "Поиск $search ";
        $res   = request("https://api.service-kp.com/v1/items/search?q=$search&field=title&page=$p", 9600);
        //print_r($res);
        for ($i = 0; $i < count($res["items"]); $i++) {
            $_CH[] = itemToCh($res["items"][$i]);
        }
        addPages($res["pagination"]);
    } elseif ($cat == "bookmarks") {
        if (!empty($search)) {
            $res            = request(
              "https://api.service-kp.com/v1/bookmarks/create?access_token=$_COOKIE[access_token]",
              "title=$search"
            );
            $_GET["folder"] = $res["folder"]["id"];
        }
        if ($_GET["act"] == "del" && !empty($_GET["folder"])) {
            request(
              "https://api.service-kp.com/v1/bookmarks/remove-folder?access_token=$_COOKIE[access_token]",
              "folder=$_GET[folder]"
            );
            $_GET["folder"] = '';
        }
        if ($_GET["act"] == "delbookm") {
            $res = request(
              "https://api.service-kp.com/v1/bookmarks/remove-item?access_token=$_COOKIE[access_token]",
              "folder=$_GET[folder]&item=$_GET[id]"
            );
            if ($res["status"] == 200) {
                $_PL["notify"] = "Закладка $_GET[title] удалена!";
                $_PL["cmd"]    = "reload(2);";
            } else {
                $_PL["cmd"] = "stop();";
            }
        } elseif ($_GET["act"] == "addbookm") {
            $TITLE = "Добавить в закладки / $_GET[title] ";
            $res   = request("https://api.service-kp.com/v1/bookmarks/get-item-folders?item=$_GET[id]");
            if (count($res["folders"]) > 0) {
                $_PL["notify"] = "$_GET[title] уже есть в папке " . $res["folders"][0]['title'];
                $_PL["cmd"]    = "stop();";
            } else {
                $_CH[] = [
                  'title'        => "Добавить в новую папку",
                  "search_on"    => "Введите имя папки",
                  "description"  => '',
                  'logo_30x30'   => "$siteicon/add_box.png",
                  'playlist_url' => "$siteurl/?cat=$cat&id=$_GET[id]"
                ];
                $res   = request("https://api.service-kp.com/v1/bookmarks");
                for ($i = 0; $i < count($res["items"]); $i++) {
                    $el    = $res["items"][$i];
                    $_CH[] = [
                      'title'        => $el['title'] . " ($el[count])",
                      "description"  => "Количество: $el[count]<br>Просмотров: $el[views]",
                      'playlist_url' => "$siteurl/?cat=$cat&folder=$el[id]&id=$_GET[id]",
                      'menu'         => [
                        [
                          'title'        => "Удалить папку",
                          "description"  => "Вы уверены что хотите удалить $el[title]?",
                          'playlist_url' => "confirm",
                          "confirm"      => ["$siteurl/?cat=$cat&folder=$el[id]&act=del", '']
                        ]
                      ]
                    ];
                }
                //print_r($res);
            }
        } elseif (!empty($_GET["id"]) && $_GET["act"] != "delbookm") {
            $res = request(
              "https://api.service-kp.com/v1/bookmarks/add?access_token=$_COOKIE[access_token]",
              "folder=$_GET[folder]&item=$_GET[id]"
            );
            if ($res["status"] == 200) {
                $_PL["notify"] = "Закладка $_GET[id] добавлена";
                $_PL["cmd"]    = "historyback(1);";
            }
        } elseif (!empty($_GET["folder"])) {
            $TITLE = "Закладки " . $_GET["ttl"];
            $res   = request("https://api.service-kp.com/v1/bookmarks/$_GET[folder]?page=$p");

            for ($i = 0; $i < count($res["items"]); $i++) {
                $_CH[] = itemToCh($res["items"][$i]);
            }
            addPages($res["pagination"]);
        } else {
            $TITLE = "Закладки";
            $res   = request("https://api.service-kp.com/v1/bookmarks");
            for ($i = 0; $i < count($res["items"]); $i++) {
                $el    = $res["items"][$i];
                $_CH[] = [
                  'logo_30x30'   => 'none',
                  'title'        => $el['title'] . " ($el[count])",
                  "description"  => "Количество: $el[count]<br>Просмотров: $el[views]",
                  'playlist_url' => "$siteurl/?cat=$cat&folder=$el[id]&ttl=" . urlencode($el['title']),
                  'menu'         => [
                    [
                      'title'        => "Удалить папку",
                      "description"  => "Вы уверены что хотите удалить $el[title]?",
                      'playlist_url' => "confirm",
                      "confirm"      => ["$siteurl/?cat=$cat&folder=$el[id]&act=del", '']
                    ]
                  ]
                ];
            }
            $_CH[] = [
              'title'        => "Добавить папку",
              "search_on"    => "Введите имя папки",
              "description"  => '',
              'logo_30x30'   => "$siteicon/add_box.png",
              'playlist_url' => "$siteurl/?cat=$cat"
            ];
        }
        if (count($_CH) == 0) {
            $_CH[] = ['title' => "Здесь пусто", 'logo_30x30' => 'none'];
        }
    } elseif ($cat == "collections") {
        //$_PL["typeList"]='start';
        $SUB   = [];
        $SUB[] = ['title' => "Новые", 'logo_30x30' => 'none', 'playlist_url' => "$siteurl/?cat=$cat&sort=updated-"];
        $SUB[] = ['title' => "Популярные", 'logo_30x30' => 'none', 'playlist_url' => "$siteurl/?cat=$cat&sort=views-"];
        $SUB[] = [
          'title'        => "Просматриваемые",
          'logo_30x30'   => 'none',
          'playlist_url' => "$siteurl/?cat=$cat&sort=watchers-"
        ];
        if (empty($_GET["sort"])) {
            $_CH[] = [
              'logo_30x30'   => "$siteicon/list.png",
              'title'        => "Сортировка: по обновлению",
              'playlist_url' => "submenu",
              "submenu"      => $SUB,
              ''
            ];
        }
        $res = request("https://api.service-kp.com/v1/collections?perpage=40&sort=$_GET[sort]&page=$p", 9600);
        //print_r($res);
        for ($i = 0; $i < count($res["items"]); $i++) {
            $el    = $res["items"][$i];
            $_CH[] = [
              'logo_30x30'   => $el["posters"]["small"],
              'title'        => $el['title'] . '',
              "description"  => "Зрителей: $el[watchers]<br>Просмотров: $el[views]<br>Обновлено: " . date(
                  "d.m.Y H:i",
                  $el["updated"]
                ),
              'playlist_url' => "$siteurl/?cat=viewpodb&podbid=$el[id]"
            ];
        }
        addPages($res["pagination"]);
    } else {
        $_PL["typeList"] = 'start';
        $SUB             = [];
        $SUB[]           = [
          'title'        => "Горячие видео",
          'logo_30x30'   => 'none',
          'playlist_url' => "$siteurl/?cat=$cat&podb=/hot&ttl=$_GET[ttl]"
        ];
        $SUB[]           = [
          'title'        => "Популярные видео",
          'logo_30x30'   => 'none',
          'playlist_url' => "$siteurl/?cat=$cat&podb=/popular&ttl=$_GET[ttl]"
        ];
        $SUB[]           = [
          'title'        => "Свежие видео",
          'logo_30x30'   => 'none',
          'playlist_url' => "$siteurl/?cat=$cat&podb=/fresh&ttl=$_GET[ttl]"
        ];
        if (empty($_GET["podbid"]) && empty($_GET["podb"]) && strpos($cat, "sort") === false) {
            $_CH[] = [
              'logo_30x30'   => "$siteicon/list.png",
              'title'        => "Сортировка: по обновлению",
              'playlist_url' => "submenu",
              "submenu"      => $SUB,
              "description"  => "Горячие / Популярные / Свежие",
              "position"     => "label"
            ];
        }
        if (!empty($_GET["podbid"])) {
            $res = request("https://api.service-kp.com/v1/collections/view?id=$_GET[podbid]&page=$p", 7200);
        } else {
            $res = request("https://api.service-kp.com/v1/items$_GET[podb]?$cat&page=$p", 7200);
        }
        //if($ip=="185.158.114.122") print_r($res);
        for ($i = 0; $i < count($res["items"]); $i++) {
            $_CH[] = itemToCh($res["items"][$i]);
        }
        addPages($res["pagination"]);
        if (empty($_GET["podbid"])) {
            $TITLE         = (empty($_GET["ttl"]) ? $res["items"][0]["type"] : $_GET["ttl"]) . " " . ($res["pagination"]["current"] ? " стр. " . $res["pagination"]["current"] : '');
            $_PL["before"] = "<div style='text-align: left;margin: 5px 47px;color: #07a366;'><span style='color: #cbcbcb;font-weight: bold;'>" . (empty($_GET["ttl"]) ? $res["items"][0]["type"] : $_GET["ttl"]) . "</span> " . $_GET["podb"] . "  <span style='color: #cbcbcb;font-weight: bold;float:right;'>Страница: " . $res["pagination"]["current"] . " из " . $res["pagination"]["total"] . "</span></div>";
        } else {
            $TITLE = "Подборка " . $res["collection"]['title'] . ($res["pagination"]["current"] ? " стр. " . $res["pagination"]["current"] : '');
        }
    }
    //print_r($res);
}

if (!empty($TITLE)) {
    $_PL['title']    = "$TITLE kinopub";
    $_PL['navigate'] = "Кинопаб (kinopub) - $TITLE";
}

// Установка на желтую кнопку своей ссылки
for ($i = 0, $iMax = count($_CH); $i < $iMax; $i++) {
    if (empty($_GET["cat"])) {
        $_CH[$i]["yellow"] = [
          'title'        => "Закладки КиноПаб",
          'logo_30x30'   => '',
          'playlist_url' => "$siteurl/?cat=bookmarks"
        ];
    } else {
        $_CH[$i]["yellow"] = ['title' => "Главная КиноПаб", 'playlist_url' => "$siteurl/"];
    }
}
// End Установка на желтую кнопку своей ссылки

$_PL['menu']      = $_MENU;
$_PL['channels']  = $_CH;
$_PL["all_local"] = "directly";


function addPages($p)
{
    global $siteurl, $_PL, $TITLE, $_CH;
    if ($_PL["typeList"] == 'start') {
        if ($p["current"] > 1) {
            $_CH[] = [
              'title'        => "Стр. " . ($p["current"] - 1),
              'playlist_url' => "$siteurl/?cat=$_GET[cat]&sort=$_GET[sort]&folder=$_GET[folder]&podb=$_GET[podb]&podbid=$_GET[podbid]&ttl=$_GET[ttl]&p=" . ($p["current"] - 1),
              "position"     => "hlink"
            ];
        }
        if ($p["current"] < $p["total"]) {
            $_CH[] = [
              'logo_30x30'   => 'none',
              'title'        => "Стр. " . ($p["current"] + 1),
              'playlist_url' => "$siteurl/?cat=$_GET[cat]&sort=$_GET[sort]&folder=$_GET[folder]&podb=$_GET[podb]&podbid=$_GET[podbid]&ttl=$_GET[ttl]&p=" . ($p["current"] + 1),
              "position"     => "hlink"
            ];
        }
    } elseif ($p["current"] < $p["total"]) {
        $_PL["next_page_url"] = "$siteurl/?cat=$_GET[cat]&sort=$_GET[sort]&folder=$_GET[folder]&podb=$_GET[podb]&podbid=$_GET[podbid]&ttl=$_GET[ttl]&p=" . ($p["current"] + 1);
    }
}

function itemToCh($el)
{
    global $siteurl, $_PL;
    $genres = '';
    foreach ($el["genres"] as $k => $v) {
        $genres .= "$v[title] ";
    }
    $countries = '';
    foreach ($el["countries"] as $k => $v) {
        $countries .= "$v[title] ";
    }
    if ($_GET["cat"] == "bookmarks") {
        $menu[] = [
          'logo_30x30'   => "$siteicon/add_box.png",
          'title'        => "Удалить из закладок kinopub",
          'playlist_url' => "$siteurl/?cat=bookmarks&folder=$_GET[folder]&act=delbookm&id=$el[id]&title=" . urlencode(
              $el['title']
            )
        ];
    } else {
        $menu[] = [
          'logo_30x30'   => "$siteicon/add_box.png",
          'title'        => "Добавить в закладки kinopub",
          'playlist_url' => "$siteurl/?cat=bookmarks&act=addbookm&id=$el[id]&title=" . urlencode($el['title'])
        ];
    }
    if ($_PL["typeList"] == 'start') {
        $title = $el['title'];
        $desc  = $el["year"] . " " . $genres;
    } else {
        $title = $el['title'] . " " . $el["year"] . " " . $genres;
        $desc  = "<div id=\"poster\" style=\"float:left;margin:0px 13px 1px 0px;\"><img src=\'' . $el["posters"]["small"] . "\" style=\"width:180px;float:left;\" /></div> Рейтинг imdb $el[imdb_rating] kp $el[kinopoisk_rating]<br>Год выхода	<span style=\"color:#6cc788;\">$el[year]</span><br>Страна	<span style=\"color:#6cc788;\">$countries</span><br>Жанр	<span style=\"color:#6cc788;\">$genres</span><br>Режиссёр	<span style=\"color:#6cc788;\">$el[director]</span><br>В ролях	<span style=\"color:#6cc788;\">" . mb_substr(
            $el["cast"],
            0,
            80
          ) . "</span><br>Длительность	<span style=\"color:#6cc788;\">" . seconds_to_time(
            $el["duration"]["average"]
          ) . " / (" . ceil(
            $el["duration"]["average"] / 60
          ) . " мин)</span><br>Субтитры	<span style=\"color:#6cc788;\">$el[subtitles]</span><br>Просмотрели	<span style=\"color:#6cc788;\">$el[views] раз</span><br>$el[plot]";
    }
    $E = [
      'logo_30x30'   => $el["posters"]["small"],
      'title'        => $title,
      'playlist_url' => "$siteurl/?cat=view&id=$el[id]",
      "description"  => $desc,
      'menu'         => $menu
    ];
    if ($_PL['typeList'] === 'start') {
        $E['position'] = 'bigtile';
    }

    return $E;
}

function valid_auth($data)
{
    global $logged;
    if (empty($data['access_token'])) {
        $logged = 0;
    } else {
        $logged = 1;
    }
}


function request($u, $data = '')
{
    global $ip, $_PL, $_CH, $siteurl;
    $cacheName = '';
    if (!is_array($data) && !is_string($data) && (int)$data > 30) {
        if (!preg_match("/\/(user|bookmarks)/", $u)) {
            if (!file_exists('/var/www/cache/kinopub')) {
                if (!mkdir('/var/www/cache/kinopub', 0777, true) && !is_dir('/var/www/cache/kinopub')) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', '/var/www/cache/kinopub'));
                }
            }
            $cacheName = '/var/www/cache/kinopub/' . md5($u);
            if (time() < filemtime($cacheName) + (int)$data) {
                $res = file_get_contents($cacheName);
                if (!empty($res)) {
                    return json_decode($res, true, 512, JSON_THROW_ON_ERROR);
                }
            }
        }
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR: $ip"));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    if (is_array($data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR, 512));
    } elseif (is_string($data) && !empty($data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
        if (strpos($u, '?') !== false) {
            $u .= '&';
        } else {
            $u .= '?';
        }
        $u .= "access_token=$_COOKIE[access_token]";
    }
    curl_setopt($ch, CURLOPT_URL, $u);
    $res     = curl_exec($ch);
    $jsonRes = json_decode($res, true, 512, JSON_THROW_ON_ERROR);
    if (isset($jsonRes['error'])) {
        if (false !== strpos($jsonRes['error'], 'unauthorized')) {
            $_CH[]           = [
              'logo_30x30'   => '',
              'location'     => 1,
              'title'        => 'Удалить авторизацию.',
              'playlist_url' => "$siteurl/?code=exit"
            ];
            $_PL['channels'] = $_CH;

            return;
        }
        $_PL['notify'] .= " $jsonRes[error]";
    }
    if (!empty($cacheName) && (!isset($jsonRes['status']) || (int)$jsonRes['status'] === 200)) {
        file_put_contents($cacheName, $res);
    }

    //print $u."\n".$res;
    return $jsonRes;
}

function seconds_to_time($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));

    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes             = floor($divisor_for_minutes / 60);

    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds             = ceil($divisor_for_seconds);

    //create string HH:MM:SS
    $ret = $hours . ":" . $minutes . ":" . $seconds;

    return ($ret);
}


print json_encode($_PL);



