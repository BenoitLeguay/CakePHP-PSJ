<?php $this->assign('title', 'Envoyez un email'); ?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center" >Envoyez un email</h2>
            </div>
            <div class="panel-body text-center">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create() ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('to', ['label' => 'Envoyez à', 'required' => true, 'placeholder' => 'to', 'options' => $users, 'empty' => true]) ?>
                    <?= $this->Form->control('subject', ['label' => 'Objet', 'required' => true, 'placeholder' => 'Objet']) ?>
                    <?= $this->Form->control('content', ['label' => 'Contenue', 'required' => true, 'placeholder' => 'Contenue', 'type' => 'textarea']) ?>
                </fieldset>
                <?= $this->Form->button(__('Envoyez')); ?>
                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
</div>
