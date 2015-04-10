### elasticsearch 处理nginx访问日志

---

环境及软件版本：

 OS: CentOS release 6.5
 Java: 1.8.0_31
 logstash-1.4.2-1_2c0f5a1.noarch
 elasticsearch-1.5.0-1.noarch
 kibana-4.0.1-linux-x64

nginx访问日志格式：

```
log_format  access  '$remote_addr - $remote_user [$time_local] "$request" '
 '$status $body_bytes_sent $request_time "$http_referer" '
 '"$http_user_agent" $http_x_forwarded_for request_body:$request_body';
```

logstash配置文件： `logstash.conf`

elasticsearch添加如下配置：

`path.data: /data/elasticsearch/
action.auto_create_index: +nginx-access*,-*
index.mapper.dynamic: false`

因为动态映射生成索引中某些字段格式有问题，所以通过定时任务执行脚本来创建索引，脚本：`lek_create_index.sh` ，索引映射json文件：`nginx-access.json`

启动了三个服务之后，进行手动创建当天索引：`curl -XPUT http://localhost:9200/nginx-access-$(date +%Y.%m.%d) -d @nginx-access.json`

然后在kibana页面上进行索引名称的指定， `Index name or pattern` 指定为 `nginx-access-*` ，`Time-field name` 选择 `@timestamp` 进行创建，并将其设为默认。创建完成之后进行核对索引中个字段的格式是否正确，之后就可以进行各种数据的统计展示。
