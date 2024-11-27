

<html>
    <head>
        <title>Database Connection</title>
    </head>
    <body>
        <?php

        //Connect to server
        $connect = mysqli_connect("localhost", "root", "", "pantrypro");

        if (!$connect)
        {
            die ('ERROR:' .mysqli_connect_error());
        }

        ?>
    </body>
</html>