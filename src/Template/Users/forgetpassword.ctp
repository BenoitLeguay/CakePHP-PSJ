<?php $this->assign('title', 'Mot de passe oublié'); ?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center" >Entrez votre adresse email</h2>
            </div>
            <div class="panel-body text-center">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create() ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('email', ['required' => true, 'placeholder' => 'Email']) ?>
                </fieldset>
                <?= $this->Form->button(__('Envoyez un email')); ?>
                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
</div>
