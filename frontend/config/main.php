<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',    # very important line, when DocumentRoot on frontend/web/../../
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'frontcook',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,    // no framework magic plz
            'suffix' => '',
            'rules' => [
                '' => 'site/index',
                '<controller:[\w-]+>/<action:[\w-]+>/' => '<controller>/<action>',
                '<controller:[\w-]+>/' => '<controller>/index',
            ],
        ],
    ],
    'on beforeRequest' => function() {
        // redirect all ending with / to non-/
        // TODO: check if nginx does this job instead, for better ux.
        $app = \Yii::$app;
        $pathInfo = $app->request->pathInfo;
        if (isset($app->request->isAjax) && $app->request->isAjax) {
            return;
        }
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $query = $app->request->getQueryString();
            $qs = empty($query) ? '' : '?' . $query;
            $new_url = '/' . rtrim($pathInfo, '/') . $qs;
            $app->response->redirect($new_url, 301);
            \Yii::$app->end();
        }
    },
    'params' => $params,
];
