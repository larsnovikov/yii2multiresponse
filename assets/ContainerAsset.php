<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 16.05.18
 * Time: 21:43
 */

namespace vendor\larsnovikov\yii2multiresponse\assets;

use yii\web\AssetBundle;

/**
 * Class ContainerAsset
 * @package vendor\larsnovikov\yii2multiresponse\assets
 */
class ContainerAsset extends AssetBundle
{
    public $baseUrl = 'ContainerAsset';

    public $css = [];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];

    public $sourcePath = '@vendor/larsnovikov/yii2multiresponse/assets';

    public $js = [
        'js/container.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
