# Clone project
```bash
git clone https://github.com/viehao38/ttr-cash-test.git
cd ttr-cash-test
# Sử dụng các lệnh
composer install

cp env .env

Cấu hình thông tin database
database.default.hostname = localhost
database.default.database = my_ci4
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi

Truy cập phpMyAdmin tạo database my_ci4

# Tạo bảng
php spark migrate

# data fake
php spark db:seed EmailHistoriesSeeder
php spark db:seed UsersSeeder
php spark db:seed SystemSettingsSeeder

php spark serve

# Cài thư viện JWT
composer require firebase/php-jwt

# Chạy serve
php spark serve


Vào postman tạo 1 colection chọn phương thức Post
lấy email trong database bảng User dữ liệu fake đã tạo trước đó
Mất khẩu mặc định 123456
http://localhost:8080/login/token
{
"email": "email@gmail.com",
"password": "123456" 
}

# qua tab headers gắn token đã tạo vào value 

key : Authorization
value: gắn token đã tạo khi login

đăng nhập vs role admin là 1

# system-settings

get: http://localhost:8080/admin/system-settings

chú ý Post phần headers thêm 
key : Content-Type
value: application/json

post:
http://localhost:8080/admin/system-settings
{
"meta_key": "setting_4",
"meta_value": "haoviet",
"label": "GPA",
"field_type": "select",
"options": "ok"
}

chú ý Put phần headers thêm
key : Content-Type
value: application/json

put:
http://localhost:8080/admin/system-settings/3
{
"meta_key": "setting_8",
"meta_value": "value_11",
"label": "lablala",
"field_type": "select",
"options": "no"
}

delete:
http://localhost:8080/admin/system-settings/2



# email-histories
đăng nhập vs role user là 0 mới dc phép xem phần này
lấy token làm như systems_setting

get: http://localhost:8080/user/email-histories/

post:
http://localhost:8080/user/email-histories/
{
"code": "EMAIL123456",
"recipient": "nguyen@example.com",
"cc": "abc@example.com",
"bcc": "xyz@example.com",
"subject": "Test email",
"body": "Nội dung email thử nghiệm",
"error_message": null,
"status": 1,
"sent_at": "2025-10-14 10:30:00",
"resent_times": 0
}

put:
http://localhost:8080/user/email-histories/3
{
"code": "EMAIL123456",
"recipient": "nguyenhao@example.com",
"cc": null,
"bcc": "xyz@example.com",
"subject": "Test email",
"body": "Nội dung email thử nghiệm",
"error_message": null,
"status": 1,
"sent_at": "2025-10-14 10:30:00",
"resent_times": 222
}

delete: http://localhost:8080/user/email-histories/3
