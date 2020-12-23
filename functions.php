<?php

// データベース接続
function connectDb()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理
function h($s)
{
    // ENT_QUOTES	シングルクオートとダブルクオートを共に変換する。
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

// 売上関連データの取得
function salesData($input_year, $input_branch, $input_staff, $dbh)
{
    $sql = "SELECT t1.*, t3.name AS branch_name, t2.name AS staff_name FROM sales AS t1
            INNER JOIN staffs AS t2 ON t2.id = t1.staff_id
            INNER JOIN branches AS t3 on t2.branch_id = t3.id WHERE 1";
    if(!empty($input_year)) {
        $sql .= " AND t1.year = :year";
    }
    if(!empty($input_branch)) {
        $sql .= " AND t3.id = $input_branch";
    }
    if(!empty($input_staff)) {
        $sql .= " AND t2.id = $input_staff";
    }

    $sql .= " ORDER BY t1.year, t1.month, t3.id, t2.id;";
    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);
    // プリペアドステートメントの実行
    if(!empty($input_year)) {
        $stmt->bindValue(":year", $input_year, PDO::PARAM_INT);
    }
    $stmt->execute();
    // 結果の受け取り
    $result_sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result_sales;
}

// 支店名の取得
function getBranches($dbh)
{
    $sql = "SELECT * FROM branches";
    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    // 結果の受け取り
    $result_branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result_branches;
}

// 従業員名の取得
function getStaffs($dbh)
{
    $sql = "SELECT * FROM staffs";
    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    // 結果の受け取り
    $result_staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result_staffs;
}