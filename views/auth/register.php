<?php /** @var $model LoginUser */

use application\app\models\auth\LoginUser; ?>

<h1>Register</h1>

<?php $form = \application\core\view\component\form\Form::begin("", "post")?>
    <?php echo input_csrf_token(); ?>
    <div class="row">
        <div class="col">
            <?php echo $form->inputField($model, 'firstName')?>
        </div>
        <div class="col">
            <?php echo $form->inputField($model, 'lastName')?>
        </div>
    </div>
    <?php echo $form->inputField($model, 'emailAddress')->isEmail()?>
    <?php echo $form->inputField($model, 'password')->isPassword()?>
    <?php echo $form->inputField($model, 'passwordConfirmation')->isPassword()?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php $form->end()?>