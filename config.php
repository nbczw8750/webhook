<?php
$_config_data = array();
$_config_data["test"] = array();
$_config_data["test"]["access_token"] = "secret-token";//密钥
$_config_data["test"]["access_ip"] = array("192.168.2.18");//允许ip
$_config_data["test"]["dir"] = "/home/wwwys/test/webhook/";//目录必填
$_config_data["test"]["git_url"] = "git@192.168.2.18:czw/test.git";//git地址
$_config_data["test"]["sh_clone"] = "git clone {$_config_data["test"]["git_url"]} {$_config_data["test"]["dir"]}";//git clone 命令 初次发布必填
$_config_data["test"]["sh_pull"] = "cd {$_config_data["test"]["dir"]} && /usr/bin/git pull"; //git pull 命令 更新代码必填
return $_config_data;