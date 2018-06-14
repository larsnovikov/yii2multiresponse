<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 13.06.18
 * Time: 20:12
 */

namespace vendor\larsnovikov\yii2multiresponse\widgets;

use vendor\larsnovikov\yii2multiresponse\assets\ContainerAsset;
use yii\web\AssetBundle;

/**
 * Class HtmlWidget
 * @package vendor\larsnovikov\yii2multiresponse\widgets
 */
abstract class AbstractHtmlWidget extends AbstractWidget implements WidgetInterface
{
    /**
     * Js функция которая будет вызвана после получения контента от WS server
     * @return string
     */
    public function getCallbackFunction(): string
    {
        return <<<JS
             console.log('callback function');
             console.log(response.token);
             console.log(response.message);
             $('#multiresponse_'+response.token).html(response.message);
JS;
    }

    /**
     * @return AssetBundle
     */
    public function getAsset(): AssetBundle
    {
        return new ContainerAsset;
    }

    /**
     * Создание пустого контейнера
     * @return string
     */
    public function createContainer(): string
    {
        return $this->render($this->dataRequest->getView(), array_merge($this->dataRequest->getData(), [
            'token' => $this->dataRequest->getToken()
        ]));
    }
}
