<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-05</title>
</head>
<body>
    <?php
        $filename="mission_3-05.txt";
        $count=file($filename);
        $last_line=end($count);
        $last_num=explode("<>", $last_line);
        if(count($count)==0) {
            $num=1;
        } else {
            $num=$last_num[0]+1;
        }
        $date=date("Y年m月d日 H時i分s秒");
        $password_matched=false;
        
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["str_password"])) {
            $name=$_POST["name"];
            $str=$_POST["str"];
            $password=$_POST["str_password"];
            if(!empty($_POST["edit"])) {
                $edit=$_POST["edit"];
                $fp=fopen($filename, "w");
                foreach($count as $lines) {
                    $edit_line=explode("<>", $lines);
                    if($edit_line[0]==$edit && $edit_line[4]==$password) {
                        fwrite($fp, $edit."<>".$name."<>".$str."<>".$date."<>".$password."<>".PHP_EOL);
                    } else {    
                        fwrite($fp, $edit_line[0]."<>".$edit_line[1]."<>".$edit_line[2]."<>".$edit_line[3]."<>".$edit_line[4]."<>".PHP_EOL);
                    }
                }
                fclose($fp);
            } else {
                $fp=fopen($filename, "a");
                fwrite($fp, $num."<>".$name."<>".$str."<>".$date."<>".$password."<>".PHP_EOL);
                fclose($fp);
            }
        } elseif(!empty($_POST["delete"]) && !empty($_POST["delete_password"])) {
            $delete=$_POST["delete"];
            $password=$_POST["delete_password"];
            foreach($count as $key => $lines) {
                $delete_line=explode("<>", $lines);
                if($delete==$delete_line[0] && $password==$delete_line[4]) {
                    unset($count[$key]);
                    file_put_contents($filename, $count);
                }
            }
        } elseif(!empty($_POST["edit"]) && !empty($_POST["edit_password"])) {
            $edit=$_POST["edit"];
            $password=$_POST["edit_password"];
            foreach($count as $lines) {
                $edit_line=explode("<>", $lines);
                if($edit==$edit_line[0] && $password==$edit_line[4]) {
                    $edit_num=$edit_line[0];
                    $edit_name=$edit_line[1];
                    $edit_comment=$edit_line[2];
                    $edit_password=$edit_line[4];
                    $password_matched=true;
                }
            }
        }
        
        if (!$password_matched) {
        }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name"  <?php if($password_matched) {echo "value=".$edit_name; } else {echo "placeholder=名前"; } ?>>
        <br>
        <input type="text" name="str" <?php if($password_matched) {echo "value=".$edit_comment; } else {echo "placeholder=コメント"; } ?>>
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
        foreach(file($filename,FILE_IGNORE_NEW_LINES) as $model) {
            $word=explode("<>", $model);
            echo $word[0]." ".$word[1]." ".$word[2]." ".$word[3]."<br>";
        }
    ?>
</body>
</html>