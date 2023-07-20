<<<<<<< HEAD
<html>
<body>

<form action="welcome.php" method="get">
First Name: <input type="text" name="first_name"><br>
Last Name: <input type="text" name="last_name"><br>
<input type="submit">
</form>

<?php
    if($_GET){
        echo ucwords(strtolower($_GET["first_name"]));
        echo ucwords(strtolower($_GET["last_name"]));
    }
?>
</body>
</html>
=======
<html>
<body>

<form action="welcome.php" method="get">
First Name: <input type="text" name="first_name"><br>
Last Name: <input type="text" name="last_name"><br>
<input type="submit">
</form>

<?php
    if($_GET){
        echo ucwords(strtolower($_GET["first_name"]));
        echo ucwords(strtolower($_GET["last_name"]));
    }
?>
</body>
</html>
>>>>>>> 461f7c67cbdee02dfe0c988905ee0fb78fd3201a
