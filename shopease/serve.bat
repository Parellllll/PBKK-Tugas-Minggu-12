@echo off
echo Menjalankan Tugas 12 ShopEase CRUD & Katalog pada http://localhost:8082 ...
C:\xampp\php\php.exe -d extension=intl -d extension=gd -d extension=sqlite3 -S localhost:8082 -t public vendor\codeigniter4\framework\system\rewrite.php

