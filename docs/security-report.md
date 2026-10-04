# セキュリティテストレポート：ログイン機能付き掲示板

- 実施日：2026-10-04
- 対象：`/comments`（掲示板）と Breeze の認証機能（登録・ログイン・ログアウト）
- 環境：ローカル Docker（nginx 1.31.6 / PHP 8.2.33 / Laravel 12 / MySQL 8.0）、`APP_ENV=local`
- 方法
  1. 自動テスト：`tests/Feature/CommentTest.php`（13件）を `php artisan test` で実行
  2. 手動テスト：curl で `http://localhost` に実際のリクエストを送って確認
  3. 設定レビュー：`.env`、`config/session.php`、nginx 設定、ルート定義
  4. 依存パッケージ：`composer audit`、`npm audit`

## 1. 結果サマリー

| # | 項目 | 結果 | 重要度 |
|---|------|------|--------|
| 1 | XSS（投稿本文・ユーザー名） | ✅ 問題なし | - |
| 2 | SQL インジェクション | ✅ 問題なし | - |
| 3 | CSRF | ✅ 問題なし | - |
| 4 | 認証（未ログインでの投稿・削除） | ✅ 問題なし | - |
| 5 | 認可（他人の投稿の削除 / IDOR） | ✅ 問題なし | - |
| 6 | Mass Assignment（`user_id` のなりすまし） | ✅ 問題なし | - |
| 7 | 入力値検証 | ✅ 問題なし | - |
| 8 | 投稿の連投制限 | ✅ 問題なし | - |
| 9 | ログインの総当たり対策 | ✅ 問題なし | - |
| 10 | セッション管理（固定化・ログアウト・Cookie 属性） | ✅ 問題なし（本番で要設定あり） | 低 |
| 11 | 機密ファイルの公開 | ✅ 問題なし | - |
| 12 | エラー情報の漏えい（`APP_DEBUG`） | ⚠️ 要対応（本番時） | 中 |
| 13 | バージョン情報の露出 | ⚠️ 要対応 | 低 |
| 14 | セキュリティヘッダー | ⚠️ 未設定 | 低 |
| 15 | 依存パッケージの脆弱性 | ⚠️ 要更新 | 低〜中 |

アプリのコード（掲示板の実装）には脆弱性は見つかりませんでした。指摘事項はすべてサーバー・環境設定と依存パッケージに関するものです。

## 2. テスト詳細

### 2.1 XSS

| 入力 | 結果 |
|------|------|
| 本文 `<script>alert('xss')</script>` | `&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;` として出力され、実行されない |
| 本文 `"><svg onload=alert(1)>` | エスケープされ、生の `<svg` タグは出力されない |
| ユーザー名 `<img src=x onerror=alert(1)>` で登録 | 掲示板・ナビゲーションともエスケープされる |

**理由**：Blade の `{{ }}` で出力しており、`htmlspecialchars` が自動適用される。`{!! !!}` は使っていない。

### 2.2 SQL インジェクション

| 入力 | 結果 |
|------|------|
| 本文 `' OR '1'='1'; DROP TABLE comments; --` | 文字列のまま保存・表示され、テーブルは無事 |
| `GET /comments?page=1' OR 1=1--` | 200（ページ番号として無視される） |
| `DELETE /comments/1 OR 1=1` | 該当なし（404/419）。他の投稿は削除されない |

**理由**：Eloquent（プリペアドステートメント）のみを使い、生の SQL を組み立てていない。ルートモデルバインディングで ID を解決している。

### 2.3 CSRF

| リクエスト | 結果 |
|------------|------|
| トークンなしで `POST /comments` | 419 |
| 偽のトークン（`_token=AAAA`） | 419 |
| ログアウト前のトークンをログアウト後に再利用 | 419 |

自動テストでも、CSRF ミドルウェアを有効にして 419 になることを確認した（`test_post_without_csrf_token_is_rejected`）。Cookie は `SameSite=Lax`。

### 2.4 認証・認可

