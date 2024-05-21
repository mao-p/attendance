# attendance
1.プロジェクト名
#Atte
(images/2024-05-19 index.png)

## 概要
企業の為の勤怠管理。人事評価の為

## 機能一覧
・ログイン機能
・出退勤、休憩打刻機能
・日付別勤怠閲覧機能

## テーブル設計
(images/2024-05-20 table2.png)
(images/2024-05-20 table.png)

## ER図
(images/atte.png)

## 実行環境
php7.3
laravel8.75

## 環境構築
使用したソフトウェア
・PHP
・MySQL
・Composer
・Git

データベースの設定
.envファイル
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

マイグレーション
php artisan migrate

シーディング
php artisan db:seed

