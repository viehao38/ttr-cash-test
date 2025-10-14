<?php

namespace App\Resources;

use JsonSerializable;

class EmailHistoriesResources implements JsonSerializable
{
    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
         return [
            'id'            => $this->resource['id'],
            'code'          => $this->resource['code'],
            'recipient'     => $this->resource['recipient'],
            'cc'            => $this->resource['cc'] ?? null,
            'bcc'           => $this->resource['bcc'] ?? null,
            'subject'       => $this->resource['subject'],
            'body'          => $this->resource['body'],
            'error_message' => $this->resource['error_message'] ?? null,
            'status'        => (int) $this->resource['status'],
            'sent_at'       => $this->resource['sent_at'] ?? null,
            'resent_times'  => (int) $this->resource['resent_times'],
            'created_at'    => $this->resource['created_at'] ?? null,
            'updated_at'    => $this->resource['updated_at'] ?? null,
            'deleted_at'    => $this->resource['deleted_at'] ?? null,
        ];
    }

    public static function collection(array $items): array
    {
        return array_map(fn($item) => (new static($item))->toArray(), $items);
    }
}

//chuyển đổi dữ liệu từ Model sang JSON trả về client.