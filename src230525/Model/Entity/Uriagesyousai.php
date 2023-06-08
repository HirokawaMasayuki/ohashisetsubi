<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Uriagesyousai Entity
 *
 * @property int $id
 * @property int $uriagemasterId
 * @property float $num
 * @property string $pro
 * @property string|null $tani
 * @property int|null $amount
 * @property int|null $tanka
 * @property int|null $zeiritu
 * @property int|null $price
 * @property string|null $bik
 * @property \Cake\I18n\FrozenDate|null $uriagebi
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Uriagemaster $uriagemaster
 */
class Uriagesyousai extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'uriagemasterId' => true,
        'num' => true,
        'pro' => true,
        'tani' => true,
        'amount' => true,
        'tanka' => true,
        'zeiritu' => true,
        'price' => true,
        'bik' => true,
        'uriagebi' => true,
        'delete_flag' => true,
        'created_at' => true,
        'uriagemaster' => true
    ];
}
