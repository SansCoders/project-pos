@echo off

start chrome http://127.0.0.1:8000

set project_dir=D:/xampp/htdocs/toko

cd %project_dir%

set PATH=%PATH%;%project_dir%\vendor\bin

php artisan serve --host=127.0.0.1 --port=8000