# proving-ground
実験場です。
その時々によってデプロイされているアプリが変わります。

## bcidol-ground
### required
- PHP
- PHP extension
  - mbstring
  - pdo-pgsql
- Heroku
- Heroku add-on
  - Heroku Postgres

### prepare
```
./composer.phar install
echo DATABASE_URL=... > .env
```
and make sure `date.timezone` setting of `[date]` section in `php.ini`.

### run on local env
```
php -S 0.0.0.0:8888 -t public
```
and open [http://localhost:8888/](http://localhost:8888/)
