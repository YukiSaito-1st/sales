<?php

// 設定ファイルと関数ファイルを読み込む
require_once('config.php');
require_once('functions.php');

// DBに接続
$dbh = connectDb();

$input_year = $_GET["year"];
$input_branch = $_GET["branch"];
$input_staff = $_GET["staff"];

// 売上関連データの取得
$result_sales = salesData($input_year, $input_branch, $input_staff, $dbh);

// 支店名の取得
$result_branches = getBranches($dbh);

// 従業員名の取得
$result_staffs = getStaffs($dbh);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>売上一覧</title>
    <meta name="description" content="売上一覧の課題に挑戦しました。">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<h1>売上一覧</h1>

<div class="flame">
    <form action="" method="get">
        <div class="item">
            <label class="label">年
            <input class="inputs" type="number" name="year" value="<?php if( !empty($_GET['year']) ){ echo $_GET['year']; } ?>">
            </label>

            <label class="label">支店
            <select class="inputs" name="branch">
                <option value="" <?php if(empty($_GET['branch'])) echo 'selected'; ?>></option>
            <?php foreach ($result_branches as $branch) : ?>
                <option value=<?= $branch['id'] ?>
                    <?= $_GET['branch'] == $branch['id'] ? 'selected' : ''; ?>><?= $branch['name'] ?></option>
            <?php endforeach; ?>
            </select>
            </label>

            <label class="label">従業員
            <select class="inputs" name="staff">
                <option value="" <?php if(empty($_GET['staff'])) echo 'selected'; ?>></option>
            <?php foreach ($result_staffs as $staff) : ?> 
                <option value=<?= $staff['id'] ?> 
                    <?= $_GET['staff'] == $staff['id'] ? 'selected' : '' ; ?>><?= $staff['name'] ?></option>
            <?php endforeach; ?>
            </select>
            </label>
        </div>

        <div class="btn">
            <input type="submit" value="検索">
        </div>
    </form>
</div>

<table class="report">
    <thead>
        <tr>
            <th>年</th>
            <th>月</th>
            <th>支店</th>
            <th>従業員</th>
            <th>売上</th>
        </tr>
    </thead>

    <?php foreach ($result_sales as $sale) :
    $sale['sale'] = number_format($sale['sale']);
    echo <<< EOM
    <tbody>
        <tr>
            <td>{$sale['year']}</td>
            <td>{$sale['month']}</td>
            <td>{$sale['branch_name']}</td>
            <td>{$sale['staff_name']}</td>
            <td>{$sale['sale']}</td>
        <tr>
    </tbody>
    EOM;
        $sum_sales += $sale['sale']; // 売上額の合計
    endforeach; ?>
</table>
<h2>合計:<?= number_format($sum_sales) ?>万円</h2>
</body>
</html>