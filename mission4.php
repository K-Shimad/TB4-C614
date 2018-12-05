<?php
header("Content-Type: text/html; charset=UTF-8");
$dsn  =  'データベース名';
$user  =  'ユーザー名';
$password  =  'パスワード';
$pdo  =  new  PDO($dsn,$user,$password);
$sql = "CREATE TABLE mission"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date TIMESTAMP,"
."password TEXT"
.");";
$stmt = $pdo -> query($sql);


//削除機能
if((!empty($_POST['delete'])) and (empty($_POST['pass2'])))
{
    echo "パスワードを入力してください";
}

if(!empty($_POST['delete']) and !empty($_POST['pass2']))
{
   $delete = $_POST['delete'];
   $delpas = $_POST['pass2'];
   $sql = 'SELECT * FROM mission';
   $results = $pdo->query($sql);
   foreach($results as $row)
   {
       if($delpas == $row['password'])
       {
           $sql = "delete from mission where id = $delete"; 
           $result = $pdo->query($sql);
       }
       if($delpas !== $row['password'] and $delete == $row['id'])
       {
           echo "パスワードが違います";
       }
   }
}


//編集機能
if(!empty($_POST['editnum']) and !empty($_POST['pass3']))
{
   $num = $_POST['editnum'];
   $pas = $_POST['pass3'];
   $sql = 'SELECT * FROM mission';
   $results = $pdo->query($sql);
   foreach($results as $row)
   {
      if($num == $row['id'])
      {
         if($pas != $row['password'])
         {
            echo "パスワードが違います";
         }
         else
         {
             $na = $row['name'];
             $co = $row['comment'];
             $pa = $row['password'];
         }
      }
    }
}

if(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['pass1']))
{
   if(!empty($_POST['editing']))
   {
      $id = $_POST['editing'];
      $nm = $_POST['name'];
      $kome = $_POST['comment'];
      $date = date("Y/m/d H:i:s");
      $password = $_POST['pass1'];
      $sql = "update mission set name='$nm', comment='$kome', date='$date',password='$password' where id = $id";
      $result = $pdo->query($sql);
   }
   else//投稿機能
   {
      $name=htmlspecialchars($_POST['name']);
      $comment=htmlspecialchars($_POST['comment']);
      $editing=htmlspecialchars($_POST['editing']);
      $pass1=$_POST['pass1'];
      if(empty($name) or empty($comment) or empty($pass1))
      {
         echo "名前、コメント、パスワードの三項目を入力してください";
      }
      else
      {
         $sql = $pdo -> prepare("INSERT INTO mission(name,comment,date,password)VALUES(:name,:comment,:date,:password)");
         $sql -> bindParam(':name',$name,PDO::PARAM_STR);
         $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
         $sql -> bindParam(':date',$times,PDO::PARAM_STR);
         $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
         $times = date("Y/m/d H:i:s");
         $name = $_POST['name'];
         $comment = $_POST['comment'];
         $pass = $_POST['pass1'];
         $sql -> execute();
      }
   }
}
?>


<!DOCTYPE html>
<html lang="ja">
<meta charset="UTF-8">
<form method="post" action="mission4.php">
<div style="line-height:1.5em">
<label><input name="name"type="text"size="30" placeholder="名前" value="<?=$na?>"></label>
 <br> 
<label><input name="comment"type="text"size="30" placeholder="コメント" value="<?=$co?>"></label>
<label><input name="editing"type="hidden"size="30" value="<?=$num?>">
<br>
<label><input name="pass1"type="text"size="30" placeholder="パスワード" value="<?=$pa?>"></label>&nbsp;
<input type="submit"value="送信">
<p><label><input name="delete"type="text"size="30" placeholder="削除対象番号"></label>
<br>
<label><input name="pass2"type="text"size="30" placeholder="パスワード"></label>&nbsp;
<input type="submit"value="削除"></p>
<p><label><input name="editnum"type="text"size="30" placeholder="編集対象番号"></label>
<br>
<label><input name="pass3"type="text"size="30" placeholder="パスワード"></label>&nbsp;
<input type="submit"value="編集"></p>
</div>
</form>

<?php
$sql = 'SELECT * FROM mission order by id';
$results = $pdo -> query($sql);
foreach($results as $row)
{
   echo $row['id'].',';
   echo $row['name'].',';
   echo $row['comment'].',';
   echo $row['date'].'<br>';
}
?>

</body>
</html>
