<?php
/**
 * Created by PhpStorm.
 * User: naseer
 * Date: 05/10/16
 * Time: 17:01
 */

namespace app\assets;
use yii\web\AssetBundle;

class FontawsomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'fonts/font-awesome/css/font-awesome.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}