<?php
session_start();
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if (isset($_POST['body']) && !empty($_SESSION['login_user_id'])) {
  // POSTで送られてくるフォームパラメータ body がある かつ ログイン状態 の場合

  $image_filename = null;
  if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    // アップロードされた画像がある場合
    if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1) {
      // アップロードされたものが画像ではなかった場合
      header("HTTP/1.1 302 Found");
      header("Location: ./bbs.php");
      return;
    }
    // 元のファイル名から拡張子を取得
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    // 新しいファイル名を決める。他の投稿の画像ファイルと重複しないように時間+乱数で決める。
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }

  // insertする
  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (user_id, body, image_filename) VALUES (:user_id, :body, :image_filename);");
  $insert_sth->execute([
    ':user_id' => $_SESSION['login_user_id'], // ログインしている会員情報の主キー
    ':body' => $_POST['body'],
    ':image_filename' => $image_filename,
  ]);
  // 処理が終わったらリダイレクトする
  header("HTTP/1.1 302 Found");
  header("Location: ./bbs.php");
  return;
}
?>

<?php if(empty($_SESSION['login_user_id'])): ?>
  投稿するには<a href="/login.php">ログイン</a>が必要です。
<?php else: ?>
</div><a href="/icon.php">アイコン画像の設定はこちら</a>。</div>
<form method="POST" action="./bbs.php" enctype="multipart/form-data">
  <textarea name="body"></textarea>
  <div style="margin: 1em 0;">
    <input type="file" accept="image/*" name="image" id="imageInput">
  </div>
  <button type="submit">送信</button>
</form>
<?php endif; ?>

<hr>

<dl id="entryTemplate" style="display: none; margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
  <dt>番号</dt>
  <dd data-role="entryIdArea"></dd>
  <dt>投稿者</dt>
  <dd>
    <a href="" data-role="entryUserAnchor">
      <img data-role="entryUserIconImage"
        style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
      <span data-role="entryUserNameArea"></span>
    </a>
  </dd>
  <dt>日時</dt>
  <dd data-role="entryCreatedAtArea"></dd>
  <dt>内容</dt>
  <dd data-role="entryBodyArea">
  </dd>
</dl>
<div id="entriesRenderArea"></div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const entryTemplate = document.getElementById('entryTemplate');
  const entriesRenderArea = document.getElementById('entriesRenderArea');
  let offset = 0; // 初期オフセット
  const limit = 10; // 1回のロードで取得する件数
  let loading = false; // データロード中フラグ

  const loadEntries = () => {
    if (loading) return; // 現在ロード中ならスキップ
    loading = true;

    const request = new XMLHttpRequest();
    request.onload = (event) => {
      const response = event.target.response;
      response.entries.forEach((entry) => {
        const entryCopied = entryTemplate.cloneNode(true);
        entryCopied.style.display = 'block';
        entryCopied.querySelector('[data-role="entryIdArea"]').innerText = entry.id.toString();
        entryCopied.querySelector('[data-role="entryUserNameArea"]').innerText = entry.user_name;
        entryCopied.querySelector('[data-role="entryCreatedAtArea"]').innerText = entry.created_at;
        entryCopied.querySelector('[data-role="entryBodyArea"]').innerHTML = entry.body;
        if (entry.user_icon_file_url) {
          entryCopied.querySelector('[data-role="entryUserIconImage"]').src = entry.user_icon_file_url;
        } else {
          entryCopied.querySelector('[data-role="entryUserIconImage"]').style.display = 'none';
        }
        if (entry.image_file_url) {
          const imageElement = new Image();
          imageElement.src = entry.image_file_url;
          imageElement.style.display = 'block';
          imageElement.style.marginTop = '1em';
          imageElement.style.maxHeight = '300px';
          imageElement.style.maxWidth = '300px';
          entryCopied.querySelector('[data-role="entryBodyArea"]').appendChild(imageElement);
        }
        entriesRenderArea.appendChild(entryCopied);
      });
      offset += limit; // 次のオフセットを更新
      loading = false; // ロード完了
    };
    request.open('GET', `/bbs_json.php?offset=${offset}&limit=${limit}`, true);
    request.responseType = 'json';
    request.send();
  };

  // 最初のデータロード
  loadEntries();

  // スクロールイベントを監視
  window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
      loadEntries(); // ページ下部に近づいたらロード
    }
  });

  const imageInput = document.getElementById("imageInput");
  imageInput.addEventListener("change", () => {
    if (imageInput.files.length < 1) {
      return;
    }
    if (imageInput.files[0].size > 5 * 1024 * 1024) {
      alert("5MB以下のファイルを選択してください。");
      imageInput.value = "";
    }
  });
});
</script>





