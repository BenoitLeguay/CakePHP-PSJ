<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            PSJ
        </title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css('bootstrap.css'); ?>


        <?=
        $this->Html->script([
            'jquery-3.2.1.min.js',
            'bootstrap.min.js'
        ]);
        ?>

        <?= $this->Html->css('webarena.css') ?>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>

        <?php
        $var = 'Admin ';
        if ($nb_invite > 0):
            $var = $var . '<span class="label label-danger">' . $nb_invite . '</span>';
        endif;
        ?>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="navbar-brand" style="color: black !important;"><?= $this->fetch('title') ?></span>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (!$Auth->user('id')): ?>
                            <li><?php echo $this->Html->link('Se connecter', array('controller' => 'Users', 'action' => 'login')); ?></li>
                        <?php elseif ($Auth->user('id')): ?>
                            <li><?php echo $this->Html->link('Accueil', array('controller' => 'Football', 'action' => 'index')); ?></li>
                            <?php if ($Auth->user('role') == 'admin'): ?>
                                <li><?php echo $this->Html->link($var, array('controller' => 'Football', 'action' => 'admin'), ['escape' => false]);
                                ?></li><?php
                            elseif ($Auth->user('role') == 'membre'):
                                ?>
                                <li><?php echo $this->Html->link('Membre', array('controller' => 'Football', 'action' => 'membre')); ?></li><?php
                            endif;
                            ?>
                            <li><?php echo $this->Html->link('Déconnexion', array('controller' => 'Users', 'action' => 'logout'));
                            ?></li>
                        <?php endif; ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <?= $this->Flash->render() ?>
        <div class="container-fluid clearfix">
            <?= $this->fetch('content') ?>
        </div>
        <footer class="footer container-fluid">
            <div class="jumbotron text-center" style="background-color: #337AB7 !important;color: white !important;">
                <h2><b>Pré Saint-Jean: Association de football</b></h2>
                <p>site créé par LeCodeurFou</p>
            </div>
        </footer>
    </body>
</html>