| リクエスト | 期待 | 結果 |
|------------|------|------|
| ゲストが `POST /comments` | ログインへリダイレクト | 302 → `/login` ✅ |
| ゲストが `DELETE /comments/{id}` | ログインへリダイレクト | 302 → `/login` ✅ |
| ユーザーB が A の投稿を削除 | 403 | 403 ✅（投稿は残る） |
| ユーザーA が自分の投稿を削除 | 成功 | 302、削除される ✅ |
| 存在しない ID を削除 | 404 | 404 ✅ |
| 削除ボタンの表示 | 投稿者本人のみ | 本人のみ ✅ |

**理由**：`auth` ミドルウェア、`CommentPolicy::delete`（`$user->id === $comment->user_id`）、view の `@can`。

### 2.5 Mass Assignment

ユーザーB が `body=...&user_id=1` を送信 → 投稿者は B のまま保存された。

**理由**：`user_id` を `$fillable` に含めず、`$request->user()->comments()->create($validated)` で保存しているため、リクエストの `user_id` は無視される。

### 2.6 入力値検証

| 入力 | 結果 |
|------|------|
| 空文字 | バリデーションエラー |
| 1001 文字 | バリデーションエラー |
| `body[]=x`（配列） | バリデーションエラー（`string` ルール）、500 にならない |

### 2.7 連投・総当たり対策

| 項目 | 結果 |
|------|------|
| 投稿を 15 秒で 12 回送信 | 1〜10 回目は 302、11 回目以降は **429**（`throttle:10,1`） |
| 間違ったパスワードで 6 回ログイン | 「Too many login attempts. Please try again in 41 seconds.」でロック（Breeze 標準：メールアドレス + IP ごとに 5 回まで） |

### 2.8 セッション管理

| 項目 | 結果 |
|------|------|
| ログイン前後でセッション ID が変わるか | 変わる ✅（セッション固定化攻撃への対策） |
| 登録後・ログアウト後にセッション ID が変わるか | 変わる ✅ |
| ログアウト後に古い CSRF トークンが使えるか | 使えない（419）✅ |
| セッション Cookie の属性 | `HttpOnly; SameSite=Lax`、`Secure` なし |

`Secure` がないのはローカルが HTTP のため。**本番（HTTPS）では `SESSION_SECURE_COOKIE=true` を設定すること。** パスワードは bcrypt（`BCRYPT_ROUNDS=12`）で保存されている。

### 2.9 機密ファイルの公開

| パス | 結果 |
|------|------|
| `/.env` | 404 |
| `/../.env`、`/%2e%2e/.env` | 400 |
| `/index.php/../.env` | 404 |
| `/.git/config` | 404 |
| `/composer.json`、`/phpinfo.php`、`/.user.ini` | 404 |
| `/storage/logs/laravel.log` | 403 |

nginx のドキュメントルートが `public/` なので、`.env` などのファイルには外部から届かない。`/storage/...` の 403 は Laravel 12 の `storage.local` ルート（署名付き URL が必要）が返したもので、ログの内容は返っていない。このルートが配信するのは `storage/app/private` だけで、ログは含まれない。`.env` が Git の管理対象外（`.gitignore`）であることも確認した。

## 3. 指摘事項と対策

### 【中】3.1 `APP_DEBUG=true`

- **現状**：`.env` が `APP_ENV=local` / `APP_DEBUG=true`。今回のテストで出た 404・419・バリデーションエラーではスタックトレースは表示されなかったが、想定外の例外（500）が起きると、デバッグ画面にファイルパス、SQL、環境変数などが表示される。
- **リスク**：この設定のまま公開すると、DB のパスワードや `APP_KEY` が漏れるおそれがある。
- **対策**：本番では `APP_ENV=production`、`APP_DEBUG=false` にする。

### 【低】3.2 バージョン情報の露出

- **現状**：レスポンスヘッダーに `Server: nginx/1.31.6` と `X-Powered-By: PHP/8.2.33` が含まれる。
- **リスク**：既知の脆弱性を持つバージョンを狙った攻撃の手がかりになる。
- **対策**：nginx に `server_tokens off;`、PHP に `expose_php = Off` を設定する。

### 【低】3.3 セキュリティヘッダーが未設定

