## git 配置

***

####配置级别
Git的配置分三个级别(优先级从上向下，高级别配置会覆盖低级别)：

| 级别  |配置文件路径   |别名   |适用范围   |
| :---: | :----: | :---: |:---: |
|版本库 |项目目录下.git/config| |当前项目有效   |
|全局   |~/.gitconfig   |global |当前用户中所有项目 |
|系统级 |/etc/gitconfig |system |其当系统中所有用户 |

####用户信息
用户配置的`user.name`和`user.email`是用来标示身份的，会被写入历史记录中。
配置命令：

```
$ git config --global user.name "hwshang"
$ git config --global user.email "hwshang@yeah.net"
```
> 其中`--global`选项可去掉，或者替换为`--system`，定义不同级别配置。

####配置查看
查看已有配置，使用`git config --list`，如下(system级别)：

```
$ git config --list --system
core.symlinks=false
core.autocrlf=true
color.diff=auto
color.status=auto
color.branch=auto
...
...
```