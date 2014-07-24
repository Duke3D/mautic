<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!$app->getRequest()->isXmlHttpRequest()):
    //load base template
    $view->extend('MauticUserBundle:Security:base.html.php');
    $view['blocks']->set('header', $view['translator']->trans('mautic.user.auth.header'));
else:
    $view->extend('MauticUserBundle:Security:ajax.html.php');
endif;
?>

<form class="form-login" name="login" data-toggle="ajax" role="form" action="<?php echo $view['router']->generate('mautic_user_logincheck') ?>" method="post">
    <div class="margin-md">
        <label for="username" class="sr-only"><?php echo $view['translator']->trans('mautic.user.auth.form.loginusername'); ?></label>
        <input type="text" id="username" name="_username"
               class="form-control input-lg" value="<?php echo $last_username ?>" required autofocus
               placeholder='<?php echo $view['translator']->trans('mautic.user.auth.form.loginusername'); ?>' />
    </div>
    <div class="margin-md">
        <label for="password" class="sr-only"><?php echo $view['translator']->trans('mautic.user.auth.form.loginpw'); ?>:</label>
        <input type="password" id="password" name="_password"
               class="form-control input-lg" required
               placeholder='<?php echo $view['translator']->trans('mautic.user.auth.form.loginpw'); ?>' />
    </div>
    <div class="margin-md">
        <input type="checkbox" id="remember_me" name="_remember_me" />
        <label for="remember_me"><?php echo $view['translator']->trans('mautic.user.auth.form.rememberme'); ?></label>
    </div>

    <input type="hidden" name="_csrf_token" value="<?php echo $view['form']->csrfToken('authenticate') ?>" />
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $view['translator']->trans('mautic.user.auth.form.loginbtn'); ?></button>
</form>