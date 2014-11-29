##代理

###代理与网关

代理是两个或多个使用相同协议的应用程序。
网关是两个或多个使用不同协议的端点。用于转换协议。
代理也可以完成一些协议转换工作。

###功能

- 过滤作用
- 访问权限控制
- 安全防护
- Web缓存
- 方向代理
- 路由
- 转码
- 匿名代理 处理请求报文中的客户端信息，提高私密性

###流量获取

- 客户端使用代理
- 流量拦截
- DNS修改
- Web服务器上进行重定向

**客户端代理**
手动配置
浏览器代理配置
PAC(proxy auto-configuration)  JavaScript编写的配置文件，客户端需要请求的URI根据文件匹配，决定是否以及使用哪个代理。
WPAD 自动检测从哪下载PAC文件。

PAC文件必须定义一个FindProxyForURL(url, host)的函数。

| FindProxyForURL返回值 | 描述 |
| ---   | --- |
| DIRECT | 不使用代理 |
| PROXY host:port | 使用指定代理 |
| SOCKS host:port | 使用指定的SOCKS服务器 | 

