## git 分支

### 创建分支

git创建分支就是在当前commit对象上添加一个分支指针。不同分支对应不同分支指针，而仅有的一个HEAD(对应.git/HEAD文件)指针则指向当前分支上。

**创建** `testing` 分支：

```
$ git branch testing
```

![](http://git-scm.com/figures/18333fig0305-tn.png)

**切换**到 `testing` 分支：


```
$ git checkout testing
```

![](http://git-scm.com/figures/18333fig0306-tn.png)

> 可使用`git checkout -b testing` 进行testing分支的创建于切换，效果等同于以上两条操作

在`master` `testing` 两个分支分别进行一次更新提交之后如下图：

![](http://git-scm.com/figures/18333fig0309-tn.png)


---

### 合并分支

在master分支上将testing分支进行合并：

```
$ git merge testing
Merge made by the 'recursive' strategy.
 testing | 0
 1 file changed, 0 insertions(+), 0 deletions(-)
 create mode 100644 testing
```

因为master和testing分支分提交了不同的数据，执行合并操作，会自动创建一个commit对象，

```
$ git log --graph --oneline
*   cb137 Merge branch 'testing'
|\  
| * c2b9e add testing file
* | 87ab2 add master file
|/  
* f30ab add Readme.md
```

> `87ab2` 为master分支的提交，`c2b9e` 为testing的提交，执行合并操作会自动提交`cb137` ， 所以它的父对象有两个`87ab2` 和 `c2b9e` 。

***如果合并发生冲突 ***

合并分支须是不同分支修改了不同的数据，如果合并的分支修改了相同的数据，则需要手动进行选择

```
$ git merge testing
Auto-merging README.md
CONFLICT (content): Merge conflict in README.md
Automatic merge failed; fix conflicts and then commit the result.
$ cat README.md 
<<<<<<< HEAD
master-add
=======
testing-add
>>>>>>> testing
```

> 其中 `master-add` 为master（当前分支由HEAD指针标注）分支添加到第一行的数据，`testing-add`为testing分支添加到第一行的数据，合并发生冲突。

这时进行手动编辑发生冲突的文件，然后进行提交继续合并操作

```
$ git log --graph --oneline
*   38961 update Readme.md
|\  
| * 7098c update for testing
* | 0a929 update for master
* | cb137 Merge branch 'testing'
|\ \  
| |/  
| * c2b9e add testing file
* | 87ab2 add master file
|/  
* f30ab add Readme.md
```

---

### 分支删除

合并之后，`testing` 分支不再使用则进行删除

```
$ git branch -d testing
Deleted branch testing (was 7098c40).
```

---

### 分支管理

```
$ git branch
* master
  shw
  test
```

查看所有分支，`*`  标注当前所在分支

```
$ git branch -vv
* master 73634bc [origin/master] Merge branch 'test'
  shw    3f1f588 [origin/shw: ahead 1] chmod 777 shw
  test   0be3c53 [origin/test: ahead 1] update test.md for test branch
```

查看所有分支最后一次提交信息。也可使用 `-v` 参数

```
$ git branch --merged
* master
$ git branch --no-merged
  shw
  test
```

查看合并到当前分支与未合并的分支

---

### 远程分支

表示本地/远程分支 `[远程名] [本地分支]:[远程分支]` 的选项，`[]` 表示可以为空

```
$ git push origin master
```

将本地的master分支推送到远程仓库上。

```
$ git fetch origin
```

将远程分支拉取到本地

```
$ git push origin :test
```

此条推送命令中没有指定本地分支，则代表把 **空** 推送到远程之后会 **删除** origin的test分支。
