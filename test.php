<?php
echo "<pre>";
echo "设置session_id\n";
$sessionid=zlsafe_session_id('test');//设置session_id

session_start();
echo "获取session_id\n";
var_dump($sessionid,zlsafe_session_id());//获取session_id
echo "session算法hash值\n";
var_dump(zlsafe_md5('test'));//session算法hash值
echo "zlsafe_session_id()和seesion_id()等效\n";
var_dump(session_id()==zlsafe_session_id());
$ip=$_SERVER['REMOTE_ADDR'];
echo "ip md5 $ip\n ";
var_dump(zlsafe_md5_real($ip),md5($ip));

var_dump(zlsafe_session_check());
var_dump(zlsafe_session_checkip());

var_dump($token=zlsafe_md5_encrypt('test'));
var_dump(zlsafe_session_check($token),zlsafe_session_checkip($token));
$token='111'.$token;
var_dump(zlsafe_session_check($token),zlsafe_session_checkip($token));//不是该服务器加密的数据返回false

$data=array('n'=>"name",'u'=>'user');
var_dump($retdata=zlsafe_array_encrypt($data,'1234567890123456','md5'));
var_dump($retdata=zlsafe_array_encrypt($data,'1234567890123456'));
var_dump(zlsafe_array_decrypt($retdata,'1234567890123456'));

var_dump($data=zlsafe_encrypt("test",'1234567890123456'));
var_dump($ret=zlsafe_decrypt($data,'1234567890123456'));



