# Laravel Docker App
Docker ComposeでLaravel開発環境の構築

1. コンテナの起動
docker comose up -d

2. Laravelのインストール
docker compose exec app bash
→コンテナ内のLinuxターミナルが開く
composer create-project laravel/laravel .
→Laravelのインストールを実行するコマンド
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
→権限の設定。Lavaralがログファイル等を書き込めるようにする
exit
→コンテナから出る

3. .envのDB設定の編集
　DB_CONNECTION=mysql
　DB_HOST=db
　DB_PORT=3306
　DB_DATABASE=laravel
　DB_USERNAME=root
　DB_PASSWORD=secret

4. マイグレーションの実行
docker compose exec app php artisan migrate

5. 動作確認
 Laravel: http://localhost
 phpMyAdmin: http://localhost:8080（サーバ: `db`）