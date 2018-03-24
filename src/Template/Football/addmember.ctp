<?php $this->assign('title', 'Ajouter un membre'); ?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center" >Ajouter un membre</h2>
            </div>
            <div class="panel-body text-center">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create() ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('prenom', ['label' => 'Prénom', 'required' => true, 'placeholder' => 'Prénom']) ?>
                    <?= $this->Form->control('nom', ['label' => 'Nom', 'required' => true, 'placeholder' => 'Nom']) ?>
                    <?= $this->Form->control('email', ['required' => true, 'placeholder' => 'Email']) ?>
                    <?= $this->Form->control('role', ['label' => 'Role', 'required' => true, 'placeholder' => 'Admin ou membre', 'options' => array('membre', 'admin')]) ?>

                </fieldset>
                <?= $this->Form->button(__('Ajouter')); ?>
                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
</div>
