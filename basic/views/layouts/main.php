<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$this->title = "Техресурс58.рф";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Техресурс58.рф',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $items = [
      0 => ['label' => 'Главная',      'url' => ['/site/index']],
      1 => ['label' => 'Цена и заказ', 'url' => ['/site/search']],      
      4 => ['label' => 'Контакты',     'url' => ['/site/contact']]      
    ];    
    
    if( Yii::$app->user->isGuest ){
      $items[] = ['label' => 'Вход',     'url' => ['/site/login']];      
    } else {
      $items[2] = ['label' => 'Корзина', 'url' => ['/basket/index']];
      $items[3] = ['label' => 'Заказы',  'url' => ['/order/index']];
      
      /* @var $identity app\models\WebUser */
      $identity = \yii::$app->user->getIdentity();      
      
      if( $identity && $identity->isAdmin() ){
        $items[] = [  'label' => 'Управление клиентами',  'url' => ['/client/index']];
        $items[] = [  'label' => 'Управление заказами',   'url' => ['/order/admin']];        
      }
      
      $items[] = [  'label' => 'Выход(' . Yii::$app->user->identity->uname . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']];      
    }
    ksort($items);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; АвтоТехСнаб58 <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
