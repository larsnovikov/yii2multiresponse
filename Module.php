<?php

namespace vendor\larsnovikov\yii2multiresponse;

use vendor\larsnovikov\yii2multiresponse\storages\StorageInterface;

/**
 * yii2multiresponse module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'vendor\larsnovikov\yii2multiresponse\controllers';

    /**
     * @var StorageInterface
     */
    public $storage = 'vendor\larsnovikov\yii2multiresponse\storages\BaseStorage';

    /**
     * @var array
     */
    public $socketUrls = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
