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



