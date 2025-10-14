git clone ''
create database my_ci4 on mysql

# Tạo bảng

php spark migrate

# data fake

php spark db:seed EmailHistoriesSeeder
php spark db:seed UserSeeder
php spark db:seed SystemSettingsSeeder

login get token
post: http://localhost:8080/login/token
{
"email": "email@gmail.com",
"password": "123456"
}

# qua tab headers

key : Authorization
value: gắn token đã tạo khi login

# system-settings

get: http://localhost:8080/admin/system-settings
post:
http://localhost:8080/admin/system-settings
{
"meta_key": "setting_4",
"meta_value": "haoviet",
"label": "GPA",
"field_type": "select",
"options": "ok"
}

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
