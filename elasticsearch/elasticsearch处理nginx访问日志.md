## elasticsearch 处理nginx访问日志

---

环境及软件版本：

- OS: CentOS release 6.5
- Java: 1.8.0_31
- logstash-1.4.2-1_2c0f5a1.noarch
- elasticsearch-1.5.0-1.noarch
- kibana-4.0.1-linux-x64

### nginx

nginx访问日志格式：

```
set $uid "-";
if ( $http_cookie ~* "PHPSESSID=(\S+)(;.*|$)" ){
  set $uid $1;
}

log_format  access  '$remote_addr - $remote_user [$time_local] "$request" '
 '$status $body_bytes_sent $request_time "$http_referer" '
 '"$http_user_agent" $http_x_forwarded_for request_body:$request_body "$uid"';
```

> 为了统计UV，将客户端的cookie信息进行记录。

### logstash

logstash配置文件： `logstash.conf`

> `output` 中的  `index` 为elasticsearch中索引名称，和后边创建的索引模板对应。

> GeoLite databases 文件 `GeoLiteCity.dat` 需额外下载。

> [Grok语法调试](http://grokdebug.herokuapp.com/)

### elasticsearch

elasticsearch添加如下配置：

```
path.data: /data/elasticsearch/
action.auto_create_index: +nginx-access*,-*
```

因为默认的logstash模板映射生成索引中某些字段格式有问题，所以进行添加自定义模板，模板json文件：`template-nginx-access.json` 。

启动了三个服务之后，进行手动创建当天索引：`curl -XPUT http://localhost:9200/_template/nginx-access -d @template-nginx-access.json` 。  创建的模板： `http://localhost:9200/_template/nginx-access?pretty`

然后在kibana页面上进行索引名称的指定， `Index name or pattern` 指定为 `nginx-access-*` ，`Time-field name` 选择 `@timestamp` 进行创建，并将其设为默认。创建完成之后进行核对索引中个字段的格式是否正确，`Discover` 页面中可以查出日志数据，说明日志已成功写入elasticsearch，之后就可以进行各种数据的统计展示。

logstash有数据写入到elasticsearch中，就可查看到已经索引的数据，通过URL获取到10个文档：`http://localhost:9200/nginx-access-*/_search?pretty`
