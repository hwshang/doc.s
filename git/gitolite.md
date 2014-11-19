## gitolite

gitolite 也是通过git仓库的形式管理authorized_keys 文件和权限控制。
gitosis在2009年之后就不再更新，gitolite在权限控制上也更加详细。

安装

```
# useradd git
# su - git
# cd /tools/
# git clone git://github.com/sitaramc/gitolite
# gitolite/install -to /bin/
```

初始化及添加操作客户端的公钥

```
# su - git
$ gitolite setup -pk /tmp/id_rsa.pub 
Initialized empty Git repository in /home/git/repositories/gitolite-admin.git/
Initialized empty Git repository in /home/git/repositories/testing.git/
WARNING: /home/git/.ssh missing; creating a new one
    (this is normal on a brand new install)
WARNING: /home/git/.ssh/authorized_keys missing; creating a new one
    (this is normal on a brand new install)

$ rm /tmp/id_rsa.pub
```

> 可以看到自动创建了`gitolite-admin` `testing` 两个repo，并在git用户的家目录下写入了客户端的公钥文件。

客户端验证并clone gitolite-admin库

```
$ ssh  -T git@192.168.5.23
hello id_rsa, this is git@rpmbuild running gitolite3 v3.6.2-4-g2471e18 on git 1.7.1

 R W	gitolite-admin
 R W	testing

$ git clone git@192.168.5.23:gitolite-admin
```

帮助信息

```
$ ssh git@192.168.5.23 help
hello id_rsa, this is git@rpmbuild running gitolite3 v3.6.2-4-g2471e18 on git 1.7.1

list of remote commands available:

	desc
	help
	info
	perms
	writable
```


权限管理仓库文件

```
$ cd gitolite-admin/
$ tree
.
├── conf
│   └── gitolite.conf
└── keydir
    └── id_rsa.pub

2 directories, 2 files
```

> `gitolite.conf` 权限管理配置文件，`id_rsa.pub` 为客户端的公钥文件，即本机生成的公钥文件。

权限管理

```
$ cat conf/gitolite.conf 
repo gitolite-admin
    RW+     =   id_rsa

repo testing
    RW+     =   @all
```

授权语法：
```
@team = user1 user2 user3

repo <版本库>[/指定目录]

<权限> [正则表达式匹配] = <user> [ <user> ... ]
```

>  组可以包含其他组。
> 版本库必须指定一个权限。

权限：

- C 创建，只在通配符版本库授权时可以时候
- R,RW,RW+ 表示 只读，读写，和读写加强制执行
- RWC,RW+C 使用了正则定义分支/tag时才可使用，C代表允许创建匹配到的分支/tag
- RWD,RW+D 使用了正则定义分支/tag时才可使用，D代表允许删除匹配到的分支/tag
- RECD,RE+CD 使用了正则定义分支/tag时才可使用，可创建或者删除匹配到的分支/tag
- `-` 禁用指令,只会禁用写操作。如果指定的用户在其他指令中包含了读权限则不影响读权限
