28/09/2022


<html>  
      <head>  
           <title>Webslesson Tutorial</title> 
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <style>
   
   .box
   {
    width:750px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:100px;
   }
  </style>
      </head>  
      <body>  
        <div class="container box">
          <h3 align="center">Import JSON File Data into Mysql Database in PHP</h3><br />
          <?php
$already_done = array();
$modle_already = array();
$bit_arr = array();
$bitwin = array();
$contain1 = array();
$contain2 = array();
$conn = new mysqli("localhost", "root", "", "test"); //Connect PHP to MySQL Database
$query = '';
$querynew = '';
$table_data = '';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$filename = "data.json";
$data = file_get_contents($filename); //Read the JSON file in PHP
$array = json_decode($data, true); //Convert JSON String into PHP Array
$out = [];
$put = [];
$output = [];

//   echo '<pre>';
//   print_r($array['results']);
//   exit;













foreach ($array['results'] as $element) {
    $year_already[$element['Model']] = array();
    $out[$element['Make']][] = ['Model' => $element['Model'], 'Year' => $element['Year']];
    foreach ($out[$element['Make']] as $element) {
        //$put[$element['Model']][] = ['Year' => $element['Year']];
        $put[$element['Model']][] = ['Year' => $element['Year']];
    }
}

  

foreach ($array['results'] as $row) //Extract the Array Values by using Foreach Loop
{


// for make

$sql = "SELECT id, make_name FROM make where make_name = '" . $row['Make'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($rows = $result->fetch_assoc()) {
        $unique_hash = $rows["make_name"];
        $already_done[] = $unique_hash;
    }  
} else {
    

        foreach ($out as $key=>$course) {
            // echo '<pre>';
            // print_r($key);
            // exit;
            $unique_hash = $key;
            if (!in_array($unique_hash, $already_done)) {
                $already_done[] = $unique_hash;
                $query = "INSERT  INTO make(make_name, avatar, created_at)  VALUES ('" . $unique_hash . "', 'NULL', '" . date("Y/m/d") . "'); "; // Make Multiple Insert Query
                $result = $conn->query($query);
                $last_id[$key] = $conn->insert_id;
            }




        }

// echo '<pre>';
// print_r($last_id);
// exit;
     
    
}









// make end

// model

    $sql = "SELECT id, 	model_name FROM model where model_name = '" . $row['Model'] . "'";
    $result1 = $conn->query($sql);
    if ($result1->num_rows > 0) {
        // output data of each row
        while ($rows = $result1->fetch_assoc()) {
            $unique_model = $rows["model_name"];
            $modle_already[] = $unique_model;
        }
    } else {
        for ($i = 0;$i < sizeof($out[$row["Make"]]);$i++) {
            $unique_model = $out[$row["Make"]][$i]["Model"];
            if (!in_array($unique_model, $modle_already)) {
                $modle_already[] = $unique_model;
                $query = "INSERT  INTO model(model_name, avatar, created_at)  VALUES ('" . $unique_model . "', 'NULL', '" . date("Y/m/d") . "'); "; // Make Multiple Insert Query
                $result = $conn->query($query);
                $last_id_new[$row["Make"]][$i] = $conn->insert_id;
            }
        }
    }


    // model end 
    // for year

    $sql2 = "SELECT id, 	year FROM year where year = '" . $row['Year'] . "'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        // output data of each row
        while ($rows = $result2->fetch_assoc()) {
            $unique_year = $rows["year"];
            $year_already[] = $unique_year;
        }
    } else {
        
        for ($i = 0;$i < sizeof($put[$row["Model"]]);$i++) {
            $unique_year = $put[$row["Model"]][$i]["Year"];
      
            if (!in_array($unique_year, $year_already[$row["Model"]])) {
                $year_already[$row["Model"]][] = $unique_year;
                $query = "INSERT  INTO year(year, created_at)  VALUES ('" . $unique_year . "',  '" . date("Y/m/d") . "'); "; // Make Multiple Insert Query
                $result = $conn->query($query);
                $last_id_year[$row["Model"]][$i] = $conn->insert_id;
            }
        }

    }

    // year end
}

// echo '<pre>';
// print_r($last_id_year);
// exit;
























// echo '<pre>';
// print_r($out);
// exit;

