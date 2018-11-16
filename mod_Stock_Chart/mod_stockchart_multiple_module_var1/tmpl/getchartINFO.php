<?php

    $symbol = $_GET['q'];

    $con = mysql_connect("localhost","root","");
    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }

    // Connect DB
    mysql_select_db("joomla", $con);

    $Data = [];
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
    $Data['Dates'] = $dates;
    $Data['Closes'] = $closeValues;
    $Data['Volumes'] = $volumes;
    $Data['Lows'] = $lows;
    $Data['Highs'] = $highs;
    $Data['Opens'] = $opens;
    $Data['symbol'] = $symbol;

    // close DB
    mysql_close($con);
    //$Data = json_encode($Data);

    echo json_encode($Data);

?>