# web技術概論前期最終課題

# 起動手順

## クローンする
```bash
    git clone git@github.com:hiiragi1230/web-doyou-kadai.git
```

## コンテナ起動

```bash
    docker compose up 
``` 

## テーブル作成

```bash
docker compose exec mysql mysql kyototech
```
### 動かない場合
```
docker start mysql
```

### 以下のSQL文を入れる

```sql

CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `body` TEXT NOT NULL,
    `image_filename` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);


```

## localhost/enshu2.php に接続

## 加点要素
- 授業でサンプルとして作った「hogehoge」ではなく、適切な名前のテーブルを作って実装する（これができたら+5点）
- 投稿それぞれに自動で連番（ID）を付与し、各投稿に表示し、レスアンカー機能が使えるように (これができたら+15点)
- レスアンカー機能と共存させつつ、ページングを実装する（これができたら+10点）

# 後期課題

## 加点要素
- 掲示板を無限スクロール
