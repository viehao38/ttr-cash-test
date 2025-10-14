<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemSettingModel extends Model
{
    // gọi model này trong controller:
    protected $table = 'system_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes  = false;
    protected $protectFields  = true;
    protected $allowedFields  = [//Các cột có thể chèn, update bằng $model->save, insert, update
        'meta_key',
        'meta_value',
        'label',
        'field_type',
        'options'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;//tự động kích hoạt time
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules= [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert= [];
    protected $afterInsert  = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
