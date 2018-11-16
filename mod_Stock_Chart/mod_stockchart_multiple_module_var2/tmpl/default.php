<?php
	// No direct access
defined('_JEXEC') or die; 

	error_reporting(E_ALL);
	ini_set('display_errors','On');
 
//init Joomla Framework
    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

    $doc = JFactory::getDocument();
    $doc->addStyleSheet(JURI::root().'modules/mod_stockchart/css/style.css');

	$doc->addScript(JURI::root().'modules/mod_stockchart/js/amcharts.js');
	$doc->addScript(JURI::root().'modules/mod_stockchart/js/serial.js');
	$doc->addScript(JURI::root().'modules/mod_stockchart/js/amstock.js');
	
	$doc->addScript(JURI::root().'modules/mod_stockchart/js/custom.js');

	$doc->addStyleSheet(JURI::root().'modules/mod_stockchart/css/custom.css');
	

    $mainframe = JFactory::getApplication('site');

	//////////////// Joomla DB Connection ///////////////////
	$db = JFactory::getDBO();
	/////////////////////////////////////////////////////////
	$symbol = json_decode($historicalData)->symbol;
    $color1 = json_decode($BcolorData)->Bcolor;
    $color2 = json_decode($HcolorData)->Hcolor;
?>

<div style="position: relative;">
 <div class="btn-group">
        <button class="button" id="b1_<?php echo $symbol;?>">View 10 days Chart</button>
        <button class="button" id="b2_<?php echo $symbol;?>">View 1 month Chart</button>
        <button class="button" id="b3_<?php echo $symbol;?>">View 3 months Chart</button>
        <button class="button" id="b4_<?php echo $symbol;?>">View 1 year Chart</button>
    </div>

    <br><br><br>

    <div class="date_input" id="chart_input">
        <input type="date" id="Date1_<?php echo $symbol;?>" max="" min="">
         ~ 
        <input type="date" id="Date2_<?php echo $symbol;?>" min="" max="">
    </div>

    <canvas id="canvas_<?php echo $symbol;?>" data-id="<?php echo $symbol;?>" width="1200" height="630" onmouseover="getID(this);"></canvas>

    <svg id="svg_<?php echo $symbol;?>" width="1200" height="60" viewBox="0 0 1200 60" preserveAspectRatio="xMinYMin meet">
        <line x1 = "70pt" y1="25pt" x2="770pt" y2="25pt" stroke= "black" stroke-width="3" />
        <rect id="scroll1_<?php echo $symbol;?>" x="80" y="15" width="20" height="40" style="fill:<?php echo $color1;?>;" />
        <rect id="scroll2_<?php echo $symbol;?>" x="1010" y="15" width="20" height="40" style="fill:<?php echo $color2;?>;" />
    </svg>

    <br><br><br>

    <div id="select_date">
    Lookup Date : 
        <select id="month_choose_<?php echo $symbol;?>" size="1">
            <option value="Jan">Jan</option>
            <option value="Feb">Feb</option>
            <option value="Mar">Mar</option>
            <option value="Apr">Apr</option>
            <option value="May">May</option>
            <option value="Jun">Jun</option>
            <option value="Jul">Jul</option>
            <option value="Aug">Aug</option>
            <option value="Sep">Sep</option>
            <option value="Oct">Oct</option>
            <option value="Nov">Nov</option>
            <option value="Dec">Dec</option>
        </select>

        <select id="day_choose_<?php echo $symbol;?>" size="1">
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
        </select>

        <select id="year_choose_<?php echo $symbol;?>" size="1">
        </select>

        <button id="b5_<?php echo $symbol;?>" class="button" onclick="choose_dateINFO();">Look up</button>
        <br> <br>
        <table border="1">
            <tr>
                <td id="datecolum_<?php echo $symbol;?>"></td>
                <td id="highcolum_<?php echo $symbol;?>"></td>
                <td id="opencolum_<?php echo $symbol;?>"></td>
            </tr>
            <tr>
                <td id="volumecolum_<?php echo $symbol;?>"></td>
                <td id="lowcolum_<?php echo $symbol;?>"></td>
                <td id="closecolum_<?php echo $symbol;?>"></td>
            </tr>
        </table>
    </div>

    <p id = "demo"> </p>
    
    <script type="text/javascript">

        var symbol, yLabel = 'Close', zLabel = 'Volume';
        var X, Y, volumes, lows, highs, opens;
        var Bcolor;

        //convert to array
        var obj = JSON.parse('<?php echo $historicalData; ?>');
        symbol = obj.symbol;
        X = obj.Dates;
        Y = obj.Closes;
        volumes = obj.Volumes;
        lows = obj.Lows;
        highs = obj.Highs;
        opens = obj.Opens;

        function showChart(str) {
            if (str == "") {
                document.getElementById("demo").innerHTML = "";
                return;
            } else { 
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                      obj = JSON.parse(this.responseText);

                      symbol = obj.symbol;
                      X = obj.Dates;
                      Y = obj.Closes;
                      volumes = obj.Volumes;
                      lows = obj.Lows;
                      highs = obj.Highs;
                      opens = obj.Opens;

                      //scroll slider defualt value
                      Sdate_term = 920 / X.length;

                      // canvas id reset
                      canvas = document.getElementById("canvas_"+symbol+"");
                      myContext = canvas.getContext("2d");

                      //button id reset
                      b1 = document.getElementById("b1_"+symbol+"");
                      b2 = document.getElementById("b2_"+symbol+"");
                      b3 = document.getElementById("b3_"+symbol+"");
                      b4 = document.getElementById("b4_"+symbol+"");
                      b5 = document.getElementById("b5_"+symbol+"");
                    }
                };
                xmlhttp.open("GET","getchartINFO?q="+str,true);
                xmlhttp.send();
            }
        }

        // get id than load DB
        function getID(ID) {
            var id_value = ID.getAttribute('data-id');
            showChart(id_value);
        }


        // chart backgroundcolor
        var Bobj = JSON.parse('<?php echo $BcolorData; ?>');
        Bcolor = Bobj.Bcolor;

        // button hover event -> backgroundcolor
        var Hobj = JSON.parse('<?php echo $HcolorData; ?>');
        Hcolor = Hobj.Hcolor;

        // Draw Chart code start
        var sizeM2 = 0;  //Y coordinate info (height)
        var button_checked = 0;  //which button was clicked
        var index_info1 = 0, index_info2 = 0, index_info3 = 0, index_info4 = 0; 
            //date index info(depend on the button)
        var Dindex1=0, Dindex2=0; 
        var index = 0, range_over = 0, XsizeValue = 0;
        var x_size=0, dotgap=0;

        //scroll slider defualt value
        var Sdate_term = 920 / X.length;

        // Stoke Chart Canvas
        var canvas = document.getElementById("canvas_"+symbol+"");
        var myContext = canvas.getContext("2d");

        // Chart Date Max, Min defined
        var date1 = document.getElementById("Date1_"+symbol+"").value;
        var date2 = document.getElementById("Date2_"+symbol+"").value;
        document.getElementById("Date1_"+symbol+"").setAttribute("max", date2);
        document.getElementById("Date2_"+symbol+"").setAttribute("min", date1);
        var date_MinMax = new Date(X[0] * 1000);
        var date_MinMax2 = date_MinMax.toISOString();
        document.getElementById("Date1_"+symbol+"").setAttribute("min", date_MinMax2.substring(0, 10));
        date_MinMax = new Date(X[X.length-1] * 1000);
        date_MinMax2 = date_MinMax.toISOString();
        document.getElementById("Date2_"+symbol+"").setAttribute("max", date_MinMax2.substring(0, 10));

        // Add option (year) - Under Stoke Chart Part
        var objSel = document.getElementById("year_choose_"+symbol+"");
        var date_TEXT = "";
        for (var i=0; i<X.length; i++) {

            var date = new Date(X[i] * 1000);
            var date2 = date.toGMTString();
            var date_text = date2.substring(12, 16);

            if(date_text != date_TEXT) {
                var objOption = document.createElement("option"); 
                objOption.text = date_text;
                objOption.value = date_text;
                objSel.options.add(objOption); 

                date_TEXT = date_text;
            }      
        }

        // view all information (latest info) - Under Chart Part
        var choose_date = new Date(X[X.length-1] * 1000);
        var choose_date2 = choose_date.toGMTString();
        var choose_month_info = document.getElementById("month_choose_"+symbol+"").value = choose_date2.substring(8,11);
        var choose_day_info = document.getElementById("day_choose_"+symbol+"").value = choose_date2.substring(5,7);
        var choose_year_info = document.getElementById("year_choose_"+symbol+"").value = choose_date2.substring(12, 16);

        var datecolum = document.getElementById("datecolum_"+symbol+"");
        datecolum.innerHTML = "Date : "+choose_date2.substring(5, 16);
        var highcolum = document.getElementById("highcolum_"+symbol+"");
        highcolum.innerHTML = "High : "+highs[highs.length-1];
        var opencolum = document.getElementById("opencolum_"+symbol+"");
        opencolum.innerHTML = "Open : "+opens[opens.length-1];
        var volumecolum = document.getElementById("volumecolum_"+symbol+"");
        volumecolum.innerHTML = "Volume : "+volumes[volumes.length-1];
        var lowcolum = document.getElementById("lowcolum_"+symbol+"");
        lowcolum.innerHTML = "Low : "+lows[lows.length-1];
        var closecolum = document.getElementById("closecolum_"+symbol+"");
        closecolum.innerHTML = "Close : "+ Y[Y.length-1];

        // Button color defined
        var b1 = document.getElementById("b1_"+symbol+"");
        b1.style.backgroundColor = Bcolor;
        var b2 = document.getElementById("b2_"+symbol+"");
        b2.style.backgroundColor = Bcolor;
        var b3 = document.getElementById("b3_"+symbol+"");
        b3.style.backgroundColor = Bcolor;
        var b4 = document.getElementById("b4_"+symbol+"");
        b4.style.backgroundColor = Bcolor;
        var b5 = document.getElementById("b5_"+symbol+"");
        b5.style.backgroundColor = Bcolor;

        // button color change depend on hover event
        b1.onmouseover = function() {
            b1.style.backgroundColor = Hcolor;
        } 
        b2.onmouseover = function() {
            b2.style.backgroundColor = Hcolor;
        } 
        b3.onmouseover = function() {
            b3.style.backgroundColor = Hcolor;
        } 
        b4.onmouseover = function() {
            b4.style.backgroundColor = Hcolor;
        } 
        b5.onmouseover = function() {
            b5.style.backgroundColor = Hcolor;
        } 
        b1.onmouseout = function() {
            b1.style.backgroundColor = Bcolor;
        } 
        b2.onmouseout = function() {
            b2.style.backgroundColor = Bcolor;
        } 
        b3.onmouseout = function() {
            b3.style.backgroundColor = Bcolor;
        } 
        b4.onmouseout = function() {
            b4.style.backgroundColor = Bcolor;
        } 
        b5.onmouseout = function() {
            b5.style.backgroundColor = Bcolor;
        } 

        // mouse click event
        b1.onclick = function() {
            DrawChart("10days_"+symbol+"");
        } 
        b2.onclick = function() {
            DrawChart("1month_"+symbol+"");
        } 
        b3.onclick = function() {
            DrawChart("3months_"+symbol+"");
        } 
        b4.onclick = function() {
            DrawChart("1year_"+symbol+"");
        } 
     

        //create the gradation color - Chart Part
        var grd = myContext.createLinearGradient(0, 0, 0, 800);
        grd.addColorStop(0.7, Bcolor);
        grd.addColorStop(0.2, "#FAFAFA");

        var max_y = 0;
        for(var v =0; v<Y.length; v++) {
            if(Y[v] > max_y) {
                max_y = Y[v];
            }
        }

        DrawChart("1year_"+symbol+"");  // default function

        // Choose date value (Chart Part) - focus
        document.getElementById("Date1_"+symbol+"").addEventListener("focusout", function() {
            var Date_info = new Date(X[0] * 1000);
            var info = Date_info.toISOString();
            var Date_info2 = new Date(X[X.length-1] * 1000);
            var info2 = Date_info2.toISOString();

            date11 = document.getElementById("Date1_"+symbol+"").value;
            date12 = document.getElementById("Date2_"+symbol+"").value;

            // lesser than first info
            if((date11.substring(0,4)*1 < info.substring(0, 4)*1) || (date11.substring(0,4)*1 == info.substring(0, 4)*1 && date11.substring(5,7)*1 < info.substring(5, 7)*1) || (date11.substring(0,4)*1 == info.substring(0, 4)*1 && date11.substring(5,7)*1 == info.substring(5, 7)*1 && date11.substring(8,10)*1 < info.substring(8, 10)*1) || (date11.substring(0,4)*1 > date2.substring(0, 4)*1) || (date11.substring(0,4)*1 == date2.substring(0, 4)*1 && date11.substring(5,7)*1 > date2.substring(5, 7)*1) || (date11.substring(0,4)*1 == date2.substring(0, 4)*1 && date11.substring(5,7)*1 == date2.substring(5, 7)*1 && date11.substring(8,10)*1 > date2.substring(8, 10)*1)){
                document.getElementById("Date1_"+symbol+"").value = date1;
                document.getElementById("Date2_"+symbol+"").value = date2;
                date1 = date11;
                date2 = date12;
                document.getElementById("Date1_"+symbol+"").setAttribute("max", date2);
                document.getElementById("Date2_"+symbol+"").setAttribute("min", date1);
            }
            else {
                document.getElementById("Date1_"+symbol+"").value = date11;
                document.getElementById("Date2_"+symbol+"").setAttribute("min", date11);
                date1 = date11;
                date2 = date12;
                DrawChart("DateView_"+symbol+"");
            }

        }, false);
        
        document.getElementById("Date2_"+symbol+"").addEventListener("focusout", function() {
            var Date_info = new Date(X[0] * 1000);
            var info = Date_info.toISOString();
            var Date_info2 = new Date(X[X.length-1] * 1000);
            var info2 = Date_info2.toISOString();

            var date11 = document.getElementById("Date1_"+symbol+"").value;
            var date12 = document.getElementById("Date2_"+symbol+"").value;

            // bigger than last i(
            if((date12.substring(0,4)*1 > info2.substring(0, 4)*1) || (date12.substring(0,4)*1 == info2.substring(0, 4)*1 && date12.substring(5,7)*1 > info2.substring(5, 7)*1) || (date12.substring(0,4)*1 == info2.substring(0, 4)*1 && date12.substring(5,7)*1 == info2.substring(5, 7)*1 && date12.substring(8,10)*1 > info2.substring(8, 10)*1) || (date12.substring(0,4)*1 < date1.substring(0, 4)*1) || (date12.substring(0,4)*1 == date1.substring(0, 4)*1 && date12.substring(5,7)*1 < date1.substring(5, 7)*1) || (date12.substring(0,4)*1 == date1.substring(0, 4)*1 && date12.substring(5,7)*1 == date1.substring(5, 7)*1 && date12.substring(8,10)*1 < date1.substring(8, 10)*1) ) {
                document.getElementById("Date1_"+symbol+"").value = date1;
                document.getElementById("Date2_"+symbol+"").value = date2;
                date1 = date11;
                date2 = date12;
                document.getElementById("Date1_"+symbol+"").setAttribute("max", date2);
                document.getElementById("Date2_"+symbol+"").setAttribute("min", date1);
            }
            else {
                document.getElementById("Date2_"+symbol+"").value = date12;
                document.getElementById("Date1_"+symbol+"").setAttribute("max", date12);
                date1 = date11;
                date2 = date12;
                DrawChart("DateView_"+symbol+"");
            }

        }, false);

        // main cavas(chart) mouse position
        function getMousePos(canvas, evt) {
            var rect = canvas.getBoundingClientRect();
            return {
                x: (evt.clientX - rect.left) * (canvas.width / rect.width),
                y: (evt.clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        // scroll slider mouse position
        var svg = document.getElementById("svg_"+symbol+"");
        function getMousePos2(canvas, evt) {
            var rect = canvas.getBoundingClientRect();
            var svg_width = document.getElementById("svg_"+symbol+"").getAttribute("width");
            var svg_height = document.getElementById("svg_"+symbol+"").getAttribute("height");
       
            return {
                x: (evt.clientX - rect.left) * (svg_width / rect.width),
                y: (evt.clientY - rect.top) * (svg_height / rect.height)
            };
        }

        // Use this function to match the form of the date value equally.
        function leadingZeros(n, digits) {
            var zero = '';
            n = n.toString();

            if (n.length < digits) {
                for (var i = 0; i < digits - n.length; i++)
                    zero += '0';
            }
            
            return zero + n;
        }

        //hover event part
        canvas.addEventListener('mousemove', function(evt) {
            var mousePos = getMousePos(canvas, evt);
            var x = mousePos.x; //mouse x coordinate
            var y = mousePos.y; //mouse y coordinate
            var r=350/sizeM2;  // intermediate process to create y coordinate values
            var value = (380-y)/r; // real Y coordinate value
    
            myContext.clearRect(0, 0, canvas.width, canvas.height); //clear the canvas

            // define the button info that which button is clicked
            if(button_checked == 1) {
                DrawChart("10days_"+symbol+"");
            }
            else if(button_checked == 2) {
                DrawChart("1month_"+symbol+"");
            }
            else if(button_checked == 3) {
                DrawChart("3months_"+symbol+"");
            }
            else if(button_checked == 4) {
                DrawChart("1year_"+symbol+"");
            }
            else if(button_checked == 5) {
                date1 = document.getElementById("Date1_"+symbol+"").value;
                date2 = document.getElementById("Date2_"+symbol+"").value;
                document.getElementById("Date1_"+symbol+"").setAttribute("max", date2);
                document.getElementById("Date2_"+symbol+"").setAttribute("min", date1);
                DrawChart("DateView_"+symbol+"");
            }
            else if(button_checked == 6) {
                DrawChart("ScrollView_"+symbol+"");
            }

            // hover range define (area chart + stick chart)
            if(x >= 100 && y >= 30 && y <= 570 && x<1010) {

                // create the red line
                myContext.lineWidth = 0.6;
                myContext.strokeStyle = "black";
                myContext.beginPath();
                myContext.moveTo(x, 30);
                myContext.lineTo(x, 570);
                myContext.closePath();
                myContext.stroke();

                // if mouse isn't on the area chart, close value don't create.
                myContext.font = 'italic 13pt Calibri';
                if(value >= 0) {
                    myContext.lineWidth = 0.6;
                    myContext.beginPath();
                    myContext.moveTo(100, y);
                    myContext.lineTo(1000, y);
                    myContext.closePath();
                    myContext.stroke();

                    // show close value 
                    myContext.lineWidth = 1;
                    myContext.beginPath();
                    myContext.moveTo(x+3, y);
                    myContext.lineTo(x+10, y-7);
                    myContext.lineTo(x+10, y-15);
                    myContext.lineTo(x+100, y-15);
                    myContext.lineTo(x+100, y+15);
                    myContext.lineTo(x+10, y+15);
                    myContext.lineTo(x+10, y+7);
                    myContext.lineTo(x+3, y);
                    myContext.closePath();
                    myContext.fillStyle = "#FFFFFF";
                    myContext.fill();
                    myContext.stroke();
                    myContext.strokeText("CLose : "+ value.toFixed(2), x+11, y+5);
                }
        
                var XX = 100;
                var indexI = 0; //volumes, close, date array index info

                // if mouse exist on the range(chart value), show the date value.
                for(indexI=index; indexI<range_over; indexI++) {
                    if(x >= XX-7 && x <= XX+7) {
                        myContext.lineWidth = 1;
                        myContext.beginPath();
                        myContext.moveTo(x-50, 600);
                        myContext.lineTo(x+105, 600);
                        myContext.lineTo(x+105, 625);
                        myContext.lineTo(x-50, 625);
                        myContext.lineTo(x-50, 600);
                        myContext.closePath();
                        myContext.fillStyle = "#FFFFFF";
                        myContext.fill();
                        myContext.stroke();

                        var d = new Date(X[indexI]*1000);                        
                        var Dvalue = d.toGMTString();
                        myContext.strokeText("Date : " + Dvalue.substring(5, 16), x-40, 615);
                    }
                    XX += XsizeValue;

                    // Unlike other charts, 1 year chart is shown on a weekly basis.
                    // so it's index incremental value is a little bit different.
                    if(button_checked == 4) {
                        indexI += 5;
                    }
                    else if(button_checked == 5 || button_checked == 6) {
                        if(range_over - index > 40) {
                            indexI += 5;
                        }
                    }
                }

                // show volumes value
                myContext.lineWidth = 1;
                myContext.beginPath();
                myContext.moveTo(x-50, 395);
                myContext.lineTo(x+95, 395);
                myContext.lineTo(x+95, 420);
                myContext.lineTo(x-50, 420);
                myContext.lineTo(x-50, 395);
                myContext.closePath();
                myContext.fillStyle = "#FFFFFF";
                myContext.fill();
                myContext.stroke();

                XX = 100 - XsizeValue/2;
                for(indexI=index; indexI<range_over; indexI++) {
                    if(x < XX+XsizeValue && x >= XX) {
                        break;
                    }
                    XX += XsizeValue;

                    if(button_checked == 4) {
                        indexI += 5;
                    }
                    else if(button_checked == 5 || button_checked == 6) {
                        if(range_over - index > 40) {
                            indexI += 5;
                        }
                    }
                }

                myContext.strokeText("Volume : " + volumes[indexI], x-40, 410);
                myContext.strokeStyle = "black";
            }
        }, false);


    // ** Draw main chart
        function DrawChart(day) {
            
            if(day == "10days_"+symbol+"") {
                button_checked = 1;
                XsizeValue = 900/ (X.length - index - 1);

                // calculate date info
                var today = new Date();
                var t_month = today.getMonth() + 1;
                var t_day = today.getDate() -10;
                var t_year = today.getFullYear();

                if(t_day < 0) {
                    if(t_month == 1 || t_month == 3 || t_month == 5 || t_month == 7 || t_month == 8 || t_month == 10 || t_month == 12 ) {
                        var tmp = 31 + t_day;
                        t_month--;
                        t_day = tmp;
                    }
                    else {
                        var tmp = 30 + t_day;
                        t_month--;
                        t_day = tmp;
                    }
                }

                var today_S = today.getFullYear() + "-" + leadingZeros(t_month, 2) +"-"+ leadingZeros(t_day, 2);

                // find the date before 10days and match the date_data 
                index_info1=0;
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(today_S == s.substring(0,10)) {
                        index_info1 = i;
                        break;
                    }

                }

                // if there is no matching data, most closest value choose.
                if(index_info1 == 0) {
                    for(var i=X.length-1; i>0; i--){
                        var date = new Date(X[i]*1000);
                        var s = date.toISOString();

                        if(t_day < 5) {
                            if(s.substring(8,10) * 1 > leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month-1, 2) *1 && s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1) {
                                index_info1 = i+1;
                                break;
                            }
                            else if(s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1) {
                                index_info1 = i+1;
                                break;
                            }
                        }
                        else {
                            if(s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1) {
                                index_info1 = i+1;
                                break;
                            }
                        }
                    }
                }

                index = index_info1;
                range_over = X.length;
                dotgap = X.length - index;
            }
            else if(day == "1month_"+symbol+"") {
                button_checked = 2;
                XsizeValue = 900/ (X.length - index - 1);

                // decide index (depend on the date value)
                var today = new Date();
                var t_month = today.getMonth();
                if(t_month == 0) {
                    t_month = 12;
                }
                var t_day = today.getDate();
                var today_S = today.getFullYear() + "-" + leadingZeros(t_month, 2) +"-"+ leadingZeros(t_day, 2);

                index_info2=0;
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(today_S == s.substring(0,10)) {
                        index_info2 = i;
                        break;
                    }

                }

                if(index_info2 == 0) {
                    for(var i=X.length-1; i>0; i--){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                        if(t_day < 5) {
                            if(s.substring(5,7) * 1 == leadingZeros(t_month-1, 2) *1 && s.substring(8,10) * 1 > leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                            else if(s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                        }
                        else {
                            if(s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                        }
                    }
                }

                index = index_info2;
                range_over = X.length;
                dotgap = X.length - index;
            }
            else if(day == "3months_"+symbol+"") {
                button_checked = 3;
                XsizeValue = 900/ (X.length - index - 1);

                // decide index (depend on the date value)
                var today = new Date();
                var t_year = today.getFullYear();
                var t_month = today.getMonth() - 2;
                if(t_month <= 0) {
                    var tmp = 13 + t_month;
                }

                var t_day = today.getDate();
                var today_S = t_year + "-" + leadingZeros(t_month, 2) +"-"+ leadingZeros(t_day, 2);

                index_info3=0;
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(today_S == s.substring(0,10)) {
                        index_info3 = i;
                        break;
                    }

                }

                if(index_info3 == 0) {
                    for(var i=X.length-1; i>0; i--){
                        var date = new Date(X[i]*1000);
                        var s = date.toISOString();

                        if(t_day < 5) {
                            if(s.substring(5,7) * 1 == leadingZeros(t_month-1, 2) *1 && s.substring(8,10) * 1 > leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                            else if(s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                        }
                        else {
                            if(s.substring(5,7) * 1 == leadingZeros(t_month, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1) {
                                index_info2 = i+1;
                                break;
                            }
                        }
                    }
                }

                index = index_info3;
                range_over = X.length;
                dotgap = X.length - index;
                
            }
            else if(day == "1year_"+symbol+"") {
                button_checked = 4;

                // decide index (depend on the date value)
                var today = new Date();
                var t_year = today.getFullYear() - 1;
                var t_month = today.getMonth() + 1;
                var t_day = today.getDate();
                var today_S = t_year + "-" + leadingZeros(t_month, 2) +"-"+ leadingZeros(t_day, 2);

                index_info4=0;
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(today_S == s.substring(0,10)) {
                        index_info4 = i;
                        break;
                    }

                }

                if(index_info4 == 0) {
                    for(var i=X.length-1; i>0; i--){
                        var date = new Date(X[i]*1000);
                        var s = date.toISOString();

                        if(t_day < 5) {
                            if(s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1 && s.substring(8,10) * 1 > leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month-1, 2)) {
                                index_info4 = i+1;
                                break;
                            }
                            else if(s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month, 2)) {
                                index_info4 = i+1;
                                break;
                            }
                        }
                        else {
                            if(s.substring(0,4) * 1 == leadingZeros(t_year, 2) *1 && s.substring(8,10) * 1 < leadingZeros(t_day, 2) *1 && s.substring(5,7) * 1 == leadingZeros(t_month, 2)) {
                                index_info4 = i+1;
                                break;
                            }
                        }
                    }
                }

                index = index_info4;
                range_over = X.length;

                x_size=0;
                for(var i=index_info4; i<X.length; i+=6) {
                    x_size++;
                }
                dotgap = x_size;
                XsizeValue = 900/(x_size-1);
            }
            else if(day == "DateView_"+symbol+"") {
                button_checked = 5;
                
                // decide index value depend on the date
                Dindex1=0, Dindex2=0;
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(date1 == s.substring(0,10)) {
                        Dindex1 = i;
                        break;
                    }

                }

                // if there is no matching data, most closest value choose.
                if(Dindex1 == 0) {
                    for(var i=X.length-1; i>0; i--){
                        var date = new Date(X[i]*1000);
                        var s = date.toISOString();

                       if(date1.substring(8,10)*1 < 5) {
                            if(s.substring(8,10) * 1 > date1.substring(8,10) * 1 && s.substring(5,7) * 1 == date1.substring(5,7) * 1 -1 && s.substring(0,4) * 1 == date1.substring(0,4)) {
                                Dindex1 = i+1;
                                break;
                            }
                            else if(s.substring(8,10) * 1 < date1.substring(8,10) * 1 && s.substring(5,7) * 1 == date1.substring(5,7) * 1 && s.substring(0,4) * 1 == date1.substring(0,4)) {
                                Dindex1 = i+1;
                                break;
                            }
                        }
                        else {
                            if(s.substring(8,10) * 1 < date1.substring(8,10) * 1 && s.substring(5,7) * 1 == date1.substring(5,7) * 1 && s.substring(0,4) * 1 == date1.substring(0,4)) {
                                Dindex1 = i+1;
                                break;
                            }
                        }
                    }
                }

                
                for(var i=0; i<X.length; i++){
                    var date = new Date(X[i]*1000);
                    var s = date.toISOString();

                    if(date2 == s.substring(0,10)) {
                        Dindex2 = i;
                        break;
                    }

                }

                // if there is no matching data, most closest value choose.
                if(Dindex2 == 0) {
                    for(var i=X.length-1; i>0; i--){
                        var date = new Date(X[i]*1000);
                        var s = date.toISOString();

                        if(s.substring(8,10) * 1 < date2.substring(8,10) * 1 && s.substring(0,4) * 1 == date2.substring(0,4) && s.substring(5,7) * 1 == date2.substring(5,7)) {
                                Dindex2 = i+1;
                                break;
                        }
                        else if(s.substring(8,10) * 1 > date2.substring(8,10) * 1 && s.substring(5,7) * 1 < date2.substring(5,7) * 1 && s.substring(0,4) * 1 == date2.substring(0,4)) {
                            Dindex2 = i+1;
                            break;
                        }
                    }
                }

                index = Dindex1;
                range_over = Dindex2+1;

                x_size=0;
                for(var i=Dindex1; i<Dindex2+1; i+=6) {
                    x_size++;
                }
                dotgap = x_size;

                if(Dindex2 - Dindex1 > 40) {
                    XsizeValue = 900/ (dotgap-1);
                }
                else {
                    XsizeValue = 900/ (Dindex2 - Dindex1);
                }


            }
            else if(day == "ScrollView_"+symbol+"") {
                button_checked = 6;
                
                // decide the index depend on the scroll slider' position
                Dindex1=0, Dindex2=0;
                var start_point = 90;
                for(var i=0; i<X.length; i++) {
                    start_point += Sdate_term;

                    var before = document.getElementById("scroll1_"+symbol+"").getAttribute("x");

                    if(before >= start_point && before < start_point+Sdate_term) {
                        Dindex1 = i;
                        break;
                    }
                }

                start_point = 90;
                for(var i=0; i<X.length; i++) {
                    start_point += Sdate_term;

                    var before = document.getElementById("scroll2_"+symbol+"").getAttribute("x");

                    if(before >= start_point && before < start_point+Sdate_term) {
                        Dindex2 = i;
                        break;
                    }
                }

                x_size=0;
                for(var i=Dindex1; i<Dindex2+1; i+=6) {
                    x_size++;
                }
                dotgap = x_size;
                
                if(Dindex2 - Dindex1 > 40) {
                    XsizeValue = 900/ (dotgap-1);
                }
                else {
                    XsizeValue = 900/ (Dindex2 - Dindex1);
                }

                index = Dindex1;
                range_over = Dindex2 + 1;


            }

            // decide date limit
            date1 = document.getElementById("Date1_"+symbol+"").value;
            date2 = document.getElementById("Date2_"+symbol+"").value;
            document.getElementById("Date1_"+symbol+"").setAttribute("max", date2);
            document.getElementById("Date2_"+symbol+"").setAttribute("min", date1);

            // decide scroll slider's position
            var default_term = 90; //slider position (start Point)
            for(var i=0; i<index+1; i++) {
                default_term += Sdate_term;
            }
            document.getElementById("scroll1_"+symbol+"").setAttribute("x", default_term);

            default_term = 90; 
            for(var i=0; i<range_over; i++) {
                default_term += Sdate_term;
            }
            document.getElementById("scroll2_"+symbol+"").setAttribute("x", default_term);

            // draw chart structure
            myContext.strokeStyle = "black";
            myContext.clearRect(0, 0, canvas.width, canvas.height);
            
            myContext.moveTo(100,30);
            myContext.lineTo(100, 380);
            myContext.moveTo(1000, 380);
            myContext.lineTo(100, 380);
            myContext.stroke();

            myContext.font = "9pt Batang";
            myContext.strokeText(symbol, 110, 18);
            myContext.font = "15pt Batang";
            myContext.rotate(Math.PI/2);
            myContext.strokeText(yLabel, 130, -10);
            myContext.rotate(1.5*Math.PI);

            myContext.lineWidth = 1;
            myContext.strokeStyle = Bcolor;
            sizeY = 0; y2 = 380;
            for(var i=0; i<6; i++) {
                myContext.beginPath();
                myContext.moveTo(100, y2);
                myContext.lineTo(1000, y2);
                myContext.stroke();
                myContext.closePath();

                sizeY = 350/5;
                y2 -= sizeY;
            }
            myContext.strokeStyle="black";

            // date info determind
            var date = new Date(X[index]*1000);
            var s = date.toISOString();
            document.getElementById("Date1_"+symbol+"").value = s.substring(0, 10);
            date1 = s.substring(0, 10);
            date = new Date(X[X.length-1]*1000);
            s = date.toISOString();
            document.getElementById("Date2_"+symbol+"").value = s.substring(0, 10);
            date2 = s.substring(0, 10);

            var a_max = 0;
            for(var i=index; i<range_over; i++) {
                if(Y[i] > a_max) {
                    a_max = Y[i];
                }
            }

            // close (Y value)'s range decide
            var sizeY = 0;
            var x2 = 60, y2 = 380;
            var tmp = 0;
            var sizeM1 = a_max/4;
            sizeM2 = parseFloat(a_max) + parseFloat(sizeM1); 

            myContext.font = 'italic 10pt Calibri';
            for(var i=0; i<6; i++) {
                myContext.strokeText(tmp.toFixed(2), x2, y2);

                sizeY = 350/5;
                y2 -= sizeY;
                tmp += sizeM2/5;
            }

            // draw chart line
            var xx = 100, yy = 380, YY = 380, r=350/sizeM2, size = 0;
            myContext.lineWidth = 2;
            myContext.strokeStyle = Bcolor;
            myContext.beginPath();
            myContext.moveTo(xx, yy);
            for(var i=index; i<range_over; i++) {
                myContext.lineTo(xx + size, YY - Y[i]*r);
                myContext.stroke();

                xx = xx + size;
                size = 900/ (dotgap-1);

                if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                    if(Dindex2 - Dindex1 > 40) {
                        size = 900/ (dotgap-1);
                        i += 5;
                    }
                    else {
                        size = 900/(Dindex2 - Dindex1);  //interval of x coordinate
                    }
                }
                else if(day == "1year_"+symbol+"") {
                    i += 5;
                }

                yy = YY - Y[i]*r;
            }

            // fill the color
            myContext.lineTo(xx , 380);
            myContext.lineTo(100, 380);
            myContext.closePath();
            myContext.fillStyle = grd;
            myContext.fill();

            // draw chart dot
            xx = 100, yy = 380, YY = 380, r=350/sizeM2, size = 0;
            var date_info = "";
            var date_num = 0;
            var before_xx = 0;
            for(var i=index; i<range_over; i++) {

                myContext.beginPath();
                myContext.arc(xx + size, YY - Y[i]*r, 2, 0, 2*Math.PI);
                myContext.closePath();
                myContext.fillStyle= Bcolor;
                myContext.fill();
                myContext.stroke();

                var date = new Date(X[i]*1000);
                var s1 = date.toISOString();
                var s = date.toGMTString();

                // too many date data -> just view start date and end date
                myContext.lineWidth = 1;
                
                if(day == "ScrollView_"+symbol+"" || day == "DateView_"+symbol+"") {
                    if(Dindex2 - Dindex1 <= 20 && date_info != s.substring(5, 11) && before_xx + 30 < xx+size-20) {
                        myContext.strokeText(s.substring(5, 11), xx+size-20, 590);
                        date_info = s.substring(5, 11);
                        before_xx = xx+size-20;
                    }
                    else if(Dindex2 - Dindex1 <= 31 && Dindex2 - Dindex1 > 20 && date_num == 6 && before_xx + 30 < xx+size-20) {
                        myContext.strokeText(s.substring(5, 11), xx+size-10, 590);
                        date_info = s.substring(5, 11);
                        date_num = 0;
                        before_xx = xx+size-20;
                    }
                    else if(Dindex2 - Dindex1 > 31 && date_info.substring(3, 7) != s.substring(8, 11) && before_xx + 30 < xx+size-20) {
                        if("Jan" == s.substring(8, 11)) {
                            myContext.strokeText(s.substring(12,16), xx+size-10, 590);
                        }
                        else {
                            myContext.strokeText(s.substring(7, 11), xx+size-10, 590);
                        }
                    
                        date_info = s.substring(5, 11);
                        before_xx = xx+size-20;
                    }
                }
                else if(day == "10days_"+symbol+"" || day == "1month_"+symbol+"" || day == "3months_"+symbol+"" || day == "1year_"+symbol+"") {

                    if(date_info != s1.substring(5, 7) && before_xx + 30 < xx+size-30){
                        myContext.strokeText(s.substring(5, 11), xx+size-20, 590);
                        date_info = s1.substring(5, 7);
                        before_xx = xx+size-20;
                    }
                }

                xx = xx + size;
                size = 900/(dotgap-1);

                if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                    if(Dindex2 - Dindex1 > 40) {
                        size = 900/ (x_size-1);
                        i += 5;
                    }
                    else {
                        size = 900/(Dindex2 - Dindex1);  //interval of x coordinate
                    }
                }
                else if(day == "1year_"+symbol+"") {
                    i += 5;
                }

                if(day == "ScrollView_"+symbol+"") {
                    date_num++;
                }
            }
        
        // ** Draw Stick Chart Part
            myContext.strokeStyle="black";
            
            myContext.beginPath();
            myContext.moveTo(100, 30);
            myContext.lineTo(100, 380);
            myContext.moveTo(100, 380);
            myContext.lineTo(1000, 380);
            myContext.closePath();
            myContext.stroke();

            // stick chart with volums
            myContext.font = "13pt Batang";
            myContext.strokeText(zLabel, 0, 600);

            // decide volume's max value
            var s_max = 0;
            for(var i=index; i<range_over; i++) {
                if(parseInt(volumes[i]) > parseInt(s_max)) {
                    s_max = volumes[i];
                }

                if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                    if(Dindex2 - Dindex1 > 40) {
                        i += 5;
                    }
                }
                else if(day == "1year_"+symbol+"") {
                    i += 5;
                }
            }

            // draw volume's range (Y value)
            var stick_y = 570, s_width = 900/(volumes.length - index-1), stick_x = 100 - s_width/2 - 70;
            if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                if(Dindex2 - Dindex1 > 40) {
                    stick_y = 570, s_width = 900/(dotgap-1)/2, stick_x = 100 - s_width/2 - 70;
                }
                else {
                    stick_y = 570, s_width = 900/(Dindex2 - Dindex1)/2, stick_x = 100 - s_width/2 - 70;
                }

                if(stick_x + 70 < 80) {
                    stick_x = 5;
                }
            }
            else if(day == "1year_"+symbol+"") {
                stick_y = 570, s_width = 900/(dotgap-1), stick_x = 100 - s_width/2 - 70;
            }
            else if(day == "10days_"+symbol+"") {
                stick_y = 570, s_width = 900/(volumes.length - index-1)/4, stick_x = 100 - s_width/2 - 70;
            }
            else if(day == "1month_"+symbol+"") {
                stick_y = 570, s_width = 900/(volumes.length - index-1)/2, stick_x = 100 - s_width/2 - 70;
            }

            var stick_Ysize = s_max/6;
            var sizeS = 0, tmpS=0;
            myContext.font = 'italic 10pt Calibri';
            for(var i=0; i<6; i++) {

                if(tmpS != 0) {
                    myContext.strokeText(tmpS.toFixed(0), stick_x, stick_y);
                    myContext.lineWidth = 0.5;
                    myContext.beginPath();
                    myContext.moveTo(stick_x+70, stick_y);
                    myContext.lineTo(1000, stick_y);
                    myContext.stroke();
                    myContext.closePath();
                    myContext.lineWidth = 1;
                }
                
                sizeS = 170/6;
                stick_y -= sizeS;
                tmpS += stick_Ysize;

            }

            // draw stick chart
            if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                var divide = 2;
                stick_Ysize = 170/s_max;  // define the interval value (stick cahrt height)
                stick_x = 100 - s_width/2;
                if(stick_x < 80) {
                    stick_x = 70;
                    divide = 4;
                }

                if(Dindex2 - Dindex1 > 40) {
                    stick_y = 570, s_width = 900/(dotgap-1)/divide;
                }
                else {
                    stick_y = 570, s_width = 900/(Dindex2 - Dindex1)/divide;
                }
            }
            else if(day == "10days_"+symbol+"") {
                stick_Ysize = 170/s_max;  // define the interval value
                stick_y = 570; s_width = 900/(volumes.length - index-1)/4; stick_x = 100 - s_width/2;
            }
            else if(day == "1month_"+symbol+"") {
                stick_Ysize = 170/s_max;  // define the interval value
                stick_y = 570; s_width = 900/(volumes.length - index-1)/2; stick_x = 100 - s_width/2;
            }
            else if(day == "1year_"+symbol+"") {
                stick_Ysize = 170/s_max;
                stick_y = 570, s_width = 900/(dotgap-1), stick_x = 100 - s_width/2;
            }
            else {
                stick_Ysize = 170/s_max;
                stick_y = 570, s_width = 900/(volumes.length - index-1), stick_x = 100 - s_width/2;
            }

            for(var i=index; i<range_over; i++) {
                myContext.fillStyle = Bcolor;
                myContext.globalAlpha = 1;
                myContext.fillRect(stick_x, parseFloat(stick_y-(volumes[i]*stick_Ysize)), s_width, parseFloat(volumes[i]*stick_Ysize));
                myContext.strokeStyle = Bcolor;
                myContext.lineWidth = 0.5;
                myContext.strokeRect(stick_x, parseFloat(stick_y-(volumes[i]*stick_Ysize)), s_width, parseFloat(volumes[i]*stick_Ysize));
                myContext.lineWidth = 1;

                if(day == "10days_"+symbol+"") {
                    stick_x += s_width*4;
                }
                else if(day == "1month_"+symbol+"") {
                    stick_x += s_width*2;
                }
                else if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                    stick_x += s_width*divide;

                    if(Dindex2 - Dindex1 > 40) {
                        i += 5;
                    }
                }
                else if(day == "1year_"+symbol+"") {
                    i += 5;
                    stick_x += s_width;
                }
                else {
                    stick_x += s_width;
                }
            }

            myContext.lineWidth = 0.5;
            myContext.beginPath();
            myContext.moveTo(100 - s_width/2, 570);
            myContext.lineTo(1000+ s_width/2, 570);
            myContext.closePath();   
            myContext.stroke(); 
            myContext.lineWidth = 1; 

            if(day == "DateView_"+symbol+"" || day == "ScrollView_"+symbol+"") {
                var date = new Date(X[index]*1000);
                var s = date.toISOString();
                document.getElementById("Date1_"+symbol+"").value = s.substring(0, 10);
                date = new Date(X[range_over-1]*1000);
                s = date.toISOString();
                document.getElementById("Date2_"+symbol+"").value = s.substring(0, 10);
            }
        }

    // ** Scroll Slider Part
        // scroll mouse - left
        makeDraggable(document.getElementById('scroll1_'+symbol+''));

        // scroll mouse - right
        makeDraggable2(document.getElementById('scroll2_'+symbol+''));

        // drag slider function - left
        function makeDraggable(element) {
            var berfore2, before3;

            element.onmousedown = function(event) {
                var mousePos = getMousePos2(svg, event);

                document.onmousemove = function(event) {
                    event = event || window.event;
                    mousePos = getMousePos2(svg, event);

                    before2 = element.getAttribute("x");
                    before3 = document.getElementById("scroll2_"+symbol+"").getAttribute("x");
                    
                    if(mousePos.x <= 1010 && mousePos.x >= 90 &&  mousePos.x + 10 < before3) {
                        element.setAttribute("x", mousePos.x);

                        var start_point = 90;
                        for(var i=0; i<X.length; i++) {
                            start_point += Sdate_term;

                            var before = element.getAttribute("x");;

                            if(before >= start_point && before < start_point+Sdate_term) {
                                Sindex1 = i;
                                break;
                            }
                        }
                        DrawChart("ScrollView_"+symbol+"");
                    }
                };

                document.onmouseup = function() {
                    document.onmousemove = null;
                    if(element.releaseCapture) { 
                        element.releaseCapture(); 
                    }
    
                    var start_point = 90;
                    for(var i=0; i<X.length; i++) {
                        start_point += Sdate_term;

                        var before = element.getAttribute("x");

                        if(before >= start_point && before < start_point+Sdate_term) {
                            Sindex1 = i;
                            break;
                        }
                    }
                    button_checked = 5;
                };

                if(element.setCapture) { 
                    element.setCapture(); 
                }
            };

            element.unselectable = "on";
            element.onselectstart = function(){return false};
            element.style.userSelect = element.style.MozUserSelect = "none";
        }

        // drag slider function - right
        function makeDraggable2(element) {
            var berfore2, before3;
            element.onmousedown = function(event) {
                var mousePos = getMousePos2(svg, event);

                document.onmousemove = function(event) {
                    event = event || window.event;
                    mousePos = getMousePos2(svg, event);

                    before2 = element.getAttribute("x");
                    before3 = document.getElementById("scroll1_"+symbol+"").getAttribute("x");
        
                    if(mousePos.x <= 1010 && mousePos.x >= 90 && mousePos.x - 10 > before3) {
                        element.setAttribute("x", mousePos.x);

                        var start_point = 90;
                        for(var i=0; i<X.length; i++) {
                            start_point += Sdate_term;

                            var before = element.getAttribute("x");

                            if(before >= start_point && before < start_point+Sdate_term) {
                                Sindex2 = i;
                                break;
                            }
                        }
                        DrawChart("ScrollView_"+symbol+"");
                    }
                };

                document.onmouseup = function() {
                    document.onmousemove = null;
                    if(element.releaseCapture) { 
                        element.releaseCapture(); 
                    }
                    
                    var start_point = 90;
                    for(var i=0; i<X.length; i++) {
                        start_point += Sdate_term;

                        var before = element.getAttribute("x");

                        if(before >= start_point && before < start_point+Sdate_term) {
                            Sindex2 = i;
                            break;
                        }                        
                    }
                    button_checked = 5;
                };

                if(element.setCapture) { 
                    element.setCapture(); 
                }
            };

            element.unselectable = "on";
            element.onselectstart = function(){return false};
            element.style.userSelect = element.style.MozUserSelect = "none";
        }

    // ** Under Stoke Chart Part
        function choose_dateINFO() {
            
            choose_month_info = document.getElementById("month_choose_"+symbol+"").value; 
            choose_day_info = document.getElementById("day_choose_"+symbol+"").value;
            choose_year_info = document.getElementById("year_choose_"+symbol+"").value;

            var date_value_info = choose_day_info +" "+ choose_month_info +" "+ choose_year_info;

            var signal = 0;
            for(var i=0; i<X.length; i++) {

                var date = new Date(X[i] * 1000);
                var date2 = date.toGMTString();

                if(date_value_info == date2.substring(5, 16)) {
                   
                    document.getElementById("datecolum_"+symbol+"").innerHTML = "Date : "+date2.substring(5, 16);
                    document.getElementById("highcolum_"+symbol+"").innerHTML = "High : "+highs[i];
                    document.getElementById("opencolum_"+symbol+"").innerHTML = "Open : "+opens[i];
                    document.getElementById("volumecolum_"+symbol+"").innerHTML = "Volume : "+volumes[i];
                    document.getElementById("lowcolum_"+symbol+"").innerHTML = "Low : "+lows[i];
                    document.getElementById("closecolum_"+symbol+"").innerHTML = "Close : "+ Y[i];

                    signal++;
                    break;
                }
            }  

            if(signal == 0) {
                
                document.getElementById("datecolum_"+symbol+"").innerHTML = "No information";
                document.getElementById("highcolum_"+symbol+"").innerHTML = "...";
                document.getElementById("opencolum_"+symbol+"").innerHTML = "...";
                document.getElementById("volumecolum_"+symbol+"").innerHTML = "Choose again";
                document.getElementById("lowcolum_"+symbol+"").innerHTML = "...";
                document.getElementById("closecolum_"+symbol+"").innerHTML = "...";
            }          
        }

    </script>