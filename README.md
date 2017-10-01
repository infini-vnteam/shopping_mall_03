#shopping_mall_03
shopping_mall_03 (Training)

##Cấu hình elastic search

###1. Cài elastic search tại đây
https://www.elastic.co/guide/en/elasticsearch/reference/current/deb.html

###2. Cài package "elasticsearch/elasticsearch"
composer update
hoặc
composer require "elasticsearch/elasticsearch"

###3. Cấu hình hosts cho elastic search
Sửa file /config/autoload/local.php

Tham khảo local.php.dist

###4. Khởi tạo index, đẩy dữ liệu lên elastic search lần đầu tiên
Chạy link: http://localhost:8080/admin/initElasticSearch

*Yêu cầu quyền admin

##Sửa lỗi
###Error : method zend\view\helper\headtitle::__tostring() must not throw an exception, caught zend\i18n\exception\extensionnotloadedexception
Instal intl extension: php5.6-intl
###composer : composer install; composer update; composer development-enable; composer dump-autoload
