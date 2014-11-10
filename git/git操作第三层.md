## git 操作第三层

### reset

重置暂存区工作区

第一种用法：

```
git reset  [ --soft | --mixed | --hard | --merge | --keep ] [-q]  [<commit>]
```

> 使用 `--soft` 参数的执行：替换HEAD指向到指定commit 。

> 使用 `--mixed` 参数的执行： 替换HEAD指向到指定commit +  替换暂存区，与指向的目录树一致  。 
>> `--mixed` 为默认参数，不加也会使用

> 使用 `--hard` 参数的执行过程如下，

>> - 替换HEAD指向到指定commit
>> - 替换暂存区，与指向的目录树一致
>> - 替换工作区，与暂存区一致

第二种用法：

```
git reset [-q] [<commit>]  [--] <paths>...
```

> 使用指定commit状态的文件替换暂存区的文件，其他不受影响。`--` 用于区分commit的ID和路径名称

用途：

> - 指定路径  -  取消之前的`git add ` 操作
> - `--soft`   - 对之前commit不满意可回退重新commit
> - `--mixed` -  不改变工作区文件状态的情况下回退版本将改变撤出暂存区
> - `--hard`  - 完全回到指定状态

额外：

> `git commit --amend` 用于对最新的提交进行更改，相当于执行了 `git reset --soft HEAD^` + `git commit -e -F .git/COMMIT_EDITMSG`

---

### checkout

第一种用法：

```
git checkout  [-q]  [<commit>]  [--] <paths>...
```

> 指定了commit，则从指定版本检出文件到工作区；忽略commit，则从暂存区检出指定文件。 与reset重置的区别是，检出不会改变版本库信息

第二种用法：

```
git checkout  [<branch> 
```

> 改变版本库中HEAD的指向。省略branch相当于检查工作区状态

第三种用法：

```
git checkout  [-m]   [ [-b | --orphan] <new_branch>]   [<start_point>]  
```

> 创建并且切换到新的分支上；新的分支从<start_point>指定的提交开始创建，忽略则从最后一次。


注意：

> `git checkout .` `git checkout  -- .` 会从暂存区覆盖工作区的文件，#没有提示#
