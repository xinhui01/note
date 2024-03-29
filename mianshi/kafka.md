## Kafka 架构中的一般概念：
- Producer： 生产者，也就是发送消息的一方。生产者负责创建消息，然后将其发送到 Kafka。
- Consumer： 消费者，也就是接受消息的一方。消费者连接到 Kafka 上并接收消息，进而进行相应的业务逻辑处理。
- Consumer Group： 一个消费者组可以包含一个或多个消费者。使用多分区+多消费者方式可以极大提高数据下游的处理速度。 同一消费组中的消费者不会重复消费消息，同样的，不同消费组中的消费者消息消息时互不影响。Kafka 就是通过消费组的方式来实现消息 P2P 模式和广播模式。
- Broker： 服务代理节点。Broker 是 Kafka 的服务节点，即 Kafka 的服务器。
- Partition： Topic 是一个逻辑的概念，它可以细分为多个分区，每个分区只属于单个主题。 同一个主题下不同分区包含的消息是不同的，分区在存储层面可以看作一个可追加的日志（Log）文件，消息在被追加到分区日志文件的时候都会分配一个特定的偏移量（Offset）。
- Offset： Offset 是消息在分区中的唯一标识，Kafka 通过它来保证消息在分区内的顺序性，不过 Offset 并不跨越分区，也就是说，Kafka 保证的是分区有序性而不是主题有序性。
- Replication： 副本，是 Kafka 保证数据高可用的方式，Kafka 同一 Partition 的数据可以在多 Broker 上存在多个副本，通常只有主副本对外提供读写服务，当主副本所在 Broker 崩溃或发生网络一场，Kafka 会在 Controller 的管理下会重新选择新的 Leader 副本对外提供读写服务。
- Record： 实际写入 Kafka 中并可以被读取的消息记录。每个 Record 包含了 key、value 和 timestamp。
## 问题
- 简单讲下 Kafka 的架构？
  -  Producer、Consumer、Consumer Group、Topic、Partition
- Kafka 是推模式还是拉模式，推拉的区别是什么？
  - Kafka 是推模式还是拉模式，推拉的区别是什么？ Kafka Producer 向 Broker 发送消息使用 Push 模式，Consumer 消费采用的 Pull 模式。拉取模式，让 consumer 自己管理 offset，可以提供读取性能
- Kafka 如何广播消息？
 -- Consumer Group
- Kafka 的消息是否是有序的？
 - 在分区内有序,在topic内无序
- Kafka 是否支持读写分离？
  - 不支持
- Kafka 如何保证数据高可用？
  -  通过副本来保证数据的高可用，Producer Ack、重试、自动 Leader 选举,Consumer 自平衡
  - kafka会保留数据
- Kafka 中 Zookeeper 的作用？
- 是否支持事务？
  - 支持
- 分区数是否可以减少？
  - 不可以,因为会影响数据

## kafka 和 RabbitMQ
- RabbitMQ：用于实时的，对可靠性要求较高的消息传递上。
- kafka：用于处于活跃的流式数据，大数据量的数据处理上。
