<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedienteModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pindust_expediente';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idExp', 'nif', 'empresa', 'email', 'convocatoria', 'tipo_tramite', 'doc'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function findExpedienteById($idExp, $convocatoria,$tipoTramite)
    {
        $expediente = $this->asArray()->where(['idExp'=> $idExp, 'convocatoria'=> $convocatoria, 'tipo_tramite'=> $tipoTramite])->first();

        if (!$expediente) {
            throw new \Exception('E000'); /* Could not find expediente for specified idExp/Convocatoria */
        }

        return $expediente;
    }
}
