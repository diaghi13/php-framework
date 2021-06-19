<?php /** @var $user \application\app\models\User */?>

<?php if (\application\core\Application::$app->session->getFlash('login')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo \application\core\Application::$app->session->getFlash('login') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif;?>

<?php if (\application\core\Application::$app->isGuest()):?>
    <h1>Welcome to the PHP Framework</h1>
<?php else:?>
    <h1>Hello, <?php echo $user->fullName()?>!</h1>
<?php endif;?>