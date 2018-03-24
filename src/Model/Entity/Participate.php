<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Participate Entity
 *
 * @property int $id
 * @property int $id_g
 * @property int $id_u
 */
class Participate extends Entity
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
        'id_g' => true,
        'id_u' => true
    ];
}
