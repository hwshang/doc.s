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

***如果合并发生冲突***

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

---

### 分支操作原理

```
$ git branch issue1

$ cat .git/refs/heads/issue1 
73634bc0a03bd468325f406f9660323000d96ce3

$ cat .git/logs/refs/heads/issue1
0000000000000000000000000000000000000000 73634bc0a03bd468325f406f9660323000d96ce3 shanghw <hwshang@yeah.net> 1415267709 +0800	branch: Created from master
```

创建分支:

- 在`.git/refs/heads/issue1` 下创建一个分支命名的文件，内容为分支的commit对象ID。
- `.git/logs/refs/heads/issue1` 文件记录了分支的log信息，第一列是父对象的ID，因为此分支刚创建，所以第一个commit对象无父对象。

```
$ git checkout issue1
Switched to branch 'issue1'

$ cat .git/HEAD 
ref: refs/heads/issue1

$ tail -1 .git/logs/HEAD 
73634bc0a03bd468325f406f9660323000d96ce3 73634bc0a03bd468325f406f9660323000d96ce3 shanghw <hwshang@yeah.net> 1415268063 +0800	checkout: moving from master to issue1
```

切换到issue1分支:

 - 会修改HEAD指向issue1的head文件，
 - 另外在log总文件 `.git/logs/HEAD` 中追加了一行切换分支的日志记录。
 - 同时也会建立暂存区数据。

```
$ cat .git/refs/heads/master 
33fb0b0f252aa53863096f4576b06dcaef5f173f

$ tail -1 .git/logs/HEAD 
73634bc0a03bd468325f406f9660323000d96ce3 33fb0b0f252aa53863096f4576b06dcaef5f173f shanghw <hwshang@yeah.net> 1415268502 +0800	merge issue1: Fast-forward

$ tail -1 .git/logs/refs/heads/master 
73634bc0a03bd468325f406f9660323000d96ce3 33fb0b0f252aa53863096f4576b06dcaef5f173f shanghw <hwshang@yeah.net> 1415268502 +0800	merge issue1: Fast-forward

$ cat .git/ORIG_HEAD
73634bc0a03bd468325f406f9660323000d96ce3
```

在issue1分支跟新并提交数据之后，切换回master进行合并操作:

 -  创建一个合并commit对象。
 -  在总log文件中追加一行合并的记录，父对象为合并前master的对象ID，所以得知这里的合并git自动选择了master分支为最佳合并基础。
 -  在master分支的log中也追加了相同的记录。
 - `.git/ORIG_HEAD` 文件中记录了操作之前的对象ID，用于做逆转操作。
 - 暂存区也进行了更新。

 
分支的删除则会删除`.git/refs/heads/issue1`  `.git/logs/refs/heads/issue1` 两个文件。
 
