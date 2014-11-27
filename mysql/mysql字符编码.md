## MySQL 字符编码

为了在mysql应用中尽量不出现乱码的情况，要让 **客户端** 到 **服务器** 的传输，再到 **mysql服务** ，再到 **mysql应用数据** 都要保持一致。这里统一使用UTF8。


1, 常用Xshell通过服务器上的mysql客户端进行操作，需要Xshell的字符编码调整为UTF-8。

2, `my.cnf` 进行配置，定义了mysql客户端/服务端的编码，同时对mysql连接执行`set names utf8` 的初始操作。

```
[client]
default-character-set=utf8

[mysql]
default-character-set=utf8

[mysqld]
collation-server=utf8_unicode_ci
character-set-server=utf8
init-connect='SET NAMES utf8' 
```

> 这里mysql服务端和客户端在同一机器上。

3, 服务器编码定义 `echo "export LC_ALL=en_US.UTF-8" >> /etc/profile && source /etc/profile` 

> 可能会出现shell环境中可以输入中文，但是mysql命令行中输入中文回显为空。因为mysql依赖 `LC_CTYPE`变量的值，编码的不一致导致了mysql处理过程中退出，所以输入没有任何显示。定义了 `LC_ALL` 宏的值，即可定义包含 `LC_CTYPE` 在内的一组变量，可以使用 `locale` 命令查看。

4, 如果要导入sql文件，也要先保证sql文件已经转换到了统一的编码。

5, 程序的编码统一。

6, 最不靠谱的因素是人。
