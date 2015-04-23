## Elasticsearch概念


与传统数据库的概念对比

Relational DB -> Databases -> Tables -> Rows -> Columns

Elasticsearch -> Indices   -> Types  -> Documents -> Fields

Elasticsearch支持的JSON数据类型

- 多值字段， 数组中所有值必须为同一类型。数组是做为多值字段被索引的，它们没有顺序。
- 空字段， Lucene没法存放null值，所以一个null值的字段被认为是空字段。
- 对象(object)， 内部对象(inner objects)经常用于嵌入一个实体或对象里的另一个地方。Elasticsearch 会动态的检测新对象的字段，并且映射它们为 object 类型，将每个字段加到 properties 字段下。

---

### 索引

索引(index)这个词在Elasticsearch中有着不同的含义:

- `索引（名词`  一个索引(index)就像是传统关系数据库中的数据库，它是相关文档存储的地方，index的复数是indices 或indexes。
- `索引（动词）` 「索引一个文档」表示把一个文档存储到索引（名词）里，以便它可以被检索或者查询。这很像SQL中的INSERT关键字，差别是，如果文档已经存在，新的文档将覆盖旧的文档。
- `倒排索引`  传统数据库为特定列增加一个索引，例如B-Tree索引来加速检索。Elasticsearch和Lucene使用一种叫做倒排索引(inverted index)的数据结构来达到相同目的。默认情况下，文档中的所有字段都会被索引（拥有一个倒排索引），只有这样他们才是可被搜索的。


### 数据类型

类型： `确切值` 及 `全文文本`

为了方便在全文文本字段中进行这些类型的查询，Elasticsearch首先对文本分析(analyzes)，然后使用结果建立一个倒排索引。

当你查询全文(full text)字段，查询将使用相同的分析器来分析查询字符串，以产生正确的词列表。

当你查询一个确切值(exact value)字段，查询将不分析查询字符串，但是你可以自己指定。

### 倒排索引

Elasticsearch使用 倒排索引(inverted index) 来做快速的全文搜索。

倒排索引由在文档中出现的唯一的单词列表，以及对于每个单词在文档中的位置组成。

当我们索引(index)一个文档，全文字段会被分析为单独的词来创建倒排索引。

创建倒排索引：首先切分每个文档的content字段为单独的单词（我们把它们叫做词(terms)或者表征(tokens)）把所有的唯一词放入列表并排序。

搜索的短语中单词和倒排索引进行匹配，相似度算法(similarity algorithm)匹配出相关性最高的文档（命中单词越多的文档相关性越高）。

### 分析

分析(analysis)机制用于进行全文文本(Full Text)的分词，以建立供搜索用的反向索引。

分析(analysis)是这样一个过程： 首先，表征化一个文本块为适用于倒排索引单独的词(term)，然后标准化这些词为标准形式，提高它们的“可搜索性”或“查全率”。

这个工作是分析器(analyzer)完成的。一个分析器(analyzer)只是一个包装用于将三个功能放到一个包里。

  - 首先字符串经过字符过滤器(character filter)，在表征化前处理字符串。字符过滤器能够去除HTML标记，或者转换"&"为"and"。(一个分析器可能包含零到多个字符过滤器。)
  - 下一步，分词器(tokenizer)被表征化（断词）为独立的词。一个简单的分词器(tokenizer)可以根据空格或逗号将单词分开。 (一个分析器 必须 包含一个分词器。)
  -  最后，每个词都通过所有表征过滤(token filters)。表征过滤器可能修改，添加或删除表征。

对于analyzed类型的字符串字段，使用analyzer参数来指定哪一种分析器将在搜索和索引的时候使用。默认的，Elasticsearch使用standard分析器，但是你可以通过指定一个内建的分析器来更改它，例如whitespace、simple或english。

测试映射
使用analyze API测试字符串字段的映射
```
GET /gb/_analyze?field=tweet
Black-cats 
```

---

### 映射

映射(mapping)机制用于进行字段类型确认，将每个字段匹配为一种确定的数据类型(string, number, booleans, date等)。

Elasticsearch支持以下简单字段类型：

|  类型 |   表示的数据类型 |
| --- | --- |
| String|string|
|Whole number|  byte, short, integer, long|
|Floating |point  float, double|
|Boolean  |boolean|
|Date |date|


映射的最高一层被称为 根对象，它可能包含下面几项：
 
-  一个 properties 节点，列出了文档中可能包含的每个字段的映射
- 多个元数据字段，每一个都以下划线开头，例如 _type, _id 和 _source
- 设置项，控制如何动态处理新的字段，例如 analyzer, dynamic_date_formats 和 dynamic_templates。
- 其他设置，可以同时应用在根对象和其他 object 类型的字段上，例如 enabled, dynamic 和 include_in_all

