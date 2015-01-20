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

- connection 连接有关选项。用来管理持久连接，或者代理转发时须删除的首部字段
- date 报文创建时间
- MIME-Version MIME版本
- via 显示报文经过的中间节点，即代理/网关

缓存首部：
 
- cache-control 缓存指示
 - no-cache 在请求中指客户端不接收缓存过的响应，缓存服务器必须把客户端的请求转发给源服务器；在响应中指缓存服务器不能对资源进行缓存。
 - max-age 指定资源缓存时长，值为0指不让缓存服务器缓存 。在HTTP/1.1中，会优先处理max-age，而忽略掉Expires首部。
 - no-transform 缓存不能改变实体主体的媒体类型，防止缓存或者代理服务器压缩图片等文件。
- pragma  不专用于缓存

请求首部：

- Client-IP 客户端IP
- From 客户端Email地址
- Host 接收请求的主机地址和端口号。这是HTTP/1.1中 **唯一一个必须被包含的请求首部字段** 
- Referer 当前请求URI文档的URL
- User-Agent 发起请求的程序名称及版本
- Accept 请求的MIME类型，以及类型相对优先级。使用 `q=权重值` 表示，使用 `;` 进行分隔。权重值的范围 0-1，1最大，为默认值。  
- Accept-Charset 客户端支持的字符集，及相对优先级
- Accept-Encoding 客户端支持的内容编码，及相对优先级
- Accept-Language 客户端支持的语言，及相对优先级
- Cookie 
- Max-Forward 请求经过代理或网关的最大次数，为0的时候不再转发，直接响应
- Proxy-Authorization 与代理认证使用
- Proxy-Connection 与代理建立连接使用
- TE 告知服务器客户端能够处理响应的传输编码方式，以及相对优先级

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
- Content-Encoding 服务器对实体的主机部分选用的内容编码方式

实体缓存首部：

- Expires 实体不再有效，要从原始服务器再次获取此实体的日期和时间
- Last-Modified 实体最后一次被修改的日期时间

条件首部：

- If-XXX 服务器接收到请求，判断条件为真才执行请求
- If-Match 值与实体标记(ETag)的值一致，服务器才接收请求
- If-None-Match 与If-Match相反，值不一致才处理请求
- If-Range 与ETag的值一致才执行范围请求，否则返回全体资源
