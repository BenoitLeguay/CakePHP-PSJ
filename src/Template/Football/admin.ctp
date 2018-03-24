<?php $this->assign('title', $Auth->user('prenom') . ' ' . $Auth->user('nom')); ?>
<div class="row">
    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
    </div>
    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center" ><?php echo $Auth->user('prenom') . ' ' . $Auth->user('nom') ?></h3>
            </div>
            <div class="panel-body" style="background:#FFFFF;">
                <?= $this->Flash->render() ?>
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4">

                        <div class="thumbnail">
                            <?php
                            echo $this->Html->image($Auth->user('id') . '.png', array('alt' => 'photo', 'border' => '0'));
                            ?><br><p class='text-center'><button class="btn btn-default btn-success" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    Changer  d'avatar
                                </button></p>
                            <div class="collapse" id="collapseExample">
                                <div class="well"><?php
                                    echo $this->Form->create('NouveauC', ['type' => 'file']);
                                    ?> <p class="text-center">
                                    <?php
                                    echo $this->Form->file('avatar', ['_button' => ['class' => 'btn btn-default btn-info', 'label' => 'avatar']]);
                                    ?></p><?php
                                    ?> <p class="text-center"> <?php echo $this->Form->button('Changer', ['class' => 'btn btn-default btn-info']); ?></p><?php
                                    echo $this->Form->end();
                                    ?>
                                </div>
                            </div>
                            <div class="caption text-center">
                                <h2><b><?php echo $Auth->user('prenom') . ' ' . $Auth->user('nom'); ?></b></h2>
                                <h4><i><?php echo $Auth->user('email'); ?></i></h4>

                            </div>
                        </div>

                    </div>
                    <div class="col-sm-8 col-md-8 col-lg-8">

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Sécurité</h3>
                            </div>
                            <div class="panel panel-body">
                                <?php
                                echo $this->Html->link(
                                        'Changer mon mot de passe', ['controller' => 'Football', 'action' => 'changepassword', '_full' => true], ['role' => 'button', 'class' => 'btn']
                                );
                                ?>
                            </div>
                        </div>


                        <div class = "panel panel-primary">
                            <div class = "panel-heading">
                                <h3 class = "panel-title">Statistiques</h3>
                            </div>
                            <div class="panel panel-body">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-info">
                                        Nombre de match(s) joué(s) : <?php echo $number ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class = "panel panel-primary">
                            <div class = "panel-heading">
                                <h3 class = "panel-title">Invitation</h3>
                            </div>
                            <div class="panel panel-body">
                                <?php if (!$invitations): ?>
                                    Aucune demande d'invitation pour le moment. <?php else:
                                    ?>
                                    <?php foreach ($invitations as $invitation): ?>
                                        <ul class = "list-group text-center">
                                            <li class="list-group-item list-group-item-primary">
                                                <?php echo 'Invitation de <b>' . $invitation['invitedBy'] . '</b> pour <b>' . $invitation['guestfullname'] . '</b> pour le match du <b>' . $invitation['gameDate']->nice('Europe/Paris', 'fr-FR') . '</b>'; ?> <?php
                                                echo $this->Html->link('<i class="glyphicon glyphicon-ok"></i>', array('controller' => 'Football', 'action' => 'admininvite', true, $invitation['id']), array('escape' => false, 'confirm' => 'Voulez vous vraiment accepter cet invitation ?'));
                                                echo '&nbsp' . '&nbsp';
                                                echo $this->Html->link('<i class="glyphicon glyphicon-remove" style="color:red"> </i>', array('controller' => 'Football', 'action' => 'admininvite', 0, $invitation['id']), array('escape' => false, 'confirm' => 'Voulez vous vraiment refuser cet invitation ?'));
                                                ?>

                                            </li>
                                        </ul>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                        <div class = "panel panel-primary">
                            <div class = "panel-heading">
                                <h3 class = "panel-title">Admin</h3>
                            </div>
                            <div class="panel panel-body">
                                <?php
                                echo $this->Html->link(
                                        'Ajouter un membre', ['controller' => 'Football', 'action' => 'addmember', '_full' => true], ['role' => 'button', 'class' => 'btn']
                                );
                                echo $this->Html->link(
                                        'Envoyez un email', ['controller' => 'Football', 'action' => 'adminsendemail', '_full' => true], ['role' => 'button', 'class' => 'btn']
                                );
                                echo $this->Html->link(
                                        'Créez le prochain match', ['controller' => 'Football', 'action' => 'creategame', '_full' => true], ['role' => 'button', 'class' => 'btn']
                                );
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class = "col-sm-1 col-md-1 col-lg-1 col-xl-1">
        </div>
    </div>
