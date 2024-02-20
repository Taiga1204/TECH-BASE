<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <?php
        $dsn = 'mysql:dbname=データベース名;host=localhost';
        $user = 'ユーザ名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        $sql = "CREATE TABLE IF NOT EXISTS tbboard"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "str TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        $date=date("Y年m月d日 H時i分s秒");
        $password_matched=false;
        
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["str_password"])) {
            $name=$_POST["name"];
            $str=$_POST["str"];
            $password=$_POST["str_password"];
            
            if(!empty($_POST["edit"])) {
                $id = $_POST["edit"];
                $sql = 'SELECT id, password FROM tbboard WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($row['password']==$password) {
                    $id = $row['id'];
                    $sql = 'UPDATE tbboard SET name=:name,str=:str WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':str', $str, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }    
            } else {
                $sql = "INSERT INTO tbboard (name, str, date, password) VALUES (:name, :str, :date, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':str', $str, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
            }
        } elseif(!empty($_POST["delete"]) && !empty($_POST["delete_password"])) {
            $id = $_POST["delete"];
            $password = $_POST["delete_password"];
            
            $sql = 'SELECT password FROM tbboard WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row['password']==$password) {
                $sql = 'delete from tbboard where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        } elseif(!empty($_POST["edit"]) && !empty($_POST["edit_password"])) {
            $id=$_POST["edit"];
            $password=$_POST["edit_password"];
            
            $sql = 'SELECT id, name, str, password FROM tbboard WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row['password']==$password) {
                $edit_num=$row['id'];
                $edit_name=$row['name'];
                $edit_str=$row['str'];
                $edit_password=$row['password'];
                $password_matched=true;
            }
        }
        
        if (!$password_matched) {
        }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name"  <?php if($password_matched) {echo "value=".$edit_name; } else {echo "placeholder=名前"; } ?>>
        <br>
        <input type="text" name="str" <?php if($password_matched) {echo "value=".$edit_str; } else {echo "placeholder=コメント"; } ?>>
        <input type="password" name="str_password" <?php if($password_matched) {echo "value=".$edit_password; } else {echo "placeholder=パスワード"; } ?>>
        <input type="submit" name="submit-comment">
        <br>
        <br>
        <input type="text" name="delete" placeholder="削除対象番号">
        <input type="password" name="delete_password" placeholder="パスワード">
        <input type="submit" name="submit-delete" value="削除">
        <br>
        <br>
        <input type="text" name="edit" <?php if($password_matched) {echo "value=".$edit_num; } else {echo "placeholder=編集対象番号"; } ?>>
        <input type="password" name="edit_password" placeholder="パスワード">
        <input type="submit" name="submit-edit" value="編集">
        <br>
    </form>
    
    <?php
        $sql = 'SELECT * FROM tbboard';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['str'].' ';
            echo $row['date'].'<br>';
        }
    ?>
</body>
</html>