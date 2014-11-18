##HTTP协议

###http请求及响应过程

[当你输入一个网址的时候，实际会发生什么?](http://www.cnblogs.com/wenanry/archive/2010/02/25/1673368.html)

---

###基于TCP连接

HTTP协议基于TCP/IP协议。TCP接收到HTTP数据之后，处理成段进行网络传输。
TCP段数据由IP分组来定义，IP分组组成部分：

- 一个IP分组首部(包含源IP，目标IP及其他信息)，
- 一个TCP段首部(源端口，目标端口，TCP段序号，确认信息)，
- 一个TCP数据块(HTTP数据)。

> 一个HTTP请求可能会被分为多个TCP段，所以需要TCP段序号。

HTTP传输报文，以流的形式将数据通过已经连接的TCP连接按序传输。
HTTPS在HTTP和TCP之间插入了一个密码加密层，使用TLS或SSL。

---

###TCP套接字

TCP API   隐藏了所有底层的网络协议的握手细节，TCP数据流与IP分组之间的分段和重装细节。


