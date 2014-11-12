## 版本库

####.git目录

在工作区目录下执行git相关操作，会在工作区目录中依次向上递归查询，直到找到.git目录，那么这个目录就是工作区对应的版本库。如果不在工作区执行git某些命令则会因为找不到.git目录报错。


特点：

- .git目录位于工作区根目录下，而在工作区其他子目录下是没有.git目录的，目录相对干净。
- 存放本地的版本库数据。
- 存放版本库配置信息。


文件：

`HEAD` 项目当前所处分支

`branches` 新版本git不再使用

`config` 项目配置文件

`description` 项目描述信息，仅供gitweb程序使用

`hooks` 项目的钩子脚本

`index` 项目文件的状态，保存暂存区信息

`info` 保存了一份不希望在 `.gitignore` 文件中管理的忽略模式 (ignored patterns) 的全局可执行文件。

`logs` 各refs的历史信息，其中HEAD文件保存了所有log信息，`git log` 命令指定不同的选项从中获取不同格式的log信息。

`objects` 本地仓库的对象数据存放目录，子目录为对象ID的前2位字符，对象ID的后38位是对应目录下的文件名。

`refs` 存储指向数据(分支)的提交对象的指针。


***

####工作区路径

显示版本库.git目录所在的位置
`git rev-parse --git-dir`

显示工作区根目录
`git rev-parse --show-toplevel`

显示于工作区根目录的相对目录
`git rev-parse --show-prefix`
