## 版本库工作区

####.git目录

在工作区目录下执行git相关操作，会在工作区目录中依次向上递归查询，直到找到.git目录，那么这个目录就是工作区对应的版本库。如果不在工作区执行git某些命令则会因为找不到.git目录报错。


特点：

- .git目录位于工作区根目录下，而在工作区其他子目录下是没有.git目录的，目录相对干净。
- 存放本地的版本库数据。
- 存放版本库配置信息。


文件：

`HEAD` 项目当前所处分支

`branches` 

`config` 项目配置文件

`description` 项目描述信息

`hooks` 项目的钩子脚本

`index` 项目文件的状态，使用`git ls-files --stage`命令其查看内容

`info` 

`logs` 各refs的历史信息

`objects` 本地仓库的数据

`packed-refs` 

`refs` 关联分支和提交信息


添加&提交：

`git add`将文件信息更新到index文件中，`git commit`将index文件中的改变写入本地仓库。

***

####工作区路径

显示版本库.git目录所在的位置
`git rev-parse --git-dir`

显示工作区根目录
`git rev-parse --show-toplevel`

显示于工作区根目录的相对目录
`git rev-parse --show-prefix`
