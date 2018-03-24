<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Participate Model
 *
 * @method \App\Model\Entity\Participate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Participate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Participate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Participate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Participate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Participate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Participate findOrCreate($search, callable $callback = null, $options = [])
 */
class ParticipateTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('participate');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->integer('id_g')
                ->allowEmpty('id_g');

        $validator
                ->integer('id_u')
                ->allowEmpty('id_u');

        return $validator;
    }

    public function participate($user_id = null, $game_id = null) {
        $this->query()
                ->insert(['id_g', 'id_u', 'participe', 'isInvited', 'isAccepted'])
                ->values([
                    'id_g' => $game_id,
                    'id_u' => $user_id,
                    'participe' => true,
                    'isInvited' => 0,
                    'isAccepted' => 1
                ])
                ->execute();
    }

    public function reparticipate($user_id = null, $game_id = null) {
        $this->query()
                ->update()
                ->set(['participe' => true])
                ->where(['id_g' => $game_id, 'id_u' => $user_id, 'isInvited' => 0])
                ->execute();
    }

    public function notparticipate($user_id = null, $game_id = null) {
        $this->query()
                ->insert(['id_g', 'id_u', 'participe'])
                ->values([
                    'id_g' => $game_id,
                    'id_u' => $user_id,
                    'participe' => false,
                    'isInvited' => 0
                ])
                ->execute();
    }

    public function unparticipate($user_id = null, $game_id = null) {
        $this->query()
                ->update()
                ->set(['participe' => false])
                ->where(['id_g' => $game_id, 'id_u' => $user_id, 'isInvited' => 0])
                ->execute();
    }

    public function already_participate($user_id = null, $game_id = null) {
        $bool = $this->find('all')
                ->where(['id_g' => $game_id])
                ->where(['id_u' => $user_id])
                ->where(['participe' => 1])
                ->where(['isInvited' => 0])
                ->limit(1)
                ->first();
        if ($bool) {
            return true;
        } else {
            return false;
        }
    }

    public function already_respond($user_id = null, $game_id = null) {
        $bool = $this->find('all')
                ->where(['id_g' => $game_id])
                ->where(['id_u' => $user_id])
                ->where(['isInvited' => 0])
                ->limit(1)
                ->first();
        if ($bool) {
            return true;
        } else {
            return false;
        }
    }

    public function get_players($game_id = null) {
        return $this->find()
                        ->select(['id_u', 'isInvited', 'guestfullname'])
                        ->where(['id_g' => $game_id])
                        ->where(['isAccepted' => 1])
                        ->toArray();
    }

    public function get_players_not_invited($game_id = null) {
        return $this->find()
                        ->select(['id_u'])
                        ->where(['id_g' => $game_id])
                        ->where(['isAccepted' => 1])
                        ->where(['isInvited' => 0])
                        ->toArray();
    }

    public function get_number_matchs($user_id = null) {
        return $this->find()
                        ->where(['id_u' => $user_id])
                        ->where(['participe' => 1])
                        ->where(['isInvited' => 0])
                        ->count();
    }

    public function get_number_players($game_id = null) {
        return $this->find()
                        ->where(['id_g' => $game_id])
                        ->where(['participe' => 1])
                        ->where(['isAccepted' => 1])
                        ->count();
    }

    public function invite($user_id = null, $game_id = null, $fullname = null, $isAccepted) {
        $this->query()
                ->insert(['id_g', 'id_u', 'participe', 'isInvited', 'isAccepted', 'guestfullname'])
                ->values([
                    'id_g' => $game_id,
                    'id_u' => $user_id,
                    'participe' => true,
                    'isInvited' => 1,
                    'isAccepted' => $isAccepted,
                    'guestfullname' => $fullname
                ])
                ->execute();
    }

    public function removeinvite($user_id = null, $game_id = null) {
        $this->query()
                ->delete()
                ->where(['id_u' => $user_id])
                ->where(['id_g' => $game_id])
                ->where(['isInvited' => 1])
                ->execute();
    }

    public function get_invite() {
        return $this->find()
                        ->select(['id', 'id_g', 'id_u', 'guestfullname'])
                        ->where(['isAccepted' => 0])
                        ->toArray();
    }

    public function get_nb_invite() {
        return $this->find()
                        ->where(['isAccepted' => 0])
                        ->count();
    }

    public function acceptinvite($id = null) {
        $this->query()
                ->update()
                ->set(['isAccepted' => true])
                ->where(['id' => $id])
                ->execute();
    }

    public function declineinvite($id = null) {
        $this->query()
                ->delete()
                ->where(['id' => $id])
                ->execute();
    }

    public function get_guest($user_id = null, $game_id = null) {
        return $this->find()
                        ->select(['guestfullname'])
                        ->where(['id_u' => $user_id, 'id_g' => $game_id, 'participe' => 1, 'isInvited' => 1, 'isAccepted' => 1])
                        ->toArray();
    }

    public function delete_by_game_id($game_id = null) {
        $this->query()
                ->delete()
                ->where(['id_g' => $game_id])
                ->execute();
    }

}
