## git 操作第一层

### 克隆
`git clone https://github.com/hwshang/doc.s` 
`git clone https://github.com/hwshang/doc.s mydoc`

克隆操作会在把整个版本库拿到当前目录下，目录名为 `doc.s` ，也可自己指定目录名为 `mydoc`。在doc.s目录下会包含整个版本库的所有版本，然后取最新版本到工作区。

---

### 检查状态

```
$ git status
# On branch master
nothing to commit (working directory clean)
```
提示工作区文件均已提交，另外也没有更新。当前所处的分支为master。
> 须在工作区执行，下面的命令同样需要在工作区目录执行。

```
$ touch db
$ git status
# On branch master
# Untracked files:
#   (use "git add <file>..." to include in what will be committed)
#
#	db
nothing added to commit but untracked files present (use "git add" to track)
```
以上输出表示db文件处于 `untracked(未跟踪)` 状态。

> 文件的状态：
- **已跟踪**  只文件已经出现在以前的版本中，不论是否更新过。
- **未跟踪**  不曾出现在以前的版本中，也不存在于暂存区。

---

### 跟踪(添加)/暂存已修改

***跟踪***

```
$ git add db
$ git status
# On branch master
# Changes to be committed:
#   (use "git reset HEAD <file>..." to unstage)
#
#	new file:   db
#
```

此时db文件放入暂存区(add file into staged area)，处于已暂存状态。

***暂存已修改***

* 对于已跟踪的文件，更新之后还未放入暂存区，执行`git add`来暂存。
* 如果已暂存的文件再次更新，那么`git status`会显示此文件两个不同版本的状态，一个是已暂存，一个是未暂存。

---

### 提交

```
$ git commit -m "add file db"
[master 5569a6f] add file db
 1 file changed, 0 insertions(+), 0 deletions(-)
 create mode 100644 db
```

`git commit` 通过 `-m` 参数来输入提交说明，如果执行 `git commit` 则会启动文本编辑器输入提交说明。
输出信息中的 `master` 为当前的分支， `5569a6f` 为本次提交的完整SHA-1校验和，以及提交说明。并显示了文件的变化情况。

***跳过暂存区提交***

```
$ git commit -a -m "update file db"
[master 646414e] update file db
 1 file changed, 3 insertions(+)
```

不使用暂存区（跟新文件之后不通过git add 去暂存已修改），直接提交添加 `-a` 参数即可，更为方便。

> 跳过操作针对已跟踪的文件，还未跟踪的文件则不能跳过。

---

### 删除

```
$ git status
 On branch master
nothing to commit, working directory clean

$ ls
123  css  db  js  logs  README.md

$ git rm 123
rm '123'

$ git status
 On branch master
 Changes to be committed:
   (use "git reset HEAD <file>..." to unstage)

	deleted:    123

$ ls
css  db  js  logs  README.md

$ git commit -m "rm 123"
[master 5adcd24] rm 123
 1 file changed, 0 insertions(+), 0 deletions(-)
 delete mode 100644 123
```

git中删除文件，需要同时删除已跟踪的文件列表中的信息，所以，只手动删除无效
> 对与新跟踪还未提交的文件，需要使用 `git reset HEAD <file>` 命令从暂存区中删除，并手动删除该文件。

---

### 移动/重命名

```
$ git mv db newdb

$ git status
 On branch master
 Changes to be committed:
   (use "git reset HEAD <file>..." to unstage)
	renamed:    db -> newdb
	
$ git commit -m "rename db to newdb"
[master 5e22151] rename db to newdb
 1 file changed, 0 insertions(+), 0 deletions(-)
 rename db => newdb (100%)
```

此操作对与git来说分三步： `mv db newdb` + `git rm db` + `git add newdb` 。所以对git元数据来说，移动/更名是删除+添加的操作集合。

---

### 忽略文件

```
$ cat .gitignore 
*.log
logs/
```
在版本库中屏蔽掉临时文件，使用 `.gitignore` 隐藏文件来匹配，提交此文件到版本库中对其他的客户端也有效（称为共享式），否则只对本地版本库有效。
> - `.gitignore`文件可放在工作区的任何目录下，它的作用范围是当前目录和子目录。
> -  忽略只对未跟踪文件有效，已加入版本库的文件无效

```
$ ls logs/
1  1.log

$ git status -s --ignored
!! css/css.log
!! js/a.log
!! logs/
```

使用`--ignored` 参数可以查看到被忽略的文件和目录。（通过上边的例子可以看到对与`logs/` 目录下的所有的文件，只显示了`logs/`）


***添加忽略中的指定文件***

```
$ git add -f logs/1
$ git status
 On branch master
 Your branch is ahead of 'origin/master' by 1 commit.
   (use "git push" to publish your local commits)

 Changes to be committed:
   (use "git reset HEAD <file>..." to unstage)

	new file:   logs/1
```

`logs/1` 文件已经匹配到了 `.gitignore` 文件中的 `logs/` ，这时候想要添加需要使用 `git add -f <file>` 来操作。

***本地独享式忽略***

 - 针对具体版本库：在.git目录下的 `.git/info/exclude` 文件中设置
 - 全局性的忽略：通过配置变量 `git config --global core.excludesfile <ifnorefile>` 指定忽略文件来配置，对本地所有版本
 

***`.gitignore`的格式规范 ***

- 所有空行或者以注释符号 ＃ 开头的行都会被 Git 忽略。
- 可以使用标准的 glob 模式匹配，即 * 代表任意多个字符，? 代表一个字符， [] 代表可选字符。
- 匹配模式最前边跟反斜杠（/）说明要忽略的文件在此目录下。
- 匹配模式最后跟反斜杠（/）说明要忽略的是目录。
- 要忽略指定模式以外的文件或目录，可以在模式前加上惊叹号（!）取反。

