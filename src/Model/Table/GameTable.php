<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * Game Model
 *
 * @method \App\Model\Entity\Game get($primaryKey, $options = [])
 * @method \App\Model\Entity\Game newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Game[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Game|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Game[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Game findOrCreate($search, callable $callback = null, $options = [])
 */
class GameTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('game');
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
                ->date('date')
                ->allowEmpty('date');

        return $validator;
    }

    public function get_last_date() {
        $date = $this->find()
                ->select('date')
                ->order(['date' => 'DESC'])
                ->limit(1)
                ->first();
        return $date['date'];
    }

    public function add_game($date = null) {
        $this->query()
                ->insert(['date'])
                ->values(['date' => $date])
                ->execute();
    }

    public function get_next_games($sight = null) {
        $oneMonthTime = Time::now();
        $oneMonthTime->addMonth(1);
        return $this->find()
                        ->select(['id', 'date'])
                        ->where(['date BETWEEN :start_date AND :end_date'])
                        ->bind(':start_date', Time::now(), 'datetime')
                        ->bind(':end_date', $oneMonthTime, 'datetime')
                        ->order(['date' => 'ASC'])
                        ->toArray();
    }

    public function get_date($game_id = null) {
        $email = $this->find()
                ->select('date')
                ->where(['id' => $game_id])
                ->limit(1)
                ->first();
        return $email['date'];
    }

    public function get_futur_deleted_games() {
        $nowTime = Time::now();
        return $this->find()
                        ->select('id')
                        ->where(['date <' => $nowTime])
                        ->toArray();
    }

    public function delete_game() {
        $nowTime = Time::now();
        $this->query()
                ->delete()
                ->where(['date <' => $nowTime])
                ->execute();
    }

}
