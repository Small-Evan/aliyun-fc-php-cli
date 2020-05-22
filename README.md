# aliyun-fc-php-cli

#### 介绍
阿里云函数计算PHP版本的CLI工具


#### 使用说明
```
git clone https://gitee.com/wupz/aliyun-fc-php-cli.git
cd aliyun-fc-php-cli
composer install
```

config.php
```
$config = [
    "endpoint" => '公网 Endpoint',//阿里云函数计算首页右侧查看
    "accessKeyID" => 'accessKeyID',//RAM 用户的accessKeyID
    "accessKeySecret" => 'accessKeySecret',//RAM 用户的accessKeySecret
];
```

- 列出服务: php index.php listServices

- 列出函数: php index.php Listfunctions [ServiceName]

- 配置预留资源: php index.php SetProvision [ServiceName] [Alias] [Target]
