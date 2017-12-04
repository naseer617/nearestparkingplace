<?php
/**
 * @creator   Naseer Ahmad <naseer@bt.tn>
 * @created  2017-02-06
 * @package
 * @category
 */

namespace app\assets;

use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $vendor = '@vendor';
    public $baseUrl = '@web';

    public $js = [
        'js/garages.js',
    ];

    public $css = [
        'css/garages.css',
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    /*public $depends = [
        'app\assets\AppAsset',
    ];*/
}