<?php

namespace App\Controllers;

use Exception;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\ExpedienteModel;
use CodeIgniter\HTTP\ResponseInterface;

class Expediente extends BaseController
{
    use ResponseTrait; /* Para forzar que la respuesta sea json */

    public function index()
    {
        $model = new ExpedienteModel();

        return $this->getResponse([
            'message' => 'Expedients retrieved successfully',
            'expedientes' => $model->findAll()
        ]);
    }

    public function store()
    {
        date_default_timezone_set("Europe/Madrid");
		$selloTiempo = date("d_m_Y_h_i_sa");

        $rules = [
            'idExp' => 'required|min_length[12]|max_length[12]',
            'empresa' => 'required',
            'nif' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[pindust_expediente.email]',
            'convocatoria' => 'required|min_length[4]|max_length[4]',
            'tipo_tramite' => 'required|min_length[8]|max_length[8]',
            'doc'          => 'uploaded[doc]|max_size[doc, 2048]|ext_in[doc,pdf]'
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this->getResponse($this->validator->getErrors(), ResponseInterface::HTTP_BAD_REQUEST);
        } 

        $expedienteEmail = $input['email'];
        $expedienteNif = $input['nif'];

        $model = new ExpedienteModel();
        $model->save($input);
		$last_insert_id = $model->connID->insert_id;

        if ($files = $this->request->getFiles()) {

            foreach ($files as $img) {
                if ($img->isValid() && ! $img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(WRITEPATH . 'documentos/'.$expedienteNif.'/'.$selloTiempo);
                    /* $img->move(WRITEPATH . 'documentos', $newName); */
                }
            }
        }

        $expediente = $model->where('email', $expedienteEmail)->first();

        return $this->getResponse([
            'message' => 'Expediente added successfully',
            'expediente' => $expediente,
            'archivo'   =>$files,
            'lastID'    => $last_insert_id,
            'nota'      => "Seguidamente, el benficiario recibirá un mail con un pdf para que lo firme electrónicamente"
        ]); 
    }

    public function show($idExp, $convocatoria)
    {
        try {

           $model = new ExpedienteModel();
           $expediente = $model->findExpedienteById($idExp, $convocatoria, 'IDI-ISBA');

            return $this->getResponse([
                'message' => 'Expediente retrieved successfully',
                'expediente' =>$expediente
            ]);

        } catch (Exception $e) {
            return $this->getResponse([
                'message' => 'Could not find expediente for specified parameters'
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function update($idExp, $convocatoria)
    {
        try {

            $model = new ExpedienteModel();
            $model->findExpedienteById($idExp, $convocatoria, 'IDI-ISBA');

            $input = $this->getRequestInput($this->request);


            $model->update($idExp, $convocatoria, 'IDI-ISBA', $input);
            $expediente = $model->findExpedienteById($idExp, $convocatoria, 'IDI-ISBA');

            return $this->getResponse([
                'message' => 'Expediente updated successfully',
                'expediente' => $expediente
            ]);

        } catch (Exception $exception) {

            return $this->getResponse([
                'message' => $exception->getMessage()
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function destroy($idExp, $convocatoria)
    {
        try {

            $model = new ExpedienteModel();
            $expediente = $model->findExpedienteById($idExp, $convocatoria, 'IDI-ISBA');
            $model->delete($expediente);

            return $this->getResponse([
                'message' => 'Expediente deleted successfully',
            ]);

        } catch (Exception $exception) {
            return $this->getResponse([
                'message' => $exception->getMessage()
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    }
}