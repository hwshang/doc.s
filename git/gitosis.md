## Gitosis

Gitosis通过git仓库的形式管理authorized_keys 文件和权限控制。

### 安装部署

centos为例

```
yum install python-setuptools
```

> Gitosis 的工作依赖的Python 工具


```
git clone https://github.com/tv42/gitosis.git
cd gitosis
python setup.py install
```

gitosis的工作目录为/home/git，下面创建git用户

```
useradd git
```

把控制gitosis的机器公钥放到gitosis服务器上，临时使用一下

```
# git gitosis-init < /tmp/id_dsa.pub
Initialized empty Git repository in /opt/git/gitosis-admin.git/
Reinitialized existing Git repository in /opt/git/gitosis-admin.git/

# rm /tmp/id_dsa.pub
```

`post-update` 脚本加上可执行权限

```
chmod 755 /home/git/gitosis-admin.git/hooks/post-update
```

现在如果使用ssh去登陆服务器会有以下报错。

```
$ ssh git@gitserver
PTY allocation request failed on channel 0
ERROR:gitosis.serve.main:Need SSH_ORIGINAL_COMMAND in environment.
  Connection to gitserver closed.
```

> 说明 Gitosis 认出了该用户的身份，但由于没有运行任何 Git 命令，所以它切断了连接。

---

### 权限配置

控制客户端克隆gitosis库

```
git clone git@gitserver:gitosis-admin.git
```

可以看到gitosis-admin 库中的文件

```
$ tree gitosis-admin/
gitosis-admin/
├── gitosis.conf
└── keydir
    └── hwshang@yeah.net.pub
```

> `gitosis.conf` 为权限控制配置文件，`keydir` 目录下存储客户端机器的公钥文件。`hwshang@yeah.net.pub` 是控制gitosis的本机公钥文件。

配置新的仓库权限

```
$ cat gitosis.conf 
[gitosis]

[group gitosis-admin]
writable = gitosis-admin
members = hwshang@yeah.net

[group ah]
members = hwshang@yeah.net test
writable = ha1

[group ah-readonly]
members = shw
readonly = ha1
```

> `ah` `ah-readonly` 为权限配置的组名，`shw` `test` 是新用户的名称，`readonly` 指定只读权限的用户，`ha1` 为仓库名称。

添加新用户的公钥文件到`keydir` 目录下，文件名和 `gitosis.conf` 中配置的用户名对应。

```
$ cat keydir/shw.pub 
ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAuHKnDbUA6UZB4E6yiywS6Cby7FNOjQnR+Az/OEEnWfsU1a7PJ9jsK6x1mTo7JCruDordpAYgzG8VUE/KqFDd9ijq92/YZZ2H9DguzFHvZqhSXrchE+z2567zYBz1sY4UJ3bGj7VEKh9LZJ60Q3ClN/Hae+eaQvRspfHulWy0gLGhZVPwOnGJeaUXzkkngDzsCP1GERuLJH0NsUiIG0gP2wU9zpcc/Wm1qFV5GynrOIoxVh/g89cmEHOSriK3FXgHri7uLdcmJy5OWrHq4c8j4HRZb7zdFGrUKi8Hog/0YbKd+ePuqeOhle8DwjUNQmUG1jXJPwVzsUHe7x/HdULOBQ== rsa-key-20141113
```

接下来提交推送到服务器上，便可生效。

> 这时服务器上还有没有`ha1`仓库，当有用户来clone`ha1`仓库时，会自动创建，无须手动操作。



