# simple-symfony-xdebug

## 環境構築方法
ビルド 

```
docker compose build --no-cache
```

起動

```
docker compose up --force-recreate
OR
docker-compose up -d
```

Symfonyプロジェクト作成
```shell
docker-compose exec -it app bash

# Git Safty Setting
git config --global --add safe.directory /var/www/html && git config --global user.email "test@gmail.com"

# /var/www/html配下をクリアする
find /var/www/html/ -type f -delete && find /var/www/html/ -mindepth 1 -type d -delete

# new project
# symfony new --webapp --dir=/var/www/html my_project --version=5.4.34
symfony new --webapp --dir=/var/www/html my_project
OR 
# もしバージョン指定が効かないなら、以下方法で
composer create-project symfony/website-skeleton:"5.4.*" html/

# wsl2の場合権限つける
chmod 777 -R /var/www/html

# DocumentRootを変更する
vim ~/.vimrc
set encoding=utf-8

vi /etc/apache2/sites-available/000-default.conf
/var/www/html -> /var/www/html/publicへ変更

# Install Symfony Components
composer require symfony/apache-pack
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle

# 注意
# Root composer.json requires symfony/asset-mapper 5.4.*, found symfony/asset-mapper[v6.3.0
# バージョン古いの方、上記conflictするため、composer.jsonに以下修正必要
"symfony/asset-mapper": "5.4.*" -> "symfony/asset-mapper": "5.4.* || ^6.3"

# apache2再起動
service apache2 restart
# 上記うまく起動できない場合は、docker compose stop -> start
```

データベース接続(.env)注意
```text
DATABASE_URL="mysql://test_user:test_pass@mysql:3306/test_db_name?serverVersion=10.3.39-MariaDB&charset=utf8mb4"
```

## 動作確認
1. WEBページを確認
```
http://localhost:8080/
```
phpinfo()が表示されていること<br><br>

2. phpmyadminの確認
```
http://localhost:8180/
```
ログイン済みの状態で画面表示されていること<br><br>

3. メール送信テストと確認
<br>appコンテナにアクセスした後、mail送信のスクリプトを実行する
```
docker-compose exec app bash

root@f761b2f53458:/var/www# php -r "mail('aaa@local', 'テストタイトル', 'テスト本文', 'From: bbb@local');";
```
メール確認
```
http://localhost:8025/
```
inboxに、テストメールが表示されていること<br>

### Xdebug設定と確認（VSCodeの例）
1. VSCodeの拡張Install
```
Dev Containers
PHP Debug
```

2. VSCodeのlaunch.json設定
```json
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9004
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9004
        }
    ]
}
```

3. 動作確認
```php
<?php
$now = date('Y-m-d H:i:s');
phpinfo();
```

```html
http://localhost をアクセス
```

### Xdebug設定と確認（PHPStormの例）
CLI Interpreterを追加するのみ