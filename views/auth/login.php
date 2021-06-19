<?php /** @var $model \application\app\models\auth\LoginUser */ ?>

<h1>Login</h1>

<?php $form = \application\core\view\component\form\Form::begin("", "post")?>
<?php echo input_csrf_token(); ?>
<?php echo $form->inputField($model, 'emailAddress')->isEmail()?>
<?php echo $form->inputField($model, 'passwordHash')->isPassword()?>
<button type="submit" class="btn btn-primary">Login</button>
<?php $form->end()?>