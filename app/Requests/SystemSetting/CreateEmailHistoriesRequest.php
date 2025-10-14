<?php

namespace App\Requests\SystemSetting;
//validate
class CreateEmailHistoriesRequest
{
    public static function rules(): array
    {
        return [
            'code'          => 'required|max_length[100]',
            'recipient'     => 'required|max_length[255]',
            'cc'            => 'permit_empty|max_length[255]',
            'bcc'           => 'permit_empty|max_length[255]',
            'subject'       => 'required|max_length[255]',
            'body'          => 'required',
            'error_message' => 'permit_empty',
            'status'        => 'required|integer|in_list[0,1]',
            'sent_at'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'resent_times'  => 'permit_empty|integer',
            'deleted_at'    => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'updated_at'    => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'created_at'    => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];
    }

    public static function messages(): array
    {
        return [
            'code.required'      => 'Code is required.',
            'code.max_length'    => 'Code cannot exceed 100 characters.',

            'recipient.required' => 'Recipient is required.',
            'recipient.max_length' => 'Recipient cannot exceed 255 characters.',

            'cc.max_length'      => 'CC cannot exceed 255 characters.',
            'bcc.max_length'     => 'BCC cannot exceed 255 characters.',

            'subject.required'   => 'Subject is required.',
            'subject.max_length' => 'Subject cannot exceed 255 characters.',

            'body.required'      => 'Body is required.',

            'status.required'    => 'Status is required.',
            'status.integer'     => 'Status must be an integer (0 or 1).',
            'status.in_list'     => 'Status must be either 0 (failed) or 1 (success).',

            'sent_at.valid_date' => 'Sent at must be a valid datetime (Y-m-d H:i:s).',
            'deleted_at.valid_date' => 'Deleted at must be a valid datetime (Y-m-d H:i:s).',
            'updated_at.valid_date' => 'Updated at must be a valid datetime (Y-m-d H:i:s).',
            'created_at.valid_date' => 'Created at must be a valid datetime (Y-m-d H:i:s).',

            'resent_times.integer' => 'Resent times must be an integer.',
        ];
    }
 
}
