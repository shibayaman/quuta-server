# quuta-server

学校のチーム制作品"quuta"のバックエンド(製作中)　　

## quutaについて
学校の人とチームで作っている飲食店専門のSNSです。
「既存の飲食店レビューサイトってレビューするハードルが高くて見る専門の割合が高いね」という意見からもっと気軽においしい飲食店の共有ができるアプリケーションを作ることになりました。
フロントエンドはiOSとWeb版を開発中です。

## 始め方
```
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan jwt-secret
```
を実行してください。

**クローンして開発する場合**

```
$ git config core.hooksPath .githooks
```
を実行してください。
コミット時にコードが整形されてテストが走ります。

## ドキュメント
```
$ php artisan l5-swagger:generate
```
を実行後に
`/api/docs` にGETを送るとSwagger UIを使ったドキュメントが見れます。
