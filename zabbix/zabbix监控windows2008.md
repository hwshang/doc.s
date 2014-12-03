### zabbix监控windows2008网卡的问题


**问题**

使用zabbix监控windows2008服务器，使用默认的发现功能去探测agent的网卡设备，会有很多无效的项目，有用的只有 `Net Device PV Driver *` , 那些大量没有意义的监控项严重消耗zabbix的性能，所以需要将其屏蔽掉。

**解决方法**

zabbix面板 `Administration` -> `General ` , 选择 `Regular expressions` , 修改其中的 `Network interfaces for discovery` 表达式为 `1	»	^(Net|eth|em)	[Result is TRUE]` 。 发现执行结果只匹配 `Net|eth|em` 开头的项目。
