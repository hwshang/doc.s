## git 操作第二层

### status

***精简状态信息***

```
$ echo "H" >> hello
$ git status -s
 M hello
$ git add .
$ git status -s
M  hello
```
其中hello文件已存在版本库中， 在执行`git add .` 的前后，查看精简状态信息(`git status -s`)，输出中的`M`(`git status` 中可看到指的是 `modified` ) 是有区别的:

- 第一列的M指 版本库中的文件处于中间状态(与暂存区中的文件相比差异)
- 第二列的M指 工作区中的文件处于中间状态(与暂存区中的文件相比差异)

再次修改文件
```
$ echo  "E" >> hello
$ git status -s
MM hello
```
代表此文件在 工作区 暂存区 版本库 中均不同。

```
$ git status -s
MM hello
$ touch world
$ git status -s
M  hello
?? world
$ git add .
$ git status -s
M  hello
A  world
```
不同的状态+不用的状态代表了不同区域文件的不同状态。


***详细状态信息***

```
$ git status 
# On branch master
# Your branch is ahead of 'origin/master' by 1 commit.
#   (use "git push" to publish your local commits)
#
# Changes to be committed:
#   (use "git reset HEAD <file>..." to unstage)
#
#	modified:   hello
#
```

git给出了详细的操作方案，可继续执行，也可回退。

---

### 日志

`git log` 可以查看所有的更新信息。

> `git log -p -2` 为常用方式，-p 显示比较信息，-2 显示最近2次更新

***--word-diff***

```
$ git log -p -1 --word-diff
 . . . 

一 二 {+三+}

one two {+three+}

[-1,2-]{+1,2,3+}
```

```
$ git log -p -1
 . . . 
-一 二
+一 二 三
 
-one two 
+one two three
 
-1,2
+1,2,3
```

`git log -p -1 --word-diff` 其中 `--word-diff` 选项可以用来进行单词对比，新增加的单词使用 `{+ +}` 表示。上边的例子表示 `一 二` 后边添加了 `三` ；`one two` 后边添加了 `three` ;而连续写的 `1,2` 替换为了 `1,2,3` 。


***format***
`$ git log --pretty=format:"%cd %cn %H %s" `
 
 输出日志格式的自定义。

| 选项 |说明 |
| :---  | :--- |
| %H	| 提交对象（commit）的完整哈希字串 |
| %h	| 提交对象的简短哈希字串 |
| %T	| 树对象（tree）的完整哈希字串 |
| %t	| 树对象的简短哈希字串 |
| %P	| 父对象（parent）的完整哈希字串 |
| %p	| 父对象的简短哈希字串 |
| %an	| 作者（author）的名字 |
| %ae	| 作者的电子邮件地址 |
| %ad	| 作者修订日期（可以用 -date= 选项定制格式）|
| %ar	| 作者修订日期，按多久以前的方式显示 |
| %cn	| 提交者(committer)的名字 |
| %ce	| 提交者的电子邮件地址 |
| %cd	| 提交日期 |
| %cr	| 提交日期，按多久以前的方式显示 |
| %s	| 提交说明 |


 ***常用选项***


|选项	| 说明 |
| :---  | :--- |
|-p	| 按补丁格式显示每个更新之间的差异。|
|--word-diff	| 按 word diff 格式显示差异。|
|--stat	| 显示每次更新的文件修改统计信息。|
|--shortstat	| 只显示 --stat 中最后的行数修改添加移除统计。|
|--name-only	| 仅在提交信息后显示已修改的文件清单。|
|--name-status	| 显示新增、修改、删除的文件清单。|
|--abbrev-commit	| 仅显示 SHA-1 的前几个字符，而非所有的 40 个字符。|
|--relative-date	| 使用较短的相对时间显示（比如，“2 weeks ago”）。|
|--graph	| 显示 ASCII 图形表示的分支合并历史。|
|--pretty	| 使用其他格式显示历史提交信息。可用的选项包括 oneline，short，full，fuller 和 format（后跟指定格式）。|
|--oneline	| --pretty=oneline --abbrev-commit 的简化用法。|
|-(n)	| 仅显示最近的 n 条提交 |
|--since, --after	| 仅显示指定时间之后的提交。 |
|--until, --before	| 仅显示指定时间之前的提交。 |
|--author	| 仅显示指定作者相关的提交。 |
|--committer	| 仅显示指定提交者相关的提交。 |

