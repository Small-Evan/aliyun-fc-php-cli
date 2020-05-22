<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use AliyunFC\Client;

include_once 'config.php';
$fcClient = new Client($config);

function listServices($client)
{
    $listServices = $client->listServices();
    foreach ($listServices['data']['services'] as $key => $service) {
        echo 'ServiceId: ' . $service['serviceId'] . PHP_EOL;
        echo 'ServiceName: ' . $service['serviceName'] . PHP_EOL;
        echo 'ServiceRole: ' . $service['role'] . PHP_EOL;
        echo 'ServiceVpcConfig' . json_encode($service['logConfig']) . PHP_EOL;
        echo 'ServiceVpcConfig' . json_encode($service['vpcConfig']) . PHP_EOL . PHP_EOL;
    }
}

function listFunctions($client, $serviceName)
{
    $listFunctions = $client->listFunctions($serviceName);

    foreach ($listFunctions['data']['functions'] as $key => $function) {
        echo 'FunctionId: ' . $function['functionId'] . PHP_EOL;
        echo 'FunctionName: ' . $function['functionName'] . PHP_EOL;
        echo 'FunctionRuntime: ' . $function['runtime'] . PHP_EOL;
        echo 'FunctionHandler: ' . $function['handler'] . PHP_EOL;
        echo 'FunctionMemorySize: ' . $function['memorySize'] . PHP_EOL;
        echo 'FunctionInitializer: ' . $function['initializer'] . PHP_EOL;
        echo PHP_EOL;
    }
}

function SetProvision($client, $serviceName, $alias, $target)
{
    $listFunctions = $client->listFunctions($serviceName)['data']['functions'];
    foreach ($listFunctions as $key => $function) {
        $fs = $client->putProvisionConfig(
            $serviceName,
            $alias,
            $function['functionName'],
            ["target" => $target, "current" => $target]
        )['data'];
        echo 'ResourceName: ' . $fs['resource'] . PHP_EOL;
        echo 'ResourceTarget: ' . $fs['target'] . PHP_EOL;
        echo PHP_EOL;
    }
}

if ($argc >= 2) {
    switch (strtolower($argv[1])) {
        case 'listservice':
            listServices($fcClient);
            break;

        case 'listservices':
            listServices($fcClient);
            break;

        case 'listfunction':
            if (empty($argv[2])) {
                echo "| ServiceName不能为空" . PHP_EOL . "$ php index.php Listfunction [ServiceName]" . PHP_EOL;
                break;
            }
            $ServiceName = strtolower($argv[2]);
            listFunctions($fcClient, $ServiceName);
            break;

        case 'listfunctions':
            if (empty($argv[2])) {
                echo "| ServiceName不能为空" . PHP_EOL . "$ php index.php Listfunction [ServiceName]" . PHP_EOL;
                break;
            }
            $ServiceName = strtolower($argv[2]);
            listFunctions($fcClient, $ServiceName);
            break;

        case 'setprovision':
            if (empty($argv[2])) {
                echo "| ServiceName 服务名称不能为空" . PHP_EOL;
                echo " php index.php SetProvision [ServiceName] [Alias] [Target]" . PHP_EOL;
                break;
            }
            if (empty($argv[3])) {
                echo "| Alias 别名不能为空" . PHP_EOL;
                echo " php index.php SetProvision [ServiceName] [Alias] [Target]" . PHP_EOL;
                break;
            }
            if (empty($argv[4])) {
                echo "| Target 目标预留实例数/当前预留实例数" . PHP_EOL;
                echo " php index.php SetProvision [ServiceName] [Alias] [Target]" . PHP_EOL;
                break;
            }
            SetProvision($fcClient, $argv[2], $argv[3], $argv[4]);
            break;
    }

} else {
    echo "列出服务: php index.php listServices " . PHP_EOL;
    echo "列出函数: php index.php Listfunctions [ServiceName]" . PHP_EOL;
    echo "配置预留资源: php index.php SetProvision [ServiceName] [Alias] [Target]" . PHP_EOL;
}
