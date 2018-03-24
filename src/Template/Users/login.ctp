<?php $this->assign('title', 'Connnexion'); ?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center" >Connexion</h2>
            </div>
            <div class="panel-body text-center">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create() ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('email', ['required' => true, 'placeholder' => 'Email']) ?>
                    <?= $this->Form->control('password', ['label' => 'Mot de passe', 'required' => true, 'placeholder' => 'Mot de passe']) ?>
                </fieldset>
                <?= $this->Form->button(__('Se connecter')); ?>
                <?= $this->Form->end() ?>
            </div>
            <p class="text-center">
                <?php
                echo $this->Html->link(
                        'Mot de passe oublié ?', ['controller' => 'Users', 'action' => 'forgetpassword', '_full' => true], ['role' => 'button', 'class' => 'btn']
                );
                ?>
            </p>
        </div>
    </div>
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
</div>
