<?php
  require_once('conn.php');
?>
<html>
  <head>
    <title>HW4: 留言板</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="./css/index.css"/>
  </head>
  <body>
    <?php include './template/navbar.php';?>
    <p>本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</p>
    <?php if($is_login){
      echo "<form class='input__form' method='POST' action='./handle_add_post.php'>";
        echo "<input type='hidden' value='$user_id' name='user_id'>";
        echo "<input type='text' class='input__text' name='comment' placeholder='說點什麼'/>";
        echo "<button type='submit' class='input__submit'>提交</button>";
      echo "</form>";
    }
    ?>
    <div class="board">
      <?php
        $page_limit= 8;
        $page = $_GET['page'];
        $start = $page*$page_limit;
        $result = $conn->query("SELECT * FROM ishin4554_comments ORDER BY time DESC LIMIT $start, $page_limit");
        if($result->num_rows > 0){
          while($row=$result->fetch_assoc()){
            if($result->num_rows) {
              $post_id = $row['id'];
              $post_user_id = $row['user_id'];
              $post_user_result = $conn->query("SELECT * FROM ishin4554_users WHERE id=$post_user_id");
              $post_nickname = $post_user_result->fetch_assoc()['nickname'];
              $comment = $row['content'];
              $time = $row['time'];
              echo "<div class='board__comment'>";
                echo "<div class='comment__header'>";
                  echo "<div class='header__nickname'>$post_nickname</div>";
                  echo "<div class='header__timestamp'>$time</div>";
                echo "</div>";
              echo "<div class='comment__content'>$comment</div>";
                if($is_login && $post_user_id === $user_id){
                  echo "<a method='GET' href='./handle_delete_post.php?id=$post_id'>delete</a>";
                  echo "<a method='GET' href='./update_post.php?id=$post_id'>edit</a>";
                }
              echo "</div>";
            } else {
              break;
            }
          }
        } 
      ?>
      </div>
    </div>
    <?php 
      $result = $conn->query("SELECT COUNT(*) FROM ishin4554_comments");
      $length = $result->fetch_assoc();
      $prev = $page-1;
      $next = $page+1;
      echo "<div class='pagination'>";
        if ($next * $page_limit >= $length['COUNT(*)']) {
          echo "<a href='./index.php?page=$prev'>prev</a>";
        }
        else if ($prev * $page_limit < 0) {
          echo "<a href='./index.php?page=$next'>next</a>";
        }
        else {
          echo "<a href='./index.php?page=$prev'>prev</a>";
          echo "<a href='./index.php?page=$next'>next</a>";
        }
      echo "</div>";
    ?>
  </body>
</html>