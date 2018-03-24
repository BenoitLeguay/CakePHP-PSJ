<?php
$this->assign('title', 'Bonjour ' . $Auth->user('prenom') . ' ' . $Auth->user('nom'));
?>
<div class="row">
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
    <div class="col-sm-10 col-md-8 col-lg-8 col-xl-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center" >Vos prochains matchs</h2>
            </div>
            <div class="panel-body" style="background:#BBDEFB;">
                <?php foreach ($games as $game): ?>
                    <?= $this->Flash->render() ?>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Match du samedi <?php echo $game['date']->nice('Europe/Paris', 'fr-FR') . '  '; ?>
                                <?php
                                if ($game['isParticipating']):
                                    ?><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                <?php elseif (!$game['hasresponded'] || !$game['isParticipating']): ?>
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                <?php endif; ?>
                                <div class="pull-right"> <?php
                                    echo $game['number'] . ' joueur';
                                    if ($game['number'] > 1): echo 's';
                                    endif;
                                    ?> </div>
                            </h3>
                        </div>
                        <div class="panel-body text-center">
                            <div class="btn-group">
                                <?php
                                if (!$game['hasresponded']):
                                    echo $this->Html->link(
                                            'Participer', ['controller' => 'Football', 'action' => 'participate', $Auth->user('id'), $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-success']
                                    );
                                    echo $this->Html->link(
                                            'Ne pas participer', ['controller' => 'Football', 'action' => 'notparticipate', $Auth->user('id'), $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-danger']
                                    );
                                else:
                                    if (!$game['isParticipating']):
                                        echo $this->Html->link(
                                                'Participer', ['controller' => 'Football', 'action' => 'participate', $Auth->user('id'), $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-success']
                                        );
                                        echo $this->Html->link(
                                                'Ne pas participer', ['controller' => 'Football', 'action' => 'index'], ['role' => 'button', 'class' => 'btn btn-default disabled']
                                        );
                                    else:
                                        echo $this->Html->link(
                                                'Participer', ['controller' => 'Football', 'action' => 'index'], ['role' => 'button', 'class' => 'btn btn-default disabled']
                                        );
                                        echo $this->Html->link(
                                                'Ne pas participer', ['controller' => 'Football', 'action' => 'notparticipate', $Auth->user('id'), $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-danger']
                                        );
                                        echo $this->Html->link(
                                                'Inviter un ami', ['_full' => true], ['role' => 'button', 'class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => "#" . -$game['id']]
                                        );
                                        ?>


                                    <?php
                                    endif;
                                endif;
                                ?>
                            </div>
                        </div>
                        <ul class = "list-group text-center">
                            <p class='text-center'><a class="btn" type="button" data-toggle="collapse" data-target="#<?php echo $game['id'] ?>" aria-expanded="false" aria-controls="<?php echo $game['id'] ?>">
                                    <span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span>
                                </a></p>
                            <div class="collapse" id="<?php echo $game['id'] ?>">

                                <?php foreach ($game['players'] as $player):
                                    ?>
                                    <?php
                                    if ($player['isParticipating']):
                                        if (!$player['isInvited']):
                                            ?><?php if ($player['id_u'] == $Auth->user('id')): ?> <strong><?php endif; ?>
                                                <li class="list-group-item list-group-item-primary">
                                                    <?php
                                                    if ($player['id_u'] == $Auth->user('id')):
                                                        echo $player['fullname'];
                                                    else:
                                                        ?>
                                                        <?php
                                                        echo $this->Html->link(
                                                                $player['fullname'], ['controller' => 'Football', 'action' => 'user', $player['id_u'], '_full' => true]
                                                        );
                                                    endif;
                                                    ?>
                                                </li>

                                                <?php if ($player['id_u'] == $Auth->user('id')): ?> </strong><?php endif; ?>
                                        <?php else: ?>
                                                <?php if ($player['id_u'] == $Auth->user('id')): ?> <strong><?php endif; ?>
                                                <li class = "list-group-item list-group-item-warning"> <?php echo 'Invité de ' . $player['fullname'] . ' : ' . $player['guestfullname'];
                                                ?></li>
                                                <?php if ($player['id_u'] == $Auth->user('id')): ?> </strong><?php endif; ?>
                                        <?php
                                        endif;
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </ul>

                        <?php if ($Auth->user('role') == 'admin'): ?>
                            <div class="panel-footer">
                                <?php
                                echo $this->Html->link(
                                        'Rappel aux joueurs', ['controller' => 'Football', 'action' => 'reminduser', $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-info pull-left']
                                );
                                ?>
                                <?php
                                if ($game['ableToConfirm']):

                                    echo $this->Html->link(
                                            'Confirmer le match', ['controller' => 'Football', 'action' => 'confirm', $game['id'], '_full' => true], ['role' => 'button', 'class' => 'btn btn-info pull-right', 'style' => 'border-color: grey ;background-color:grey;']
                                    );
                                endif;
                                ?>

                                <div class="clearfix"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <div class="col-sm-1 col-md-2 col-lg-2 col-xl-3">
    </div>
</div>


<?php foreach ($games as $game): ?>

    <!-- Modal -->
    <div id="<?php echo -$game['id'] ?>" class="modal fade" role="dialog">
        <div class="panel panel-default center-block" style="width: 60%">

            <!-- Modal content-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inviter un joueur</h4>
            </div>
            <div class="modal-body">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create('Football', ['url' => '/football/invite', $Auth->user('id'), $game['id']]) ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('Prenom', ['label' => 'Prénom de l\'invité', 'required' => true, 'placeholder' => 'Prénom']) ?>
                    <?= $this->Form->control('Nom', ['label' => 'Nom de l\'invité', 'required' => true, 'placeholder' => 'Nom']) ?>
                    <?= $this->Form->input('game_id', ['default' => $game['id'], 'type' => 'hidden']); ?>
                    <?= $this->Form->input('user_id', ['default' => $Auth->user('id'), 'type' => 'hidden']); ?>
                </fieldset>

            </div>
            <div class="modal-footer">
                <?= $this->Form->button(__('Inviter')); ?>
                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
<?php endforeach; ?>