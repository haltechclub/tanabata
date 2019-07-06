<?php
/**
 * Created by PhpStorm.
 * User: tsukasamurata
 * Date: 2019/07/06
 * Time: 20:26
 */

require_once("config.php");


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
    echo json_encode($posts, JSON_UNESCAPED_UNICODE);
} else {

    // POST
    $id = $_POST["id"];
    $user_id = $_POST["user_id"];
    $body = $_POST["body"];
    $name = $_POST["name"];
    $color = $_POST["color"];
    $sql = "insert into posts(id, user_id, body, name, color) VALUES ('$id', '$user_id', '$body', '$name', '$color');";
    $res = mysqli_query($link, $sql);

// DB切断
    mysqli_close($link);

// レスポンス
    header("Content-Type: application/json; charset=utf-8");
    header('HTTP/1.1 201 Created');
    echo json_encode([], JSON_UNESCAPED_UNICODE);
}