[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bc12cfdd7c184834924d522374317ed1)](https://www.codacy.com/app/ckmd/Serpo-time-counter?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ckmd/Serpo-time-counter&amp;utm_campaign=Badge_Grade)

# Serpo-time-counter :bar_chart:
# Project Based on
- Laravel 5.5.* Framework
- AdminLTE Template
- XLSX Writer/Loader Library
# How to use
- configure the php.ini (change the memory limit into -1 and file upload size 20MB++)
- run composer update
- Migrate the database using : php artisan migrate
- run the application using : php artisan serve
- open at localhost:8000
# How to Use in Linux Server
- Clone or download file into /var/www/
- if Using HTTPS{
    - set webserver directory in /etc/apache/sites-available/https.conf into /var/www/::Project Name::/public
  }
- run chmod -R 777 ::Project Name::/ outside ::Project Name::
- run composer update inside ::Project Name::
