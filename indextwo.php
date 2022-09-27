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
          $connect = mysqli_connect("localhost", "root", "", "test"); //Connect PHP to MySQL Database
          $query = '';
          $table_data = '';
          $filename = "data.json";
          $data = file_get_contents($filename); //Read the JSON file in PHP
          $array = json_decode($data, true); //Convert JSON String into PHP Array
        //   echo '<pre>';
        //   print_r($array['results']);
        //   exit;
        
          foreach($array['results'] as $row) //Extract the Array Values by using Foreach Loop
          {

         
            $unique_hash = $row["Make"];
          
            if (!in_array($unique_hash, $already_done))
            {
               $already_done[] = $unique_hash;
               // sql insert here
               $query .= "INSERT  INTO make(make_name, avatar, created_at)  VALUES ('".$row["Make"]."', 'NULL', '".date("Y/m/d")."'); ";  // Make Multiple Insert Query 


      
            }


            $unique_model = $row["Model"];
            if (!in_array($unique_model, $modle_already))
            {
               $modle_already[] = $unique_model;
               // sql insert here
               $query .= "INSERT  INTO model(model_name, avatar, created_at) VALUES ('".$row["Model"]."', 'NULL', '".date("Y/m/d")."'); ";  // Make Multiple Insert Query 

            }


            $unique_year = $row["Year"];
            if (!in_array($unique_year, $year_already))
            {
               $year_already[] = $unique_year;
               // sql insert here
               $query .= "INSERT  INTO year(year, created_at) VALUES ('".$row["Year"]."',  '".date("Y/m/d")."'); ";  // Make Multiple Insert Query 

            }




           $table_data .= '
            <tr>
       <td>'.$row["Make"].'</td>
       <td>NULL</td>
       <td>'.date("Y/m/d").'</td>
      </tr>
           '; //Data for display on Web page
          }
          if(mysqli_multi_query($connect, $query)) //Run Mutliple Insert Query
    {
     echo '<h3>Imported JSON Data</h3><br />';
     echo '
      <table class="table table-bordered">
        <tr>
         <th width="45%">Name</th>
         <th width="10%">Avatar</th>
         <th width="45%">Created At</th>
        </tr>
     ';
     echo $table_data;  
     echo '</table>';
          }




          ?>
     <br />
         </div>  
      </body>  
 </html>  
 