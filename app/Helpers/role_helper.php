<?php

if (!function_exists('isAdmin')) {
    /**
     * Kiểm tra user có phải admin không
     * @param int $roleId
     * @return bool
     */
    function isAdmin($roleId)
    {
        return $roleId == 1; // ví dụ role_id = 1 là admin
    }

    function isUser($roleId)
    {
        return $roleId == 0; // ví dụ role_id = 0 là usser
    }
}