- **現状**：`X-Frame-Options`、`X-Content-Type-Options`、`Content-Security-Policy`、`Referrer-Policy`、`Strict-Transport-Security` がない。
- **リスク**：クリックジャッキング（iframe に埋め込んで操作させる攻撃）を防げない。XSS が見つかった場合の被害も軽減できない。
- **対策**：nginx に以下を追加する（CSP は Vite や Alpine.js の動作を確認しながら段階的に導入する）。

  ```nginx
  add_header X-Frame-Options "SAMEORIGIN" always;
  add_header X-Content-Type-Options "nosniff" always;
  add_header Referrer-Policy "strict-origin-when-cross-origin" always;
  # HTTPS 化後
  # add_header Strict-Transport-Security "max-age=31536000" always;
  ```

### 【低〜中】3.4 依存パッケージの脆弱性

| ツール | 結果 |
|--------|------|
| `composer audit` | `league/commonmark` 2.10.1 に 2 件（high：GFM テーブル拡張の DoS、medium：`DisallowedRawHtml` のすり抜け） |
| `npm audit` | high 5 件（`braces` など）。すべてビルド時にしか使わない devDependencies。`npm audit --omit=dev` では 0 件 |

- **影響**：このアプリは Markdown 変換（`Str::markdown` など）を使っていないため、`league/commonmark` の脆弱性を突かれる経路は今のところない。npm の脆弱性も本番の実行時には関係しない。
- **対策**：`composer update league/commonmark` で修正版に更新する。npm は `npm audit fix` を試す（`--force` は Tailwind のメジャーアップデートになるため別途検討）。

## 4. 実装済みのセキュリティ対策

| 対策 | 実装箇所 |
|------|----------|
| 投稿・削除に認証を必須にする | `routes/web.php`（`auth` ミドルウェア） |
| 投稿者本人のみ削除できる | `app/Policies/CommentPolicy.php`、`CommentController::destroy` の `Gate::authorize` |
| `user_id` をサーバー側で設定する | `CommentController::store`、`Comment::$fillable` |
| 入力値検証 | `required|string|max:1000` |
| 出力のエスケープ | `resources/views/comments/index.blade.php`（`{{ }}`） |
| CSRF 対策 | 各フォームの `@csrf` |
| 連投制限 | `throttle:10,1` |
| ログイン試行制限・セッション再生成 | Breeze 標準（`LoginRequest`、`AuthenticatedSessionController`） |

## 5. 自動テスト

`tests/Feature/CommentTest.php` に 13 件。全体（38 件）が成功している。

```
docker compose exec app php artisan test --filter=CommentTest
```

| テスト | 確認内容 |
|--------|----------|
| `test_guest_can_view_board` | ゲストは閲覧できるが、投稿フォームは表示されない |
| `test_guest_cannot_post` | ゲストの投稿はログインへリダイレクトされる |
| `test_user_can_post` | ログインユーザーは投稿できる |
| `test_user_id_cannot_be_spoofed` | `user_id` を送っても投稿者は変わらない |
| `test_body_is_validated` | 空・1001 文字は拒否される |
| `test_body_is_html_escaped` | 本文の `<script>` がエスケープされる |
| `test_user_name_is_html_escaped` | ユーザー名の HTML がエスケープされる |
| `test_owner_can_delete_comment` | 本人は削除できる |
| `test_other_user_cannot_delete_comment` | 他人は 403 |
| `test_guest_cannot_delete_comment` | ゲストはログインへリダイレクトされる |
| `test_delete_button_shown_only_to_owner` | 削除ボタンは本人にのみ表示される |
| `test_posting_is_rate_limited` | 11 回目の投稿は 429 |
| `test_post_without_csrf_token_is_rejected` | CSRF トークンなしは 419 |

## 6. 今回の範囲外

- HTTPS 環境でのテスト（ローカルは HTTP のみ）
- パスワードリセットとメール認証の流れ（メール送信環境なし）
- 掲示板以外の画面（posts / events / reservations）
- 自動脆弱性スキャナ（OWASP ZAP など）による網羅的なスキャン
