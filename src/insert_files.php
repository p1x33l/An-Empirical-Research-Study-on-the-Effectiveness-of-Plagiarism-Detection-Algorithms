<?php
    
    include 'functions.php';
    include 'dbconnect.php';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $pdo=new PDO("mysql:host=$db_hostname;dbname=$db_name;",$db_user,$db_password);
        $ins=$pdo->prepare('insert into articles41 (path,lang) values(?,?)');
        $files_count=count($_FILES['article']['name']);
        for($i=0;$i<$files_count;$i++){
            $ext = pathinfo($_FILES['article']['name'][$i], PATHINFO_EXTENSION);
            if($ext=='txt'){
                $filename=trim(str_replace(' ', '_', $_FILES['article']['name'][$i]));
                $target_file='files/'.generateRandomString(5)."_".$filename;
                move_uploaded_file($_FILES['article']['tmp_name'][$i],$target_file);
                $ins->execute(array($target_file,$_POST['lang']));
                echo ($i+1).") ".$_FILES['article']['name'][$i].' successfully uploaded!<br>';
            }
            else{
                echo ($i+1).") ".$_FILES['article']['name'][$i].' error uploading file!<br>';
            }
        }
        echo "<hr>";

    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <select name="lang">
            <option value="en">EN</option>
            <option value="fr">FR</option>
            <option value="ar">AR</option>
        </select>
        <input type="file" name="article[]" id="article" multiple="multiple"><br>
        <br><br>
        <input type="submit" value="upload">
    </form>
</body>
</html>