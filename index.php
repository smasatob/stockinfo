<?php 
 
header('Content-Type: application/json');
ob_start();
$json = file_get_contents('php://input'); 
$request = json_decode($json, true);
$action = $request["result"]["action"];
$parameters = $request["result"]["parameters"];
 
$metric = $request["result"]["parameters"]["Metrics"];
$stock = $request["result"]["parameters"]["Stocks"];
 
 
$metnum = stockInfo($stock,$metric); 
 
 
    switch ($metric) {
        case 'pricetoearnings':
            $met = "P/E";
            break;
 
        case 'evtoebit':
            $met = "EV/EBIT";
            break;
         
        case 'evtofcff':
            $met = "EV/FCFF";
            break;
         
         case 'freecashflow':
            $met = "Free Cash Flow";
            $metnum = number_format($metnum);
            break;
         
         case 'ebit':
            $met = "EBIT";
            $metnum = number_format($metnum);
            break;
         
         case 'marketcap':
            $met = "Market Cap";
            $metnum = number_format($metnum);
            break;
         
         case 'last_price':
            $met = "last price";
            $metnum = number_format($metnum);
            break;
         
        default:
            $met = "what";
            break;
    }
 
 
 
 
 
 
$outputtext = "The ".$met." for ".$stock." is ".$metnum; 
 
 
 
 
function stockInfo($stock,$metric) {
 
    //global $metric, $stock;
  
 
     
 
    $ch = curl_init();
 
    curl_setopt($ch, CURLOPT_URL, "https://api.intrinio.com/data_point?identifier=".$stock."&item=".$metric);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
 
    curl_setopt($ch, CURLOPT_USERPWD, "31b15e8f10311a22434bcc049d4d5e91" . ":" . "39e1893f9a957418fb6422114561f4e0");
 
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
 
 
    $json = $result;
    $data = json_decode($json, true);
 
    return $data['value'];
 
 
}
 
 
 
 
 
 
 
 
$output["speech"] = $outputtext;
$output["displayText"] = $outputtext;
$output["source"] = "index.php";
ob_end_clean();
echo json_encode($output);
 
?>
