# git webhook
git的钩子实现
##Configuration
修改config.php
```PHP
$_config_data["test"]  每一组都是一个站点，你可以多加几个站点
比如
$_config_data["new"] = array();
 $_config_data["new"]["access_token"] = "secret-token";//密钥
 $_config_data["new"]["access_ip"] = array("192.168.2.18");//允许ip
 $_config_data["new"]["dir"] = "/home/wwwys/test/webhook/";//目录必填
 $_config_data["new"]["git_url"] = "git@192.168.2.18:czw/test.git";//git地址
 $_config_data["new"]["sh_clone"] = "git clone {$_config_data["test"]["git_url"]} {$_config_data["test"]["dir"]}";//git clone 命令 初次发布必填
 $_config_data["new"]["sh_pull"] = "cd {$_config_data["test"]["dir"]} && /usr/bin/git pull"; //git pull 命令 更新代码必填
```

##On GitHub | GitLab | Bitbucket

### GitHub

In your repository, navigate to Settings &rarr; Webhooks &rarr; Add webhook, and use the following settings:

- Payload URL: https://www.yoursite.com/webhook/index.php?k=new
- k 参数是你配置的数组key值
- Content type: application/json
- Secret: The value of TOKEN in config.php
- Which events would you like to trigger this webhook?: :radio_button: Just the push event
- Active: :ballot_box_with_check:

Click "Add webhook" to save your settings, and the script should start working.

![Example screenshot showing GitHub webhook settings](https://cloud.githubusercontent.com/assets/1123997/25409764/f05526d0-29d8-11e7-858d-f28de59bd300.png)

### GitLab

In your repository, navigate to Settings &rarr; Integrations, and use the following settings:

- URL: https://www.yoursite.com/webhook/index.php?k=new
- k 参数是你配置的数组key值
- Secret Token: The value of TOKEN in config.php
- Trigger: :ballot_box_with_check: Push events
- Enable SSL verification: :ballot_box_with_check: (only if using SSL, see [GitLab's documentation](https://gitlab.com/help/user/project/integrations/webhooks#ssl-verification) for more details)

Click "Add webhook" to save your settings, and the script should start working.

![Example screenshot showing GitLab webhook settings](https://cloud.githubusercontent.com/assets/1123997/25409763/f0540a16-29d8-11e7-95d1-5570c574fde0.png)

### Bitbucket

In your repository, navigate to Settings &rarr; Webhooks &rarr; Add webhook, and use the following settings:

- Title: git-deploy
- URL: https://www.yoursite.com/webhook/index.php?token=secret-token&k=new
- k 参数是你配置的数组key值
- Active: :ballot_box_with_check:
- SSL / TLS: :white_large_square: Skip certificate verification (only if using SSL, see [Bitbucket's documentation](https://confluence.atlassian.com/bitbucket/manage-webhooks-735643732.html#ManageWebhooks-skip_certificate) for more details)
- Triggers: :radio_button: Repository push

Click "Save" to save your settings, and the script should start working.

![Example screenshot showing Bitbucket webhook settings](https://cloud.githubusercontent.com/assets/1123997/25353602/7aee9cde-28f5-11e7-9baa-eb1e1330017e.png)
