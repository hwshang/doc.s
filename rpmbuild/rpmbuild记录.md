## rpmbuild 记录

使用rpmbuild打包，最好参考官方的spec文件，还不能漏掉其中的环境信息配置，因为完全自己写spec为mysql/php-fpm打包遇到了问题。

### mysql

自己写的mysql5.5的spec文件，打包之后安装的时候会提示依赖perl的一些包，但是在spec文件中并没有指定要依赖perl相关的。
 http://bugs.mysql.com/bug.php?id=49723 中强调，需要在spec中添加以下配置:
 
```
%undefine __perl_provides
%undefine __perl_requires
```

官方的spec文件中指出 rpmbuild 自动检测 Perl 的依赖关系才造成了这个问题，此命令应该也可适用于其他场景。

### php-fpm

自己写php5.3的spec文件，在成功安装之后，要为php环境安装扩展模块出现了问题。官方的rpm会把扩展文件安装到 `/usr/lib64/php/modules/` 目录下，而编译之后的扩展模块目录在 `/usr/local/php-5.3.28/lib/php/extensions/no-debug-non-zts-20090626`  ，通过 `php-config --extension-dir` 查看。
查了configure 的帮助命令，没有找到指定扩展路径的选项，在官方的spec中看到了对应的配置 `EXTENSION_DIR=%{_libdir}/php/modules; export EXTENSION_DIR` ，用在configure之前，这样生成的Makefile中就不是默认的配置了。

### 环境

- 使用普通用户执行rpmbuild 
- 保持系统的干净。严格遵守：构建的rpm包依赖什么，就在系统中安装什么，因为环境中的文件对生成的rpm也有依赖影响。构建完成之后对其卸载，以免影响以后的构建。
