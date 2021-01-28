<!DOCTYPE html>
<html>
    <head>
        <title>Sensors</title>
        <meta charset="utf-8">

        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
        
        <script src="./js/paho-mqtt-min.js"></script>
        <script src="./js/jquery-3.4.1.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container mt-5">
             
            <div class="row">
            <?php
                // PHP connection to DB
                $link = mysqli_connect("bbzj937svfngzjrooibe-mysql.services.clever-cloud.com", "uaxb8xwaqzqfhyg5", "TzBw8FtF0rB9hSVsPXye", "bbzj937svfngzjrooibe");
                if(!$link)
                {
                  die('Could not Connect MySql Server:' .mysql_error());
                }
                // Perform SQL queries for both the sensors, retriving past hour data
                $result = mysqli_query($link,"SELECT * FROM detection ORDER BY curenttime ASC");
            ?>   

            <!-- Last hour sensor values -->
            <div class="row">
                <div class="col">
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading pt-2">Detected Values</h4>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Detection Time</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Accuracy in %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Decode messages and print values -->
                                <?php
                                $i=0;
                                    while($data = mysqli_fetch_array($result)) {
                                                        
                                   echo '<tr>
                                        <td>'. $data["curenttime"]. ' </td>
                                        <td>'. $data["state"]. '</td>
                                        <td>'. $data["color"]. '</td>
                                        <td>'. $data["accuracy"]. '</td>
                                        
                                    </tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php mysqli_close( $link ); ?>
            
        </div>
        
        
    </body>
</html>