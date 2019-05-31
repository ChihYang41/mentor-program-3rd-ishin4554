<div class="board">
  <?php
    $page_limit= 8;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 0;
    }
    $start = $page*$page_limit;
    $result = $conn->query("SELECT * FROM ishin4554_comments ORDER BY time DESC LIMIT $start, $page_limit");
    if($result->num_rows > 0){
      while($row=$result->fetch_assoc()){
        if($result->num_rows) {
          $post_id = $row['id'];
          $comment = $row['content'];
          $time = $row['time'];
          $post_user = new User($conn);
          $post_user_id = $row['user_id'];
          $post_nickname = $post_user->readUserById($post_user_id)['nickname'];
          include './template/comment.php';
        } else {
          break;
        }
      }
    }
    ?>
  </div>
</div>