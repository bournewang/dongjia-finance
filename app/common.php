<?php
// use Cache;
use Carbon\Carbon;
function ___($str) {
    return __(ucwords(str_replace('_', ' ', $str)));
}

function cache1($key, $callback, $expires = null)
{
    // \Log::debug(__FUNCTION__);
    // $cache = Cache::tags($tags);
    if (Cache::has($key)){
        \Log::debug(" ------- from   cache $key");
        return Cache::get($key);
    }else{
        $value = call_user_func($callback);
        $str = json_encode($value);
        \Log::debug(" +++++++ refresh cache $key $str");
        Cache::put($key, $str, $expires ?? 3600 * 24);
        return $str;
    }
}
function tag_user($user) {
    if (is_int($user) || is_string($user)) {
        return "user.$user";
    }else{
        return "user.$user->id";
    }
}

function flush_tag($tag){
    Cache::tags($tag)->flush();
}

function hash2array($hash)
{
    $arr = [];
    foreach($hash as $val => $label){
        $arr[] = ['value' => $val, 'display' => $label];
    }
    return $arr;
}

function money($val)
{
    return !$val ? "-" : sprintf(__('RMB')."%.2f", $val);
}

function debug($str)
{
    \Log::debug($str);
}
