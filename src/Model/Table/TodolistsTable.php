<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Todolists Model
 *
 * @method \App\Model\Entity\Todolist newEmptyEntity()
 * @method \App\Model\Entity\Todolist newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Todolist[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Todolist get($primaryKey, $options = [])
 * @method \App\Model\Entity\Todolist findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Todolist patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Todolist[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Todolist|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Todolist saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Todolist[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Todolist[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Todolist[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Todolist[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TodolistsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('todolists');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 251)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('user')
            ->requirePresence('user', 'create')
            ->notEmptyString('user');

        return $validator;
    }
}
