### zabbix_proxy的queue居高不下

**问题**

为zabbix部署了3个proxy，在列队页面中查看各代理的队列统计，只有其中一个proxy出现了大量的队列，对比了不同proxy的配置没有不同，网络也很稳定，打开debug也没查到有用的信息。

**解决方法**

https://www.zabbix.com/forum/showthread.php?t=43522&page=4 这里提到了解决方法，是服务器的时间差异导致的.
我没有开启ntpd服务，而是每天ntpdate一次。当我执行了一次ntpdate后，有队列的proxy服务器在一分钟左右就与其他服务器有了一秒的时差。把定时任务调整为使用ntpd之后，队列就逐渐消失。
