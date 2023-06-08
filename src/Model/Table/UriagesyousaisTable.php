<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Uriagesyousais Model
 *
 * @method \App\Model\Entity\Uriagesyousai get($primaryKey, $options = [])
 * @method \App\Model\Entity\Uriagesyousai newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Uriagesyousai[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Uriagesyousai|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Uriagesyousai|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Uriagesyousai patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Uriagesyousai[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Uriagesyousai findOrCreate($search, callable $callback = null, $options = [])
 */
class UriagesyousaisTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('uriagesyousais');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        //https://qiita.com/chiyoyo/items/7cd4ddf5c8c5c7b99eb7　//id以外のカラムで結合OK

        $this->belongsTo('Uriagemasters', [
          'bindingKey' => 'id',
          'foreignKey' => 'uriagemasterId'
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('uriagemasterId')
            ->requirePresence('uriagemasterId', 'create')
            ->notEmpty('uriagemasterId');

        $validator
            ->numeric('num')
            ->requirePresence('num', 'create')
            ->notEmpty('num');

        $validator
            ->scalar('pro')
            ->maxLength('pro', 255)
            ->requirePresence('pro', 'create')
            ->notEmpty('pro');

        $validator
            ->scalar('tani')
            ->maxLength('tani', 255)
            ->allowEmpty('tani');

        $validator
            ->integer('amount')
            ->allowEmpty('amount');

        $validator
            ->integer('tanka')
            ->allowEmpty('tanka');

        $validator
            ->integer('zeiritu')
            ->allowEmpty('zeiritu');

        $validator
            ->integer('price')
            ->allowEmpty('price');

        $validator
            ->scalar('bik')
            ->maxLength('bik', 255)
            ->allowEmpty('bik');

        $validator
            ->date('uriagebi')
            ->allowEmpty('uriagebi');

        $validator
            ->integer('delete_flag')
            ->requirePresence('delete_flag', 'create')
            ->notEmpty('delete_flag');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

        return $validator;
    }
}
