<?php

namespace App\Controllers\User;

use App\Requests\SystemSetting\CreateEmailHistoriesRequest;
use App\Resources\EmailHistoriesResources;
use CodeIgniter\RESTful\ResourceController;
use App\Services\JwtAuthService;
use App\Models\EmailHistoriesModel;

class EmailHistoriesController extends ResourceController
{
    protected $jwtService;
    protected $request;
    protected $model;
    public function __construct()
    {
        $this->jwtService = new JwtAuthService();
        $this->model = new EmailHistoriesModel();
        helper('role_helper');//load helper để gọi isUser
    }
    public function index()
    {
        try {
            $auth = $this->jwtService->authenticateUser();//xac thuc

            if(!$auth['status']){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.auth'
                ], 403);
            }

            $user = (array) $auth['user_info'];
            $data = (array) $user['data'];
            $roleId = $data['role_id'];

            if(!isUser($roleId)){
                return $this->respond([
                    'status' => false,
                    'message' => 'err.auth.role'
                ], 403);
            }

            $total = $this->model->countAll();//dem tat ca sl email

            $email_histories = $this->model
                ->orderBy('created_at', 'DESC')
                ->findAll(); 

            $resource = EmailHistoriesResources::collection($email_histories);
            return $this->respond([
                'status' => true,
                'data' => $resource,
                'total' => $total
            ]);
        
        }
        catch(\Throwable $th)
        {
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
            //auth
            $auth = $this->jwtService->authenticateUser();

            if(!$auth['status']){
                return $this->respond([
                'status' => false,
                'message' => lang('error.no_authorize')
                ], 403);
            }

            $user = (array)$auth['user_info'];
            $data = (array)$user['data'];
            $roleId = $data['role_id'];

             if (!isUser($roleId)) {
                return $this->respond([
                'status' => false,
                'message' => lang('error.no authorize')
                ], 403);
            }
            //lay json body
            $request = $this->request->getJSON(true);
            //du lieu nhap validate
            $rules = CreateEmailHistoriesRequest::rules();
            $messages = CreateEmailHistoriesRequest::messages();

            if(!$this->validateData($request, $rules, $messages)){
                 return $this->respond([
                    'status' => false,
                    'errors' => $this->validator->getErrors(),
                ]);
            }

            $code = $request['code'];
            $recipient = $request['recipient'];
            $cc = $request['cc'] ?? null;
            $bcc = $request['bcc'] ?? null;
            $subject = $request['subject'];
            $body = $request['body'];
            $error_message = $request['error_message'] ?? null;
            $status = $request['status'];
            $sent_at = $request['sent_at']?? null;
            $resent_times = $request['resent_times'];
            //kiem tra ton tai
            $exist = $this->model->where('code', $code)->first();
            if($exist){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.model_existed'
                    ]);
            }

            $insertedId = $this->model->insert([
                'code' => $code,
                'recipient' => $recipient,
                'cc' => $cc,
                'bcc' => $bcc,
                'subject' => $subject,
                'body' => $body,
                'error_message' => $error_message,
                'status' => $status,
                'sent_at' => $sent_at,
                'resent_times' =>$resent_times
            ]);

            if(!$insertedId){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.model_create'
                ]);
            }
            return $this->respond([
                'status' => true,
                'message' => 'success.model_create'
            ], 201);
        } catch (\Throwable $th) {
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
            //xac thuc
            $auth = $this->jwtService->authenticateUser();

            if(!$auth['status']){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.auth'
                ], 403);
            }

            $user = (array) $auth['user_info'];
            $data = (array) $user['data'];
            $roleId = $data['role_id'];

            if(!isUser($roleId)){
                return $this->respond([
                    'status' => false,
                    'message' => 'err.auth.role'
                ], 403);
            }

            $total = $this->model->countAll();//dem tat ca sl email

            $email_histories = $this->model->find($id);//tim theo id
            if(!$email_histories){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.not_found'
                ], 404);
            }
            
            $resource = new EmailHistoriesResources($email_histories);
            return $this->respond([
                'status' => true,
                'data' => $resource,
            ]);
        
        }
        catch(\Throwable $th)
        {
            return $this->respond([
                'status' => false,
                'message' => 'An error occurred during processing. Please try again later.',
                'file' => $th->getFile(),
                'line' => $th->getLine()
            ],500);
        }
    }

    public function update($id = null)
    {
        try {
            //auth
            $auth = $this->jwtService->authenticateUser();

            if(!$auth['status']){
                return $this->respond([
                'status' => false,
                'message' => lang('error.no_authorize')
                ], 403);
            }

            $user = (array)$auth['user_info'];
            $data = (array)$user['data'];
            $roleId = $data['role_id'];
            //check role
             if (!isUser($roleId)) {
                return $this->respond([
                'status' => false,
                'message' => lang('error.no authorize')
                ], 403);
            }
            //tim email by id
            $emailId = $this->model->find($id);

            if (!$emailId) {
                return $this->respond([
                'status' => false,
                'message' => 'Common.error.not_found'
                ], 404);
            }
            //lay json body
            $request = $this->request->getJSON(true);
            //du lieu nhap validate
            $rules = CreateEmailHistoriesRequest::rules();
            $messages = CreateEmailHistoriesRequest::messages();

            if(!$this->validateData($request, $rules, $messages)){
                 return $this->respond([
                    'status' => false,
                    'errors' => $this->validator->getErrors(),
                ]);
            }
            $this->model->update($id,[
                'code' => $request['code'],
                'recipient' => $request['recipient'],
                'cc' => $request['cc'],
                'bcc' => $request['bcc'],
                'subject' => $request['subject'],
                'body' => $request['body'],
                'error_message' => $request['error_message'],
                'status' => $request['status'],
                'sent_at' => $request['sent_at'],
                'resent_times' => $request['resent_times'],
            ]);
           
            return $this->respond([
                'status' => true,
                'message' => 'success.model update'
            ], 201);
        } catch (\Throwable $th) {
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
            //xac thuc
            $auth = $this->jwtService->authenticateUser();

            if(!$auth['status']){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.auth'
                ], 403);
            }
            //lay role
            $user = (array) $auth['user_info'];
            $data = (array) $user['data'];
            $roleId = $data['role_id'];

            if(!isUser($roleId)){
                return $this->respond([
                    'status' => false,
                    'message' => 'err.auth.role'
                ], 403);
            }

            $email_histories = $this->model->find($id);//tim theo id
            if(!$email_histories){
                return $this->respond([
                    'status' => false,
                    'message' => 'error.not_found'
                ], 404);
            }

            $this->model->delete($id);
            
            return $this->respond([
                'status' => true,
                'message' => 'success.model_delete'
            ]);
        
        }
        catch(\Throwable $th)
        {
            return $this->respond([
                'status' => false,
                'message' => 'An error occurred during processing. Please try again later.',
                'file' => $th->getFile(),
                'line' => $th->getLine()
            ],500);
        }
    }
}
