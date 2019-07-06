<?php
/**
 * Created by PhpStorm.
 * User: tsukasamurata
 * Date: 2019/07/06
 * Time: 20:26
 */

use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;

require_once "vendor/autoload.php";
require_once "config.php" ;


$link = mysqli_connect(HOST, USER, PASSWORD, DB_NAME);

if (!$link) {
    // 終了させているが、本来はエラーページ表示など適切な処理をすべき
    exit;
}
// 文字コード設定
mysqli_set_charset($link, "utf8");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
// SQL実行
    $sql = "select * from posts order by created_at desc;";
    $res = mysqli_query($link, $sql);

    /*
     * $res->fetch_assoc()でSQL実行結果の次の1行を取得できるので$rowに格納する
     * $row[テーブルのカラム名]で値が取得できる
     * $res->fetch_assoc()はこれ以上結果が無い場合NULLを返すのでwhile(NULL)となりループから抜ける
     */
    $posts = [];
    while ($row = $res->fetch_assoc()) {
        $posts[] = $row;
    }

// DB切断
    mysqli_close($link);

// レスポンス
    header("Content-Type: application/json; charset=utf-8");
    header('HTTP/1.1 200 OK');
    echo json_encode($posts);
} else {

    // POST
    $id = $_POST["id"];
    $user_id = $_POST["user_id"];
    $body = isset($_POST["body"]) ? $_POST["body"] : '';
    $name = isset($_POST["name"]) ? $_POST["name"] : '';
    $color = $_POST["color"];
    $background = null;

    if ($_FILES["background"]["error"][0] == 0) {
        $tmp_name = $_FILES["background"]["tmp_name"][0];
        $ext = pathinfo($_FILES["background"]["name"][0], PATHINFO_EXTENSION);
        $imgName = uniqid('', TRUE);

        if (strtolower($ext) === 'png' || strtolower($ext) === 'jpg' || strtolower($ext) === 'gif') {
            // ローカル保存
            if (S3_BUCKET_NAME == '') {
                move_uploaded_file($tmp_name, "data/$imgName." . $ext);
                $background = "http://localhost/api/data/$imgName." . $ext;
            } else {
                // S3保存
                putenv("AWS_ACCESS_KEY_ID=".AWS_ACCESS_KEY_ID);
                putenv("AWS_SECRET_ACCESS_KEY=".AWS_SECRET_ACCESS_KEY);
                putenv("AWS_SESSION_TOKEN=".AWS_SESSION_TOKEN);
                $provider = CredentialProvider::env();
                $s3 = new Aws\S3\S3Client([
                    'version' => 'latest',
                    'region' => 'us-east-1',
                    'credentials' => $credentials
                ]);
                //バケット名を指定
                $bucket = S3_BUCKET_NAME;
                //アップロードするファイルを用意
                $image = fopen($tmp_name,'rb');

                //画像のアップロード
                $result = $s3->putObject([
                    'ACL' => 'public-read',
                    'Bucket' => $bucket,
                    'Key' => "$imgName." . $ext,
                    'Body' => $image,
                    'ContentType' => mime_content_type($tmp_name),
                ]);
                fclose($image);

                //読み取り用のパスを返す
                $background = $result['ObjectURL'];
            }
        }
    }

    $sql = "insert into posts(id, user_id, body, name, color, background) VALUES ('$id', '$user_id', '$body', '$name', '$color', '$background');";
    $res = mysqli_query($link, $sql);

    $sql = "select * from posts where id = '$id' order by created_at desc;";
    $res = mysqli_query($link, $sql);
    $posts = [];
    while ($row = $res->fetch_assoc()) {
        $posts[] = $row;
    }

    mysqli_close($link);

    header("Content-Type: application/json; charset=utf-8");
    header('HTTP/1.1 201 Created');
    echo json_encode($posts[0]);
}