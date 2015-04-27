<?php
require_once "../configs/config.php";
require_once "../controllers/db.php";
$db = Db::get();
$stm = $db->prepare("SELECT * FROM companies");
$stm->execute();
$result = $stm->fetchAll();
foreach($result as $company) {
    $url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20%28%22" . $company['symbol'] . "%22%29&env=store://datatables.org/alltableswithkeys&format=json";
    $db = Db::get();
    $json_file = file_get_contents($url);
    $jfo = json_decode($json_file);
    $symbol = $company['symbol'];
    $companyName = $jfo->query->results->quote->Name;
    $value = $jfo->query->results->quote->Ask; //Ska vara AskRealtime om börsen är öppen, annars bara Ask
    $change = $jfo->query->results->quote->Change;
    $changeInPercent = $jfo->query->results->quote->ChangeinPercent;
    $yearlow = $jfo->query->results->quote->YearLow;
    $yearhigh = $jfo->query->results->quote->YearHigh;
    $chgfromyl = $jfo->query->results->quote->ChangeFromYearLow;
    $chgpercentfromyl = $jfo->query->results->quote->PercentChangeFromYearLow;
    $chgfromyh = $jfo->query->results->quote->ChangeFromYearHigh;
    $chgpcfromyh = $jfo->query->results->quote->PercebtChangeFromYearHigh;
    $chgfiftydays = $jfo->query->results->quote->ChangeFromFiftydayMovingAverage;
    $chgpercentfiftydays = $jfo->query->results->quote->PercentChangeFromFiftydayMovingAverage;
    $stm = $db->prepare("INSERT INTO stocks (symbol, `value`, currency_change, percent_change, year_low, year_high, chgfromyl, chgpercentfromyl, chgfromyh, chgpercentfromyh, chgfiftydays, chgpercentfiftydays, updated)
                      VALUES (:symbol, :value, :currency_change, :percent_change, :yearlow, :yearhigh, :chgfromyl, :chgpercentfromyl, :chgfromyh, :chgpcfromyh, :chgfiftydays, :chgpercentfiftydays, now())");
    $stm->bindParam(':symbol', $symbol, PDO::PARAM_STR);
    $stm->bindParam(':value', $value, PDO::PARAM_STR);
    $stm->bindParam(':currency_change', $change, PDO::PARAM_STR);
    $stm->bindParam(':percent_change', $changeInPercent, PDO::PARAM_STR);
    $stm->bindParam(':yearlow', $yearlow, PDO::PARAM_STR);
    $stm->bindParam(':yearhigh', $yearhigh, PDO::PARAM_STR);
    $stm->bindParam(':chgfromyl', $chgfromyl, PDO::PARAM_STR);
    $stm->bindParam(':chgpercentfromyl', $chgpercentfromyl, PDO::PARAM_STR);
    $stm->bindParam(':chgfromyh', $chgfromyh, PDO::PARAM_STR);
    $stm->bindParam(':chgpcfromyh', $chgpcfromyh, PDO::PARAM_STR);
    $stm->bindParam(':chgfiftydays', $chgfiftydays, PDO::PARAM_STR);
    $stm->bindParam(':chgpercentfiftydays', $chgpercentfiftydays, PDO::PARAM_STR);
    if ($stm->execute()) {
        echo "success!";
    } else {
        echo "failure";
    }
}
?>