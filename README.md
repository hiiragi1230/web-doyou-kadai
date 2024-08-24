# web技術概論前期最終課題

## コンテナ起動

```docker compose up ``` 

## テーブル作成

```docker compose exec mysql mysql kyototech```

### 以下のSQL文を入れる

```sql
CREATE TABLE karipost (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 
```

## localhost/enshu2.php に接続

## 加点要素
- 授業でサンプルとして作った「hogehoge」ではなく、適切な名前のテーブルを作って実装する（これができたら+5点）
- 投稿それぞれに自動で連番（ID）を付与し、各投稿に表示し、レスアンカー機能が使えるように (これができたら+15点)
- レスアンカー機能と共存させつつ、ページングを実装する（これができたら+10点）

