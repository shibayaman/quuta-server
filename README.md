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

## 開発メンバーへの大切なお知らせ
このレポジトリの前身について。

2020/7/21に外部サービスのAPIキーが直書きされたプルリクが飛んできてしまい、履歴は消せそうになかったのでレポジトリごと葬って作り直したのがこちらのレポジトリです。

上記日付以前のプルリクは全て消し飛んでいます。(プルリクをrebaseしてたのでdevelopブランチにひたすらコミットが伸びてるだけの状態です)

良い機会なので2020/7/21以降、運用方針も考え直したいなって思います。新しい方針は別途通知します。