**属性**

属性的三个最重要的设置：

-  type： 字段的数据类型，例如 string 和 date
-  index： 字段是否应当被当成全文来搜索（analyzed），或被当成一个准确的值（not_analyzed），还是完全不可被搜索（no）
-  analyzer： 确定在索引和或搜索时全文字段使用的 分析器。
  
> analyzed  首先分析这个字符串，然后索引。换言之，以全文形式索引此字段。

> not_analyzed  索引这个字段，使之可以被搜索，但是索引内容和指定值一样。不分析此字段。

> no  不索引这个字段。这个字段不能为搜索到。

> string类型字段默认值是analyzed。如果我们想映射字段为确切值，我们需要设置它为not_analyzed：
其他简单类型——long、double、date等等——也接受index参数，但相应的值只能是no和not_analyzed，它们的值不能被分析。

**_source 字段**

默认情况下，Elasticsearch 用 JSON 字符串来表示文档主体保存在 _source 字段中。_source 字段也会在写入硬盘前压缩。

-  搜索结果中能得到完整的文档 —— 不需要额外去别的数据源中查询文档
-  如果缺少 _source 字段，部分 更新 请求不会起作用
-  当你的映射有变化，而且你需要重新索引数据时，你可以直接在 Elasticsearch 中操作而不需要重新从别的数据源中取回数据。
-  你可以从 _source 中通过 get 或 search 请求取回部分字段，而不是整个文档。
-  这样更容易排查错误，因为你可以准确的看到每个文档中包含的内容，而不是只能从一堆 ID 中猜测他们的内容。

**_all 字段**

*query_string 在没有指定字段时默认用 _all 字段查询* 。

_all 字段仅仅是一个经过分析的 string 字段。它使用默认的分析器来分析它的值，而不管这值本来所在的字段指定的分析器。而且像所有 string 类型字段一样，你可以配置 _all 字段使用的分析器。

相对于完全禁用 _all 字段，可以先默认禁用 include_in_all 选项，而选定字段上启用 include_in_all。

**动态映射**

通过【动态映射】来确定字段的数据类型且自动将该字段加到类型映射中。使用 dynamic 设置来控制这些行为，它接受下面几个选项：

- true：自动添加字段（默认）
- false：忽略字段
- strict：当遇到未知字段时抛出异常

dynamic 设置可以用在根对象或任何 object 对象上。你可以将 dynamic 默认设置为 strict，而在特定内部对象上启用它。


**dynamic_templates**

使用 dynamic_templates，你可以完全控制新字段的映射，你设置可以通过字段名或数据类型应用一个完全不同的映射。

-  match_mapping_type 允许你限制模板只能使用在特定的类型上，就像由标准动态映射规则检测的一样，（例如 string 和 long）
-  match 参数只匹配字段名，
-  path_match 参数则匹配字段在一个对象中的完整路径，所以 address.*.name 规则将匹配一个这样的字段
-  unmatch 和 path_unmatch 规则将用于排除未被匹配的字段。

用 _default_ 映射来指定公用设置会更加方便，而不是每次创建新的类型时重复操作。

---

### 文档

文档元数据

- _index  -- 文档存储的地方
- _type -- 文档代表的对象的类
- _id   -- 文档的唯一标识
- _score  -- 相关性(relevance)评分，匹配对越高，得分越高，应用于about搜索

对文档的修改，其实是删除旧文档，索引新文档，并更新_version。


**查询文档**

DSL查询(Query DSL) 。DSL(Domain Specific Language领域特定语言)

`GET /megacorp/employee/_search?q=last_name:Smith`

DSL指定JSON做为请求体,不再使用查询字符串(query string)做为参数.

聚合(aggregations)，它允许你在数据基础上生成复杂的统计。它很像SQL中的GROUP BY但是功能更强大。

**更新文档**

文档在Elasticsearch中是不可变的。如果需要更新已存在的文档，使用 index API 重建索引(reindex) 或者替换掉它，Elasticsearch把文档 _version增加了。

在内部，Elasticsearch已经标记旧文档为删除并添加了一个完整的新文档。旧版本文档不会立即消失，但你也不能去访问它。Elasticsearch会在你继续索引更多数据时清理被删除的文档。

**创建新文档**

保证文档不存在。

如果请求成功的创建了一个新文档，Elasticsearch将返回正常的元数据且响应状态码是201 Created。

如果包含相同的_index、_type和_id的文档已经存在，Elasticsearch将返回409 Conflict响应状态码

```
PUT /website/blog/123?op_type=create
PUT /website/blog/123/_create
```

**处理冲突**

