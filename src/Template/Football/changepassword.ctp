<?php $this->assign('title', $Auth->user('prenom') . ' ' . $Auth->user('nom')); ?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" >Changer le mot de passe</h3>
            </div>
            <div class="panel-body">
                <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
                </div>
                <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
                    <?= $this->Flash->render() ?>
                    <?= $this->Form->create() ?>
                    <fieldset>
                        <?= $this->Form->input('old_password', ['type' => 'password', 'label' => 'Ancien mot de passe']) ?>
                        <?= $this->Form->input('password1', ['type' => 'password', 'label' => 'Mot de passe']) ?>
                        <?= $this->Form->input('password2', ['type' => 'password', 'label' => 'Confirmer le mot de passe']) ?>
                    </fieldset>
                    <?= $this->Form->button(__('Modifier')) ?>
                    <?= $this->Form->end() ?>
                </div>
                <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
                </div>
            </div>
            <div class="panel-body">
            </div>
        </div>
        <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
        </div>
    </div>