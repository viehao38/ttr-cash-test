<?php

namespace App\Controllers\Admin;

use App\Models\SystemSettingModel;
use App\Requests\SystemSetting\CreateSystemSettingRequest;
use App\Resources\SystemSettingResource;
use App\Services\JwtAuthService;
use CodeIgniter\RESTful\ResourceController;

class SystemSettingController extends ResourceController
{
    protected $model;
    protected $request;

    protected $jwtService;
    protected $controllerName = 'Setting';

    public function __construct()
    {
        $this->model = new SystemSettingModel();
        $this->jwtService = new JwtAuthService();
         helper('role_helper');//load helper để gọi isAdmin
    }


    
  public function index()
  {
    try {
        $auth = $this->jwtService->authenticateUser();

      if (!$auth['status']) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

        $userInfo = (array)$auth['user_info'];
        // Lấy mảng data chứa thông tin user
        $data = (array)$userInfo['data'];
        // Lấy role_id
        $roleId = $data['role_id'];

      if (!isAdmin($roleId)) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

      // Validation lỗi nhập liệu
      $rules = [
        'page'   => 'permit_empty|integer|is_natural_no_zero',
        'limit'  => 'permit_empty|integer|is_natural_no_zero',
        'search' => 'permit_empty|max_length[100]',
      ];
      $messages = [
        'page.is_natural_no_zero'  => 'Page must be a positive number.',
        'limit.is_natural_no_zero' => 'Limit must be a positive number.',
        'search.max_length'        => 'Search term must not exceed 100 characters.',
      ];

      $validation = \Config\Services::validation();
      $validation->setRules($rules, $messages);
      $request = $this->request->getGet();
      if (!$validation->run($request)) {
        return $this->respond([
          'status' => false,
          'message' => 'Validation failed',
          'errors' => $validation->getErrors()
        ], 422);
      }

      $page  = isset($request['page']) ? (int) $request['page'] : 1;
      $limit = isset($request['limit']) ? (int) $request['limit'] : 10;
      $search = $request['search'] ?? "";
      $status = $request['status'] ?? "";

      $offset = ($page - 1) * $limit;

      $builder = $this->model;
      if (!empty($search)) {
        $builder->where('meta_key', $search);
      }

      if ($status != '' && !is_null($status)) {
        $builder->where('status', $status);
      }

      $total = $builder->countAllResults(false);
      $system_settings = $builder
        ->orderBy('created_at', 'DESC')
        ->findAll($limit, $offset);

      $resource = SystemSettingResource::collection($system_settings);
      return $this->respond([
        'status' => true,
        'data' => $resource,
        'pagination' => [
          'total' => $total,
          'limit' => $limit,
          'page' => $page,
          'pages' => ceil($total / $limit)
        ]
      ]);
    } catch (\Throwable $th) {
      $message = "SystemSettingController.index: ";
      $message .= $th->getFile() . " ";
      $message .= $th->getLine() . " ";
      $message .= $th->getMessage() . " ";
      log_message('error', $message);
      return $this->respond([
        'status' => false,
        'message' => 'An error occurred during processing. Please try again later.',
        'file' => $th->getFile(),
        'line' => $th->getLine()
      ],500);
    }
  }

