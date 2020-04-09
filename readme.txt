# For dev machine bootstrap:

# composer create-project --prefer-dist yiisoft/yii2-app-advanced .
composer.phar install
php yii init
php yii migrate
# console/controllers/RbacController.php // change pass here
php yii rbac/init
php yii rbac/create-admin

