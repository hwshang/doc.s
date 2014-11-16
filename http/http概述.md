## HTTP概述

###MIME
 
MIME类型来表示对象类型和子类型，中间用 `/` 分隔。
HTTP给所传输的数据使用 MIME 标识数据格式，浏览器针对不同的类型做不同的处理。
MIME有数百中类型，常见的如下：

 - `text/html`  HTML格式文档
 - `text/plain` 普通ASCII文本文档
 - `image/jpeg` JPEG图片

---

###URI

**URI** (uniform resource identifier) 统一资源标识符，分两种形式：URL / URN
**URL** 统一资源定位符，指某个资源在某服务器上的特定位置。目前几乎所有的URI都使用URL。
URL格式有三个部分：

- scheme ，指定访问资源使用的协议。通常为 `http://`
- 服务器的地址，比如`github.com`
- 资源在服务器上的位置，比如`/hwshang/doc.s`

URL支持`片段(frag)` 组件，表示一个资源内部的片段，使用 `#` 字符跟片段名称表示字段的某部分。浏览器获取了整个资源之后，根据片段名来显示指定的位置。比如`https://github.com/hwshang/doc.s/blob/master/git/git%E6%93%8D%E4%BD%9C%E7%AC%AC%E5%9B%9B%E5%B1%82.md#git-remote`

字符限制：

- % 字符转义标识
- / 路径定界符
- . 路径中使用
- .. 路径中使用
- # 分段定界符
- ? 查询字符串定界符
- ; 参数定界符
- : 方案,用户/口令,主机/端口的定界符
- $ , + 保留
- @ & = 某些方案代表某些含义
- 其他字符使用收到限制
 
**URN** 统一资源名，作为特定内容的唯一名称使用，与资源所在位置无关，方便资源迁移。目前处于试验阶段。

---

###HTTP报文

报文以文本形式的元信息(meta-information)开头。分三部分：起始行(start line)/首部(header)/主体(body)。body为可选部分。

- 起始行和首部是由行分隔的ASCII文本，以一个回车符加一个换行符结束。
- 主体是可选数据块，可以是文本或者二进制数据，可以**为空**。

**请求报文**格式:
```
<method> <request-URL> <version>
<headers>

<entity-body> 
```

**响应报文**格式:
```
<version> <status> <reason-phrase>
<headers>

<entity-body>
```

常见HTTP报文传输方法(请求报文中的`method`)：

- GET  获取服务器上文档
- PUT 将客户端数据存储到服务器上
- DELETE 删除服务器中资源
- POST 发送处理数据给服务器
- HEAD 仅响应HTTP头部
- OPTIONS 响应服务器支持的方法

**首部**

通用首部：

- connection 连接有关选项
- date 报文创建时间
- MIME-Version MIME版本
- via 显示报文经过的中间节点，即代理/网关

缓存首部：
 
- cache-control 缓存指示
- pragma  不专用于缓存

请求首部：

- Client-IP 客户端IP
- From 客户端Email地址
- Host 接收请求的主机地址和端口号
- Referer 当前请求URI文档的URL
- User-Agent 发起请求的程序名称及版本
- Accept 请求的MIME类型
- Accept-Charset 请求使用的字符集
- Accept-Encoding 请求使用的编码方式
- Accept-Language 请求使用的语言
- Cookie 
- Max-Forward 请求经过代理或网关的最大次数
- Proxy-Authorization 与代理认证使用
- Proxy-Connection 与代理建立连接使用

响应首部：

- Age 从创建开始的持续时间
- Retry-After 若资源不可用进行重试的时间/日期
- Server 服务器应用程序的名称及版本
- Title HTML的标题
- Warning 警告报文
- Vary 首部列表，用来挑选最合适的资源版本响应给客户端
- Set-Cookie 在客户端设置Cookie

实体首部：

- Allow 可以对此实体执行的请求方法
- Location 将接受端定向到资源的位置上

实体缓存首部：

- Expires 实体不再有效，要从原始服务器再次获取此实体的日期和时间
- Last-Modified 实体最后一次被修改的日期时间