$i=0;
foreach ($out as $key=>$course) {
    
    $sqlm = "SELECT id FROM make where make_name = '" . $key . "'";
        $result = mysqli_query($conn, $sqlm);
        $make_row = mysqli_fetch_assoc($result);

// echo '<pre>';
// print_r($course[$i]['Model']);
// exit;

    $last_names = array_column($course, 'Model');
    $years = array_column($course, 'Year');
    $model_names = array_unique($last_names);
    $model_years = array_unique($years);
    // $bit_arr = array();
    // $bitwin = array();


    $pirate_id = $course[$i]['Model'];
    if (!in_array($pirate_id, $bit_arr)) {
    foreach($model_names as $names){
        $bit_arr[] = $pirate_id;
        $sql = "SELECT id FROM model where model_name = '" . $names . "'";
        $result = mysqli_query($conn, $sql);
        $model_row[] = mysqli_fetch_assoc($result);
    }
}



    foreach($model_years as $years){
        $sql = "SELECT id FROM  year where  year = '" . $years . "'";
        $result = mysqli_query($conn, $sql);
        $year_row[] = mysqli_fetch_assoc($result);
    }

    //$string = serialize( $model_row );
    //$model_row_value = implode(" , ",$model_row);
    foreach ($model_row as $item)
{
     $commaSeparated_model[] =  $item['id'];
}

foreach ($year_row as $item)
{
     $commaSeparated_year[] =  $item['id'];
}

// echo '<pre>';
// print_r($commaSeparated_model);print_r($commaSeparated_year);
// exit;

    $string_product_model = implode(',', $commaSeparated_model);
    $string_product_year = implode(',', $commaSeparated_year);

    $querynew .= "INSERT  INTO ymm(make_id, model_id, year_id, created_at)  VALUES ('" . $make_row['id'] . "', ('".$string_product_model."'), ('".$string_product_year."') ,'" . date("Y/m/d") . "'); "; // Make Multiple Insert Query

   
    unset($model_row);
    unset($commaSeparated_model);
    unset($commaSeparated_year);
    unset($year_row);
 $i++;
}

// echo '<pre>';
// print_r($querynew);
// exit;








$connect = mysqli_connect("localhost", "root", "", "test"); 
if(mysqli_multi_query($connect, $querynew)) {
    echo 'success';
}



?>
     <br />
         </div>  
      </body>  
 </html>  
 




















28/09/2022

gettingTogether($model_row );
function gettingTogether(Array $array){
    $result = [];
    if(!is_array($array)){
        return false;
    }

    foreach($array as $key => $value){
        if(is_array($value)){
            $result = array_merge($result, gettingTogether($value));
        }
        else{
            $result[] = $value;
        }
    }
}
















foreach ($array['results'] as $element) {
 
 if (isset($last_id_year[$element['Model']]) && isset($last_id_new[$element['Make']]) ) {

 $model_id = implode(" , ",$last_id_new[$element['Make']]);
 $year_id = implode(" , ",$last_id_year[$element['Model']]);

$querynew .= "INSERT  INTO ymm(make_id, model_id, year_id, created_at)  VALUES ('" . $last_id[$element['Make']] . "', ('".$model_id."'), ('".$year_id."') ,'" . date("Y/m/d") . "'); "; // Make Multiple Insert Query

 }
}














echo '<pre>';
print_r($last_id);
//print_r($out[$row["Make"]][0]["Model"]);
// print_r(sizeof($out[$row["Make"]]));
exit;
//$output =  array_map("unserialize", array_unique(array_map("serialize", $put[$element['Model']])));













foreach ($array['results'] as $row) //Extract the Array Values by using Foreach Loop
{
    // make
    $sql = "SELECT id, make_name FROM make where make_name = '" . $row['Make'] . "'";
    $result = $conn->query($sql);
    // echo '<pre>';
    // print_r($result);
    // exit;
    if ($result->num_rows > 0) {
        // output data of each row
        while ($rows = $result->fetch_assoc()) {
            $unique_hash = $rows["make_name"];
            $already_done[] = $unique_hash;
        }
    } else {
        $unique_hash = $row["Make"];
        if (!in_array($unique_hash, $already_done)) {
            $already_done[] = $unique_hash;
            // sql insert here
            $query = "INSERT  INTO make(make_name, avatar, created_at)  VALUES ('" . $row["Make"] . "', 'NULL', '" . date("Y/m/d") . "'); "; // Make Multiple Insert Query
            $result = $conn->query($query);
            $last_id = $conn->insert_id;
            $querytwo = "INSERT  INTO ymm(make_id)  VALUES ('" . $last_id . "'); "; // Make Multiple Insert Query
            $result = $conn->query($querytwo);
        }
    }
    // model
    
}















$sql = "SELECT id, 	model_name FROM model where model_name = '".$row['Model']."'";
            $result1 = $conn->query($sql);
  
            if ($result1->num_rows > 0) {
                // output data of each row
                while($rows = $result->fetch_assoc()) {
                    $unique_model =  $rows["model_name"];
                }
            }else{
               
                //$unique_hash = $row["Make"];
                foreach($already_done as $item) {
                    // echo '<pre>';
                    // print_r($item);
                    // exit;
                    $unique_model =  $rows["model_name"];

                }
             

            }