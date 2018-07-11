
<html>
    <head>
        <title>Home page</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="style/style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $(function () {
                    // load banner links from html file
                    $("#banner").load("template/banner.html");
                });
            });
        </script>
    </head>
    <body>
    <?php
        if(isset($_COOKIE['user'])){
            echo "<h3>Welcome " . $_COOKIE['user'] . "</h3><br>";
        }
        else{
            header("Location: login.php");
        }
    ?>

    <div id="banner"></div>
    </body>
</html>