  public function create()
  {
    try {
      //Authorization
      $auth = $this->jwtService->authenticateUser();
      if (!$auth['status']) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }
        $userInfo = (array)$auth['user_info'];
        // Lấy mảng data chứa thông tin user
        $data = (array)$userInfo['data'];
        // Lấy role_id
        $roleId = $data['role_id'];

      if (!isAdmin($roleId)) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

      //Initial request
      $request = $this->request->getJSON(true);
      //Validation
      $rules = CreateSystemSettingRequest::rules();

      $messages = CreateSystemSettingRequest::messages();
      if (!$this->validateData($request, $rules, $messages)) {
        return $this->respond([
          'status' => false,
          'errors' => $this->validator->getErrors(),
        ]);
      }
      //phần này đã sửa
      $meta_key = $request['meta_key'];
      $meta_value = $request['meta_value'] ?? null;
      $label = $request['label'];
      $field_type = $request['field_type'];
      $options = $request['options'];


      //Process
      $exist = $this->model->where('meta_key', $meta_key)->first();
      if ($exist) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.model_existed', ['name' => $this->controllerName]),
        ]);
      }
      
      $insertedId = $this->model->insert([
        'meta_key' => $meta_key,
        'meta_value' => $meta_value,
        'label' => $label,
        'field_type' => $field_type,
        'options' => $options
      ]);
      if (!$insertedId) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.model_create', ['name' => $this->controllerName])
        ], 404);
      }

      return $this->respond([
        'status' => true,
        'message' => lang('Common.success.model_create', ['name' => $this->controllerName])
      ], 201);
    } catch (\Throwable $th) {
      $message = "SystemSettingController.create: ";
      $message .= $th->getFile() . " ";
      $message .= $th->getLine() . " ";
      $message .= $th->getMessage() . " ";
      log_message('error', $message);
      return $this->respond([
        'status' => false,
        'message' => 'An error occurred during processing. Please try again later.',
        'file' => $th->getFile(),
        'line' => $th->getLine()
      ]);
    }
  }

  public function show($id = null)
  {
    try {
      //Authorization
      $auth = $this->jwtService->authenticateUser();
      if (!$auth['status']) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }
        $userInfo = (array)$auth['user_info'];
        // Lấy mảng data chứa thông tin user
        $data = (array)$userInfo['data'];
        // Lấy role_id
        $roleId = $data['role_id'];

      if(!isAdmin($roleId)) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

      $data_settings = $this->model->find($id);
      if (!$data_settings) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.not_found', ['meta_key' => $this->controllerName])
        ], 404);
      }

      $resource = new SystemSettingResource($data_settings);//set lại 
      return $this->respond([
        'status' => true,
        'data' => $resource,
      ]);
    } catch (\Throwable $th) {
      $message = "SystemSettingController.show: ";
      $message .= $th->getFile() . " ";
      $message .= $th->getLine() . " ";
      $message .= $th->getMessage() . " ";
      log_message('error', $message);
      return $this->respond([
        'status' => false,
        'message' => 'An error occurred during processing. Please try again later.',
        'file' => $th->getFile(),
        'line' => $th->getLine()

      ]);
    }
  }

  public function update($id = null)
  {
    try {
      //Authorization
      $auth = $this->jwtService->authenticateUser();

      if (!$auth['status']) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

        $userInfo = (array)$auth['user_info'];
        // Lấy mảng data chứa thông tin user
        $data = (array)$userInfo['data'];
        // Lấy role_id
        $roleId = $data['role_id'];

      if (!isAdmin($roleId)) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

      $role = $this->model->find($id);

      if (!$role) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.not_found', ['meta_key' => $this->controllerName])
        ], 404);
      }

      //Initial request
      $request = $this->request->getJSON(true);
      //Validation
      $rules    = CreateSystemSettingRequest::rules();
      $messages = CreateSystemSettingRequest::messages();
      if (!$this->validateData($request, $rules, $messages)) {
        return $this->respond([
          'status' => false,
          'errors' => $this->validator->getErrors(),
        ]);
      }

      //Process
      $this->model->update($id, [
        'meta_key' => $request['meta_key'],
        'meta_value' => $request['meta_value'],
        'label' => $request['label'],
        'field_type' => $request['field_type'],
        'options' => $request['options']

      ]);

      return $this->respond([
        'status' => true,
        'message' => lang('Common.success.model_update', ['meta_key' => $this->controllerName])
      ]);
    } catch (\Throwable $th) {
      $message = "SystemSettingController.update: ";
      $message .= $th->getFile() . " ";
      $message .= $th->getLine() . " ";
      $message .= $th->getMessage() . " ";
      log_message('error', $message);
      return $this->respond([
        'status' => false,
        'message' => 'An error occurred during processing. Please try again later.',
        'file' => $th->getFile(),
        'line' => $th->getLine()
      ]);
    }
  }

  public function delete($id = null)
  {
    try {
      //Authorization
      $auth = $this->jwtService->authenticateUser();
      if (!$auth['status']) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

        $userInfo = (array)$auth['user_info'];
        // Lấy mảng data chứa thông tin user
        $data = (array)$userInfo['data'];
        // Lấy role_id
        $roleId = $data['role_id'];

      if (!isAdmin($roleId)) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.no_authorize')
        ], 403);
      }

      $role = $this->model->find($id);
      if (!$role) {
        return $this->respond([
          'status' => false,
          'message' => lang('Common.error.not_found', ['meta_key' => $this->controllerName])
        ], 404);
      }
      $this->model->delete($id);

      return $this->respond([
        'status' => true,
        'message' => lang('Common.success.model_delete', ['meta_key' => $this->controllerName])
      ]);
    } catch (\Throwable $th) {
      $message = "SystemSettingController.delete failed: ";
      $message .= $th->getFile() . " ";
      $message .= $th->getLine() . " ";
      $message .= $th->getMessage() . " ";
      log_message('error', $message);
      return $this->respond([
        'status' => false,
        'message' => 'An error occurred during processing. Please try again later.',
        'file' => $th->getFile(),
        'line' => $th->getLine()
      ]);
    }
  }
  //ôn
    //   public function index()
    // {
    //     $settings = $this->model->findAll();
    //     return $this->respond([
    //         'status' => 200,
    //         'data' => $settings
    //     ]);
    // }

    // public function  show($id = null)
    // {
    //     $setting = $this->model->find($id);
    //     if(!$setting){
    //         return $this->failNotFound('not found');
    //     }
    //     return $this->respond([
    //         'status' => 200,
    //         'data' => $setting
    //     ]);
    // }


    // public function  create($id = null)
    // {
    //     $data = $this->request->getJSON();

    //     if(!$this->model->insert($data)){//xem có insrt dc ko
    //         return $this->failNotFound('dont create setting',404);
    //     }

    //     return $this->respond([
    //         'status' => 201,
    //         'messages' => 'Created successfully',
    //         'data' => $data
    //     ]);
    // }


    // public function  update($id = null)
    // {
    //     $data = $this->request->getJSON();// lấy dữ liệu json từ body của HTTP request
    //     $setting = $this->model->find($id);//tìm đối tượng update

    //     if(!$setting) {//check 
    //         return $this->failNotFound('Setting not found');
    //     }

    //     $this->model->update($id, $data);//update

    //     return $this->respond([
    //         'status' => 200,
    //         'messages' => 'update successfully',
    //     ]);
    // }


    // public function delete($id = null)
    // {
    //     $setting = $this->model->find($id);
    //     if(!$setting){
    //         return $this->failNotFound('Setting not found');
    //     }

    //     $this->model->delete($id);
    //        return $this->respond([
    //         'status' => 200,
    //         'messages' => 'deleta successfully',
    //     ]);
    // }

}
