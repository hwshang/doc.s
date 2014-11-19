## etckeeper

###功能

备份/etc目录下文件到git仓库

特点

- 融合了yum/rpm，安装到/etc下新文件之后，会调用etckeeper进行自动提交更改到版本库。
- 方便结合Gitlab/GitWeb
- `/etc/.etckeeper` 记录文件的元信息
 
不足
 
-  git不追踪空目录

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

> 其实这个配置文件中指定了etckeeper运行时所需的变量，通过`/usr/bin/etckeeper` shell脚本调用。

* 指定remote，配置： `PUSH_REMOTE="origin"`

> 可push到多个remote，比如指定为“origin gitalb github”


* 默认配置中，yum会自动提交安装操作之前的更新

> 如果去掉了配置文件中的注释`#AVOID_COMMIT_BEFORE_INSTALL=1`，有文件更新的情况下yum会安装失败:

> ```
> etckeeper: pre transaction commit

> ** etckeeper detected uncommitted changes in /etc prior to yum run
> ** Aborting yum run. Manually commit and restart.

> etckeeper returned 1
> ```

* 每天自动提交更新，`#AVOID_DAILY_AUTOCOMMITS=1` 去掉注释则不自动执行。通过调用系统的crond执行

```
# cat /etc/cron.daily/etckeeper 
#!/bin/sh
set -e
if [ -x /usr/bin/etckeeper ] && [ -e /etc/etckeeper/etckeeper.conf ]; then
	. /etc/etckeeper/etckeeper.conf
	if [ "$AVOID_DAILY_AUTOCOMMITS" != "1" ]; then
		# avoid autocommit if an install run is in progress
		lockfile=/var/cache/etckeeper/packagelist.pre-install
		if [ -e "$lockfile" ] && [ -n "$(find "$lockfile" -mtime +1)" ]; then
			rm -f "$lockfile" # stale
		fi
		if [ ! -e "$lockfile" ]; then
			AVOID_SPECIAL_FILE_WARNING=1
			export AVOID_SPECIAL_FILE_WARNING
			if etckeeper unclean; then
				etckeeper commit "daily autocommit" >/dev/null
			fi
		fi
	fi
fi
```

> 在配置文件中，默认注释的三项配置去掉，会忽略一些功能。而不是像常规那样配置所需功能。
 