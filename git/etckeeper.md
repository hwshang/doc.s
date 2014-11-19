## etckeeper

###功能
备份/etc目录下文件到git仓库。并且融合了yum/rpm，安装到/etc下新文件之后，会调用etckeeper进行自动提交更改到版本库。

> Gistore工具可针对指定目录

---

###使用

安装/初始化/提交
```
yum install etckeeper
etckeeper init
etckeeper commit "etckeeper init"
```

> 现在/etc就是一个git仓库，可进行git操作。

---

###配置

配置文件 `/etc/etckeeper/etckeeper.conf`

* 指定remote:
```
PUSH_REMOTE="origin"
```

> 可push到多个remote，比如指定为“origin gitalb github”


* 默认配置中，yum操作会自动提交安装操作之前的更新。
如果去掉了配置文件中的注释`#AVOID_COMMIT_BEFORE_INSTALL=1`，有文件更新的情况下yum会安装失败:

```
etckeeper: pre transaction commit

** etckeeper detected uncommitted changes in /etc prior to yum run
** Aborting yum run. Manually commit and restart.

etckeeper returned 1
```

* 每天自动提交更新，去掉注释则不自动执行
```
#AVOID_DAILY_AUTOCOMMITS=1
```
