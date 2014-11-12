## git 操作第四层


主要的两种传输协议： 哑协议 / 智能协议

**哑协议**

Git 基于HTTP之上传输通常被称为哑协议。传输过程通过GET完成。
通过HEAD的指向，以及各个对象之间的关系网，获取到所有的对象，并把HEAD的指向检出到工作区。

**智能协议**

在Git 远程服务器上有专门的读取并生成被请求数据的进程。传输进程分为两类： 一对上传进程/一对下载进程

上传进程： 推送数据时使用。 `send-pack` 的客户端进程 和 `receive-pack` 的服务器端进程。`send-pack` 通过ssh连接拿到服务器端的数据列表，将需要同步的数据发送给服务器端。

下载进程： 拉取数据时使用。`fetch-pack` 的客户端进程 和 `upload-pack` 的服务器端进程。`fetch-pack` 进程向远端发送请求信息，服务器端通过ssh通道或者git后台进程建立的端口进行接受请求，请求通过检查无误之后响应对象信息列表，客户端进行核对再次发起所需的请求来完成数据下载。

---

### git clone

git clone 的使用方法：

```
$ git clone git@github.com:hwshang/doc.s.git
$ git clone http://example.com/gitproject.git
$ git clone https://example.com/gitproject.git
$ git clone /home/shanghw/doc.s/ /home/shanghw/docs/
$ git clone /opt/git/project.git
$ git clone file:///opt/git/project.git
$ git clone ssh://user@server/project.git
$ git clone user@server:project.git
```

> git支持的协议：git/ssh/http[s]/ftp/rsync/file/本地

---

###git remote

用来管理远程主机

查看远程主机和地址

```
$ git remote
origin
$ git remote -v
origin	https://github.com/hwshang/doc.s.git (fetch)
origin	https://github.com/hwshang/doc.s.git (push)
```

查看远程主机详细信息，远程主机默认名称指定为`origin` 。

```
$ git remote show origin
* remote origin
  Fetch URL: https://github.com/hwshang/doc.s.git
  Push  URL: https://github.com/hwshang/doc.s.git
  HEAD branch: master
  Remote branch:
    master tracked
  Local branch configured for 'git pull':
    master merges with remote master
  Local ref configured for 'git push':
    master pushes to master (up to date)
```

添加远程主机

```
git remote add <name> <url>
```

删除远程主机

```
git remote rm  <name>
```

---

###git fetch

用来获取远程分支的更新

获取origin的master分支更新

```
$ git fetch origin master
```

> 不指定分支则获取指定主机上的所有分支更新

查看所有分支

```
$ git branch -a
* master
  remotes/origin/HEAD -> origin/master
  remotes/origin/master
```

> `origin/master` 是远程分支的格式

---

###git pull

用于获取远程分支的更新并**合并到本地分支**(与git fetch的区别)


合并origin上master分支的更新到本地的master上，前边的master为远程分支名，后边为本地分支名

```
$ git pull origin master:master
```

> 省略`:master`则合并到当前分支

手动建立远程和本地分支的对应关系

```
$ git branch --set-upstream master origin/master
```

---

###git post

用于推送本地分支的更新到远程分支上

推送本地master分支的更新到origin的master上，后边的master为远程分支

```
$ git push origin master:master
```

> 不指定本地分支，则代表把 **空** 推送到远程分支，会 **删除** origin的master分支

推送模式

```
$ git config push.default
simple
```

> simple 为默认，指只推送当前分支；matching会推送所有本地所有对应远程分支的分支

```
$ git push origin --tags
```

> 使用 `--tags` 参数，才会将本地的标签推送到远程

