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
$year_already = array();
$conn = new mysqli("localhost", "root", "", "test"); //Connect PHP to MySQL Database
$query = '';
$table_data = '';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$filename = "data.json";
$data = file_get_contents($filename); //Read the JSON file in PHP
$array = json_decode($data, true); //Convert JSON String into PHP Array
//   echo '<pre>';
//   print_r($array['results']);
//   exit;
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
$i = 0;
foreach ($array['results'] as $row) //Extract the Array Values by using Foreach Loop
{
    foreach ($already_done as $item) {
        if ($item == $row['Make']) {
            $sql = "SELECT id, 	model_name FROM model where model_name = '" . $row['Model'] . "'";
            $result1 = $conn->query($sql);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($rows = $result1->fetch_assoc()) {
                    $unique_model = $rows["model_name"];
                    $modle_already[] = $unique_model;
                }
            } else {
                $unique_model = $row["Model"];
                if (!in_array($unique_model, $modle_already)) {
                    $modle_already[] = $unique_model;
                    $query = "INSERT  INTO model(model_name, avatar, created_at)  VALUES ('" . $row["Model"] . "', 'NULL', '" . date("Y/m/d") . "'); "; // Make Multiple Insert Query
                    $result = $conn->query($query);
                    $last_id[$i] = $conn->insert_id;
                }
            }
        }
    }
    $i++;
}
echo '<pre>';
print_r($last_id[0]);
exit;
?>
     <br />
         </div>  
      </body>  
 </html>  
 