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
                $result = mysqli_query($link,"SELECT * FROM detection ORDER BY curenttime DESC");
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
        
        <script>
            // MQTT broker
            var HOST = "";
            var PORT = 8083;

            // Array of stations
            var stations = [];

            // Callback handlers for connection up and lost
            function onConnect() {
                // Once a connection has been made, make a subscription
                client.subscribe("sensors");
            }

            function onConnectionLost( responseObject ) {
                if ( responseObject.errorCode !== 0 ) {
                    console.log( "onConnectionLost:" + responseObject.errorMessage );
                }
            }

            // Callback for received message from MQTT broker
            function onMessageArrived( message ) {
                var sens_id;
                var mess = JSON.parse( message.payloadString );

                // Add station to array
                if( stations.indexOf( mess.id ) == -1 )
                    stations.push( mess.id )

                if( mess.id == stations[0] )
                    sens_id = "#sens0";
                else if( mess.id == stations[1] )
                    sens_id = "#sens1";

                // Use JQuery in order to inject data into HTML code
                if( sens_id ) {
                    $( sens_id + ' .temp' ).empty().append( "Temperature: <span class='font-weight-bold'>" + mess.data.temp + "</span> °C" );
                    $( sens_id + ' .hum' ).empty().append( "Humidity: <span class='font-weight-bold'>" + mess.data.hum + "%</span>" );
                    $( sens_id + ' .w_dir' ).empty().append( "Wind direction: <span class='font-weight-bold'>" + mess.data.w_dir + "°</span>" );
                    $( sens_id + ' .w_int' ).empty().append( "Wind intensity: <span class='font-weight-bold'>" + mess.data.w_int + "</span> m/s" );
                    $( sens_id + ' .r_heig' ).empty().append( "Rain height: <span class='font-weight-bold'>" + mess.data.r_heig + "</span> mm/h" );
                }
            }

            // Create a client instance with paho javascript api
            var client = new Paho.MQTT.Client( HOST, Number(PORT), "website" );

            // Set callback handlers
            client.onConnectionLost = onConnectionLost;
            client.onMessageArrived = onMessageArrived;

            // Connect the client to MQTT broker
            client.connect( { onSuccess: onConnect } );
        </script>
    </body>
</html>