Elasticsearch是分布式的，这些复制请求都是平行发送的，并无序(out of sequence)的到达目的地。

数据库的并发控制方式：

 - 悲观并发控制（Pessimistic concurrency control） 这在关系型数据库中被广泛的使用，假设冲突的更改经常发生，为了解决冲突我们把访问区块化。典型的例子是在读一行数据前锁定这行，然后确保只有加锁的那个线程可以修改这行数据。

 - 乐观并发控制（Optimistic concurrency control） 被Elasticsearch使用，假设冲突不经常发生，也不区块化访问，然而，如果在读写过程中数据发生了变化，更新操作将失败。这时候由程序决定在失败后如何解决冲突。实际情况中，可以重新尝试更新，刷新数据（重新读取）或者直接反馈给用户


Elsaticsearch的并发控制：

1，乐观并发控制。

 利用_version的这一优点确保数据不会因为修改冲突而丢失。我们可以指定文档的verion来做想要的更改，所以在修改之前需要知道对象的version值是什么。
 
 判断标准：修改指定的版本号文档，如果那个版本号不是现在的，我们的请求就失败了。
 
 示例: `PUT /website/blog/1?version=1`

2， 使用外部版本控制系统。 

 场景：使用一些其他的数据库做为主数据库，然后使用Elasticsearch搜索数据。

 在Elasticsearch的查询字符串后面添加version_type=external来使用这些版本号。版本号必须是整数，大于零小于9.2e+18——Java中的正的long。 

 判断标准：检查文档的_version值要小于修改请求中指定的版本，否则请求失败。

 示例： 当前文档的_version: 5 `PUT /website/blog/2?version=10&version_type=external`
 
**multi-get**

检索多个文档，相对于一个一个的检索更快

```
GET /website/blog/_mget
{
   "ids" : [ "2", "1" ]
}
```

**bulk**

批量操作。bulk请求不是原子操作——它们不能实现事务。每个请求操作时分开的，所以每个请求的成功与否不干扰其它操作。

格式：

```
{ action: { metadata }}\n
{ request body        }\n
{ action: { metadata }}\n
{ request body        }\n
```

> 每行必须以"\n"符号结尾，包括最后一行。这些都是作为每行有效的分离而做的标记。
> 每一行的数据不能包含未被转义的换行符，它们会干扰分析——这意味着JSON不能被美化打印。


行为  解释

- create  当文档不存在时创建之。
- index 创建新文档或替换已有文档。
- update  局部更新文档。不需要请求体(request body)
- delete  删除一个文档。

示例：

```
POST /_bulk
{ "delete": { "_index": "website", "_type": "blog", "_id": "123" }} <1>
{ "create": { "_index": "website", "_type": "blog", "_id": "123" }}
{ "title":    "My first blog post" }
{ "index":  { "_index": "website", "_type": "blog" }}
{ "title":    "My second blog post" }
{ "update": { "_index": "website", "_type": "blog", "_id": "123", "_retry_on_conflict" : 3} }
{ "doc" : {"title" : "My updated blog post"} }
```

---

###搜索

**搜索方法**

在所有索引的user和tweet中搜索， `/_all/user,tweet/_search`

空搜索 GET /_search

**响应结果**

- hits 文档实体
- took告诉我们整个搜索请求花费的毫秒数。
- shards 节点告诉我们参与查询的分片数（total字段），有多少是成功的（successful字段），有多少的是失败的（failed字段）
- time_out值告诉我们查询超时与否。如果响应速度比完整的结果更重要，你可以定义timeout参数为10或者10ms（10毫秒），或者1s（1秒）`GET /_search?timeout=10ms`

> 需要注意的是timeout不会停止执行查询，它仅仅告诉你目前顺利返回结果的节点然后关闭连接。在后台，其他分片可能依旧执行查询，尽管结果已经被发送。 使用超时是因为SLA,而不是因为你想中断执行长时间运行的查询。

**分页**

`GET /_search?size=5&from=10`

size: 显示结果数，默认10
from: 跳过开始的结果数，默认0

集群中的深度分页 假设在一个有5个主分片的索引中搜索。当我们请求结果的第一页（结果1到10）时，每个分片产生自己最顶端10个结果然后返回它们给请求节点(requesting node)，它再排序这所有的50个结果以选出顶端的10个结果。

**简易搜索**

当你索引一个文档，Elasticsearch把所有字符串字段值连接起来放在一个大字符串中，它被索引为一个特殊的字段_all。

---

### 查询过滤

 结构化查询（Query DSL）和结构化过滤（Filter DSL）

