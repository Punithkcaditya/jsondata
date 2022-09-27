<?php
    try
    {
        $connect = mysqli_connect("localhost", "root", "", "mart_dbsync"); 
        $query = '';
        $table_data = '';
        $filename = "data.json";

        $data = file_get_contents($filename);
        $array = json_decode($data, true); 

        foreach($array as $set) 
        {

            // echo '<pre>';
            // print_r($set['Make']);
            // exit;
            $tblName = $set['Make'];
            if(sizeof($set['Make']) > 0) {
                $query = '';
                $colList = array();
                $valList = array();
                //  Get list of column names
                foreach($set['Make'][0] as $colname => $dataval) {
                    $colList[] = "`".$colName."`";
                }
                $query .= "INSERT INTO `".$tblName."` \n";
                $query .= "(".implode(",",$colList).")\nVALUES\n";
                //  Go through the rows for this table.
                foreach($set['Make'] as $idx => $row) {
                    $colDataA = array();
                    //  Get the data values for this row.
                    foreach($row as $colName => $colData) {
                        $colDataA[] = "'".$colData."'";
                    }
                    $valList[] = "(".implode(",",$colDataA).")";
                }
                //  Add values to the query.
                $query .= implode(",\n",$valList)."\n";
                //  If id column present, add ON DUPLICATE KEY UPDATE clause
                if(in_array("`id`",$colList)) {
                    $query .= "ON DUPLICATE KEY UPDATE\n\tSet ";
                    $tmp = array();
                    foreach($colList as $idx => $colName) {
                        $tmp[] = $colName." = new.".$colname." ";
                    }
                    $query .= implode(",",$tmp)."\n";
                } else {
                    echo "<p><b>`id`</b> column not found. <i>ON DUPLICATE KEY UPDATE</i> clause <b>NOT</b> added.</p>\n";
                    echo "<p>Columns Found:<pre>".print_r($colList,true)."</pre></p>\n";
                }
                echo "<p>Insert query:<pre>$query</pre></p>";
                $r = mysqli_query($connect, $query);  
                echo "<h1>".mysqli_num_rows($r)." Rows appeded in $tblName</h1>";
            } else {
                echo "<p>No rows to insert for $tblName</p>";
            }
        }
    } 

    catch(Exception $e)
    {   
        echo $e->getMessage();  
    }
?>