## git 基础操作


#### 克隆
`git clone https://github.com/hwshang/doc.s` 
`git clone https://github.com/hwshang/doc.s mydoc`
> 克隆操作会在把整个版本库拿到当前目录下，目录名为 `doc.s` ，也可自己指定目录名为 `mydoc`。在doc.s目录下会包含整个版本库的所有版本，然后取最新版本到工作区。

---

#### 检查状态

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

---

#### 跟踪(添加)/暂存已修改

**跟踪**

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


**暂存已修改**
* 对与已跟踪的文件，更新之后还未放入暂存区，执行`git add`来暂存。
* 如果已暂存的文件再次更新，那么`git status`会显示此文件两个不同版本的状态，一个是已暂存，一个是未暂存。

---

#### 提交

工作目录下文件的状态：
- **已跟踪**  只文件已经出现在以前的版本中，不论是否更新过。
- **未跟踪**  不曾出现在以前的版本中，也不存在于暂存区。

