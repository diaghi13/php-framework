<?php /** @var $user */?>

<?php if (\application\core\Application::$app->session->getFlash('login')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo \application\core\Application::$app->session->getFlash('login') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif;?>

<h1>Hello, <?php echo $user?>!</h1>