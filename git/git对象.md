## git 对象

### 对象特点

 - git中每个提交对象都包含了一个指向暂存区中数据快照的指针。
 - 对象的上次提交对象成为此对象的父对象。
 - 第一次提交没有父对象，合并分支的提交有多个父对象。

---

### 对象数据内容

文件为blob对象，目录为tree对象，提交操作为commit对象。
commit对象中包含了提交一个根目录的tree对象信息及其他提交信息；
tree对象中包含了当前目录下所有文件(blob对象)和目录(tree对象)的索引信息；
blob对象中包含了文件的数据内容。
结构如下图:
![](http://git-scm.com/figures/18333fig0301-tn.png)

---

###  git暂存提交

**暂存**操作将当前版本的文件快照保存到git仓库中，就是将文件的对象信息写入 `.git/index` 中，使用 `git ls-tree -l HEAD` 命令查看index中的信息如下

```
$ git ls-tree -l HEAD
$ git ls-tree  -l -r -t HEAD
100644 blob c7fefb57e6ca65ab349fc4d8b251a4245153f569      13	.gitignore
100644 blob 488dc857aef421c07904d454dac19b3c530d3e9e      65	README.md
040000 tree efe5ac15048a99a819bf68045add9ab267370e4b       -	css
100644 blob e75b0e71f260af9c3e4d98623f250763018eabc7      16	css/db
040000 tree f3282fbfb154bd059bf14fdd5c513a8b675f0e77       -	js
100644 blob e69de29bb2d1d6434b8b29ae775ad8c2e48c5391       0	js/a.js
040000 tree 8dc877a998d8c61f900e8b4ee9b501fa0a039358       -	logs
100644 blob e69de29bb2d1d6434b8b29ae775ad8c2e48c5391       0	logs/1
100755 blob e69de29bb2d1d6434b8b29ae775ad8c2e48c5391       0	shw
100644 blob d15595dbb62b21926b5c10b93ee3afb9f56e24fa      19	shw.md
```

> `-l` 参数使结果显示文件的大小
> `-r` 参数显示目录下的文件内容
> `-t` 参数在递归过程中显示目录
> 第一列是文件的属性，第二列指对象类型，第三列为对象ID(40位的SHA-1值)，第四列为文件大小，第五列为文件名

**提交**操作会先将目录下的文件校验和保存为tree对象，然后创建commit对象，在版本库中存储了对象信息之后，将HEAD指向commit对象。
commit对象包含了根目录的树对象ID，父对象ID，用户信息，提交日志。查询的方法如下：

```
$ git log -1
commit 3f1f5882de6bd9b0ad6b7ac1f9ab0606a54b702f
Author: shanghw <hwshang@yeah.net>
Date:   Thu Nov 6 12:17:01 2014 +0800

    chmod 777 shw
    
$ git cat-file -p "3f1f5882de6bd9b0ad6b7ac1f9ab0606a54b702f"
tree 275d40d4dd92edd0f049b70d10ae3e07edac2903
parent 0f6ddb09481c65a107ec9a3e111b2a833e976642
author shanghw <hwshang@yeah.net> 1415247421 +0800
committer shanghw <hwshang@yeah.net> 1415247421 +0800

chmod 777 shw

$ git cat-file -p "275d40d4dd92edd0f049b70d10ae3e07edac2903"
100644 blob c7fefb57e6ca65ab349fc4d8b251a4245153f569	.gitignore
100644 blob 488dc857aef421c07904d454dac19b3c530d3e9e	README.md
040000 tree efe5ac15048a99a819bf68045add9ab267370e4b	css
040000 tree f3282fbfb154bd059bf14fdd5c513a8b675f0e77	js
040000 tree 8dc877a998d8c61f900e8b4ee9b501fa0a039358	logs
100755 blob e69de29bb2d1d6434b8b29ae775ad8c2e48c5391	shw
100644 blob d15595dbb62b21926b5c10b93ee3afb9f56e24fa	shw.md
```

> 通过 `git log -1` 查看最后一次提交信息，其中包含了上次的commit对象ID;
> 使用 `git cat-file -p` 查看commit对象内容，可以查询到根目录的tree对象ID；
> 再查看根目录tree对象的内容即为版本库根目录下的目录和文件信息。

