<?php
    include 'functions.php';
    include 'dbconnect.php';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $target_dir = "files/";
        
        $target_file = $target_dir .basename($_FILES['article']['name']);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $random_string=generateRandomString(5);
        $file_name=str_replace(' ', '_', $_POST['title']);
        $target_file_new_name = $target_dir . $random_string . "_" .basename($file_name).".".$fileType;

        if($fileType=="txt"){
            move_uploaded_file($_FILES["article"]["tmp_name"],$target_file_new_name);
            $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_name;",$db_user,$db_password);
            $ins=$pdo->prepare("insert into articles (title,path) values(?,?)");
            $ins->execute(array($_POST['title'],$target_file_new_name));
            echo "<script>alert('successfully uploaded!');</script>";
        }
        else{
            echo "<script>alert('error!');</script>";
        }
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
        <input type="text" name="title" id="title">
        <input type="file" name="article" id="article">
        <input type="submit" value="upload">
    </form>
    
</body>
</html>