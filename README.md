# web技術概論前期最終課題

## コンテナ起動

```docker compose up ``` 

## テーブル作成

```docker compose exec mysql mysql kyototech

### 以下のSQL文を入れる
```CREATE TABLE karipost (
    id INT AUTO_INCREMENT PRIMARY KEY,
    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); ```







```java:title
int i = 0; //コード
```






