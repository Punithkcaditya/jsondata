

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