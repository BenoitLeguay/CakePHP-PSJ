<?php $this->assign('title', $info['prenom'] . ' ' . $info['nom']); ?>
<div class="row">
    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
    </div>
    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center" ><?php echo $info['prenom'] . ' ' . $info['nom']; ?></h3>
            </div>
            <div class="panel-body" style="background:#FFFFF;">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4">

                        <div class="thumbnail">
                            <?php
                            echo $this->Html->image($info['id'] . '.png', array('alt' => 'avatar', 'border' => '0'));
                            ?>
                            <div class="caption text-center">
                                <h2><b><?php echo $info['prenom'] . ' ' . $info['nom']; ?></b></h2>
                                <h4><i><?php echo $info['email']; ?></i></h4>
                                <h4><b><?php echo $info['role']; ?></b></h4>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-8 col-md-8 col-lg-8">
                        <div class = "panel panel-primary">
                            <div class = "panel-heading">
                                <h3 class = "panel-title">Contact</h3>
                            </div>
                            <div class="panel panel-body">
                                <?php
                                echo $this->Html->link(
                                        'Envoyer un email à ' . $info['prenom'] . ' ' . $info['nom'], ['_full' => true], ['role' => 'button', 'class' => 'btn', 'data-toggle' => 'modal', 'data-target' => "#myModal"]
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
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="panel panel-default center-block" style="width: 600px; margin-top: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Contenue de l'email</h4>
            </div>
            <div class="modal-body">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create('arenas', ['url' => '/arenas/scream']) ?>
                <fieldset class="text-left">
                    <?= $this->Form->control('Envoyez email') ?>
                </fieldset>

            </div>
            <div class="modal-footer">
                <?= $this->Form->button(__('Envoyer')); ?>
                <?= $this->Form->end() ?>
            </div>
        </div>

    </div>
</div>
