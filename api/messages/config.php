<?php

/**
 * Configuration file for 'yii message/extract' command.
 *
 * This file is automatically generated by 'yii message/config' command.
 * It contains parameters for source code messages extraction.
 * You may modify this file to suit your needs.
 *
 * You can use 'yii message/config-template' command to create
 * template configuration file with detaild description for each parameter.
 */
return [
    'color' => null,
    'interactive' => true,
    'sourcePath' => '@root',
    'messagePath' => '@app/messages',
    'languages' => ['ru-RU'],
    'translator' => 'Yii::t',
    'sort' => true,
    'overwrite' => true,
    'removeUnused' => true,
    'markUnused' => false,
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/app/messages',
        '/vendor',
    ],
    'only' => [
        '*.php',
    ],
    'format' => 'php',
    //'db' => 'db',
    //'sourceMessageTable' => '{{%source_message}}',
    //'messageTable' => '{{%message}}',
    'catalog' => 'messages',
    'ignoreCategories' => [],
];
