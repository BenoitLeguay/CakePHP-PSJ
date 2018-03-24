<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
                ->email('email')
                ->allowEmpty('email');

        $validator
                ->scalar('prenom')
                ->allowEmpty('prenom');

        $validator
                ->scalar('nom')
                ->allowEmpty('nom');

        $validator
                ->scalar('password')
                ->allowEmpty('password');

        $validator
                ->scalar('role')
                ->allowEmpty('role');

        return $validator;
    }

    public function validationPassword(Validator $validator) {

        $validator
                ->add('old_password', 'custom', [
                    'rule' => function($value, $context) {
                        $user = $this->get($context['data']['id']);
                        if ($user) {
                            if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                                return true;
                            }
                        }
                        return false;
                    },
                    'message' => 'The old password does not match the current password!',
                ])
                ->notEmpty('old_password');

        $validator
                ->add('password1', [
                    'length' => [
                        'rule' => ['minLength', 6],
                        'message' => 'The password have to be at least 6 characters!',
                    ]
                ])
                ->add('password1', [
                    'match' => [
                        'rule' => ['compareWith', 'password2'],
                        'message' => 'The passwords does not match!',
                    ]
                ])
                ->notEmpty('password1');
        $validator
                ->add('password2', [
                    'length' => [
                        'rule' => ['minLength', 6],
                        'message' => 'The password have to be at least 6 characters!',
                    ]
                ])
                ->add('password2', [
                    'match' => [
                        'rule' => ['compareWith', 'password1'],
                        'message' => 'The passwords does not match!',
                    ]
                ])
                ->notEmpty('password2');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    public function get_email($user_id) {
        $email = $this->find()
                ->select('email')
                ->where(['id' => $user_id])
                ->limit(1)
                ->first();
        return $email['email'];
    }

    public function get_fullname($user_id = null) {
        $fullname = $this->find()
                ->select(['nom', 'prenom'])
                ->where(['id' => $user_id])
                ->limit(1)
                ->first();
        return $fullname['prenom'] . ' ' . $fullname['nom'];
    }

    public function not_in($array) {
        return $this->find()
                        ->select('id')
                        ->where(['id NOT IN' => $array])
                        ->toArray();
    }

    public function get_info($user_id) {
        return $this->find()
                        ->select(['id', 'email', 'prenom', 'nom', 'role', 'created'])
                        ->where(['id' => $user_id])
                        ->toArray();
    }

    public function add_user($prenom = null, $nom = null, $password = null, $email = null, $role = null) {
        $this->query()
                ->insert(['email', 'prenom', 'nom', 'password', 'role'])
                ->values([
                    'email' => $email,
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'password' => $password,
                    'role' => $role
                ])
                ->execute();
    }

    public function getLastId() {
        $id = $this->find()
                ->select('id')
                ->order(['id' => 'DESC'])
                ->limit(1)
                ->first();
        return $id['id'];
    }

    public function get_admins() {
        return $this->find()
                        ->select('id')
                        ->where(['role' => 'admin'])
                        ->toArray();
    }

}
