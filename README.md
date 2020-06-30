# quuta-server

学校のチーム制作品"quuta"のバックエンド(製作中)　　

**重要**:クローンしたらまず

```
$ git config core.hooksPath .githooks
```
を実行してください。コミット時にformatterが作動します。

それからそれから
```
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```
を実行してください。