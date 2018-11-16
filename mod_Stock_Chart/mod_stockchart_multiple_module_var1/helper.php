<?php 
/**
	*Helper class for Stock Chart Module
	*package 			Joomla Stock Charts
	*subpackage			Front-end Module
	*license			GNU/GPL, see LICENSE.php
	*author 			Huy Tran
*/

class ModStockChartHelper{
	public static function getHistoricalData($symbol){
		if($symbol==""){
                  echo "Data is not available";
            }
            else{
                  $url = 'https://query2.finance.yahoo.com/v8/finance/chart/'.$symbol.'?formatted=true&crumb=yF0zujWA5Pe&lang=en-US&region=US&interval=1d&events=div%7Csplit&range=1y&corsDomain=finance.yahoo.com';
                  $json = file_get_contents($url);

                  $json = json_decode($json);
                  // var_dump($json);
                  $charts = $json->chart->result[0];
                  $timestamps = $charts->timestamp;
                  $indicators = $charts->indicators;
                  $closePrice = $indicators->quote[0]->close;
                  $volume = $indicators->quote[0]->volume;
                  $low = $indicators->quote[0]->low;
                  $high = $indicators->quote[0]->high;
                  $open = $indicators->quote[0]->open;
                  date_default_timezone_set("America/Los_Angeles");

                  $symbol = str_replace(".", "_", $symbol);

                  $con = mysql_connect("localhost","root","");
                  if (!$con)
                  {
                        die('Could not connect: ' . mysql_error());
                  }

                  // Create database
                  if (mysql_query("CREATE DATABASE joomla",$con))
                  {
                        // Connect DB
                        mysql_select_db("joomla", $con);

                        // Create table
                        $sql = "CREATE TABLE ".$symbol."           
                        (
                              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                              dates INT(12) NOT NULL,
                              closes FLOAT(12) NOT NULL,
                              volumes INT(12) NOT NULL,
                              lows FLOAT(12) NOT NULL,
                              highs FLOAT(12) NOT NULL,
                              opens FLOAT(12) NOT NULL
                        );";
              
                        mysql_query($sql, $con);
                  
                        // insert info
                        for ($i=0; $i < count($timestamps)  ; $i++) { 

                              $sql = "INSERT INTO ".$symbol." (dates, closes, volumes, lows, highs, opens) 
                                    VALUES ($timestamps[$i],  floor($closePrice[$i] * 100) / 100, $volume[$i], $low[$i], $high[$i], $open[$i]);";
                              mysql_query($sql, $con);
                        }
                  }
                  else
                  {     
                        // Connect DB
                        mysql_select_db("joomla", $con);

                        // Delete exist table (for update)
                        $sql = "DROP table ".$symbol.";"; 
                        mysql_query($sql, $con);

                        // Create table
                        $sql = "CREATE TABLE ".$symbol."           
                        (
                              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                              dates INT(12) NOT NULL,
                              closes FLOAT(12) NOT NULL,
                              volumes INT(12) NOT NULL,
                              lows FLOAT(12) NOT NULL,
                              highs FLOAT(12) NOT NULL,
                              opens FLOAT(12) NOT NULL
                        );";
                        mysql_query($sql, $con);
            
                        // insert info
                        for ($i=0; $i < count($timestamps)  ; $i++) { 

                              $sql = "INSERT INTO ".$symbol." (dates, closes, volumes, lows, highs, opens) 
                                    VALUES ($timestamps[$i],  floor($closePrice[$i] * 100) / 100, $volume[$i], $low[$i], $high[$i], $open[$i]);";
                              mysql_query($sql, $con);
                        }
                  }

                  $historicalData = [];
                  $historicalData['symbol'] = $symbol;
                  $dates = [];
                  $closeValues = [];
                  $volumes = [];
                  $lows = [];
                  $highs = [];
                  $opens = [];
                      
                  $result = mysql_query("SELECT * FROM ".$symbol);
                  while($row = mysql_fetch_assoc($result)) {
                        array_push($dates, $row["dates"]);
                        array_push($closeValues, $row["closes"]);
                        array_push($volumes, $row["volumes"]);
                        array_push($lows, $row["lows"]);
                        array_push($highs, $row["highs"]);
                        array_push($opens, $row["opens"]);
                  }
                  $historicalData['Dates'] = $dates;
                  $historicalData['Closes'] = $closeValues;
                  $historicalData['Volumes'] = $volumes;
                  $historicalData['Lows'] = $lows;
                  $historicalData['Highs'] = $highs;
                  $historicalData['Opens'] = $opens;

                  // close DB
                  mysql_close($con);

                  $historicalData = json_encode($historicalData);
                  //echo $historicalData;
                  return $historicalData;
            }
      }
}

class ModStockChartHelper2{
      public static function getHistoricalData2($Bcolor){
            if($Bcolor==""){
                  echo "Data is not available";
            }else{
                  $BcolorData = [];
                  $BcolorData['Bcolor'] = $Bcolor;

                  $BcolorData = json_encode($BcolorData);
                  return $BcolorData;
            }
      }
}

class ModStockChartHelper3{
      public static function getHistoricalData3($Hcolor){
            if($Hcolor==""){
                  echo "Data is not available";
            }else{
                  $HcolorData = [];
                  $HcolorData['Hcolor'] = $Hcolor;

                  $HcolorData = json_encode($HcolorData);
                  return $HcolorData;
            }
      }
}
?>