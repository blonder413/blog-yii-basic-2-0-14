<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=blog',
    'username' => 'user',
    'password' => 'password',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,  // Duration of schema cache.
    'schemaCache' => 'cache',   // Name of the cache component used to store schema information
    'on afterOpen' => function($event) {
        // $event->sender se refiere a la conexión DB
        $event->sender->createCommand("SET time_zone = '-5:00'")->execute();
    }
];