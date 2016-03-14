### zabbix api

---

认证字符串获取方法

`curl -i -X POST -H 'Content-Type: application/json' -d '{"jsonrpc": "2.0","method":"user.login","params":{"user":"root","password":"qwertyuiop"},"id":0}' http://zabbix.hichao.com/api_jsonrpc.php`

> 需替换`uri` , `user` , `password`

[**zabbix user_media 管理**](zabbix_usermedia.php)