差异：一条查询语句会计算每个文档与查询语句的相关性，会给出一个相关性评分 _score，并且 按照相关性对匹配到的文档进行排序。 这种评分方式非常适用于一个没有完全配置结果的全文本搜索。使用查询语句做全文本搜索或其他需要进行相关性评分的时候，剩下的全部用过滤语句。


**过滤**

- term 主要用于精确匹配哪些值，比如数字，日期，布尔值或 not_analyzed的字符串。
- terms 跟 term 有点类似，但 terms 允许指定多个匹配条件。 如果某个字段指定了多个值，那么文档需要一起去做匹配。
- range 过滤允许我们按照指定范围查找一批数据。
- exists 和 missing 过滤可以用于查找文档中是否包含指定字段或没有某个字段，类似于SQL语句中的IS_NULL条件。区分出某个字段是否存在的时候使用。
- bool 过滤可以用来合并多个过滤条件查询结果的布尔逻辑，它包含一下操作符：
  -  must :: 多个查询条件的完全匹配,相当于 and。
  -  must_not :: 多个查询条件的相反匹配，相当于 not。
  -  should :: 至少有一个查询条件匹配, 相当于 or。

**查询**

- match_all 可以查询到所有文档，是没有查询条件下的默认语句。
- match 查询是一个标准查询，不管你需要全文本查询还是精确查询基本上都要用到它。
  如果使用 match 查询一个全文本字段，它会在真正查询之前用分析器先分析match下查询字符。
  如果用 match 指定了一个确切值，在遇到数字，日期，布尔值或者not_analyzed 的字符串时，它将为你搜索你给定的值
  提示： 做精确匹配搜索时，你最好用过滤语句，因为过滤语句可以缓存数据。
- multi_match查询允许你做match查询的基础上同时搜索多个字段
- bool 查询与 bool 过滤相似，用于合并多个查询子句。不同的是，bool 过滤可以直接给出是否匹配成功， 而bool 查询要计算每一个查询子句的 _score 。
  提示： 如果bool 查询下没有must子句，那至少应该有一个should子句。但是 如果有must子句，那么没有should子句也可以进行查询。

查询语句和过滤语句可以放在各自的上下文中。 
查询语句可以包含过滤子句，反之亦然。
validate API 可以验证一条查询语句是否合法。


---

### 路由与分片

文档存储的分片由路由决定。路由的算法： `shard = hash(routing) % number_of_primary_shards` 。

`routing`值是一个任意字符串，它默认是_id，但也可以自定义。哈希路由值之后通过分片总数取得余数，余数范围 `0-分片总数` ，分片的编号也是这样的，因此 *分片数量在创建索引之后不能修改* 。

> `routing`值自定义，例如使用UserID值，这样在通过UserID进行检索的时候可直接路由到目标分片，但是需要索引文档和检索都指定`routing` 参数。

---

**分片分布**

节点上配置主分片数(默认5)和每个主分片的复制分片数(默认1)，分片总数为10。这些分片分配的所有节点上，不论是主分片还是复制分片，同一分片不存在同一个节点上，保证了相同的数据不会再同一个节点上有冗余。所以，复制分片数大于等于节点数，那会多出分片则不能分配。

单节点机器使用默认的配置，那么只能分配一份主分片，另外一份复制分片不能分配。所以单节点配置复制分片没有意义。


**写操作请求过程**

- 客户端发送请求给请求节点。
- 请求节点根据路由把请求发送到对应的主分片上。 
- 主分片上写操作执行成功，转发请求到所有的复制分片上，得到了所有复制分片的成功响应，在成功响应客户端。

当客户端接受到成功响应，说明已经在所有分片上成功写入。

请求可用参数：`replication` `consistency` `timeout` `routing`

`replication` 默认值为 `sync` ，指 主分片得到复制分片的成功响应后才返回。

也可配置为 `async` ，请求在主分片上被执行后就会返回给客户端。它依旧会转发请求给复制节点，但你将不知道复制节点成功与否。这可能导致主分片满负荷处理请求，导致复制分片压力过大而出错。

`consistency` 默认主分片在尝试写入时需要规定数量(quorum)或过半的分片（可以是主节点或复制节点）可用。这是防止数据被写入到错的网络分区。

consistency允许的值为one（只有一个主分片），all（所有主分片和复制分片）或者默认的quorum或过半分片。

`timeout` 当分片副本不足时，Elasticsearch会等待更多的分片出现。默认等待一分钟。如果需要，你可以设置timeout参数让它终止的更早：100表示100毫秒，30s表示30秒。


**检索请求过程**

- 客户端发送请求给请求节点。
- 节点根据_id确认文档所在分片，将请求轮询转发到目标分片所在的一个节点上。
- 最终的节点响应请求节点，请求节点返回给客户端。

