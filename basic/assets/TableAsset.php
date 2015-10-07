<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TableAsset extends AssetBundle
{
    public $sourcePath = '@web';    
    public $css = [
        '/css/dataTables.css',        
    ];
    public $js = [
        '/js/jquery.dataTables.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',        
    ];
}
