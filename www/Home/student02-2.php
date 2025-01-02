<!doctype html>
<html class="no-js" lang="">

<head>
  <div class="table-container">
    <table id="data-table">
      <thead>
        <tr>
          <th>學號</th>
          <th>姓名</th>
          <th>備審</th>
          <th>留言板</th>
          <th>競賽歷程</th>
          <th>志願序</th>
        </tr>
      </thead>
      <?php
      session_start();

      // 資料庫連接
      $link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
      if (!$link) {
        die("資料庫連接失敗: " . mysqli_connect_error());
      }

      // 檢查 POST 請求中的 grade 和 class
      if (isset($_POST['grade']) && isset($_POST['class'])) {
        $grade = mysqli_real_escape_string($link, $_POST['grade']);
        $class = mysqli_real_escape_string($link, $_POST['class']);

        // 查詢符合 grade 和 class 條件的學生
        $sql = "SELECT * FROM user WHERE grade = '$grade' AND class = '$class'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['user']) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>
                    <form action="downloadreview-02.php" method="POST">
                        <input type="hidden" name="user" value="' . $row['user'] . '">
                        <button type="submit" class="downloadreview">查看備審</button>
                    </form>
                  </td>';
                  echo '<td>
                     <form action="messageboard02-1.php" method="POST">
                        <input type="hidden" name="user" value="' . $row['user'] . '">
                        <button type="submit" class="messageboard">留言板</button>
                    </form>
                  </td>';
            echo '<td>
                    <form action="viewcompetition-02.php" method="POST">
                        <input type="hidden" name="user" value="' . $row['user'] . '">
                        <button type="submit" class="viewcompetition">查看競賽</button>
                    </form>
                  </td>';
            echo '<td>
                    <form action="viewapplicationorder-02.php" method="POST">
                        <input type="hidden" name="user" value="' . $row['user'] . '">
                        <button type="submit" class="viewapplicationorder">查看志願</button>
                    </form>
                  </td>';
            echo '</tr>';
          }

          echo '</tbody>
        </table>';
        } else {
          echo '<p>此班級目前沒有學生。</p>';
        }
      } else {
        echo '<p>資料不完整，請重新嘗試。</p>';
      }

      mysqli_close($link);
      ?>

    </table>


    <!-- JavaScript  -->
    <script>
      fetch('download.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user=' + encodeURIComponent(userId),
      })
        .then(response => response.blob())
        .then(blob => {
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = '下載的檔案名稱';
          document.body.appendChild(a);
          a.click();
          a.remove();
        })
        .catch(error => console.error('下載錯誤:', error));
    </script>
</head>

</html>