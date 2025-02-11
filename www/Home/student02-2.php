<!doctype html>
<html class="no-js" lang="">

<head>
    <div class="table-container">
        <table id="data-table">
            
            <tbody>
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
                    $sql = "SELECT user FROM user WHERE grade = '$grade' AND class = '$class'";
                    $result = mysqli_query($link, $sql);

               
                        //while ($row = mysqli_fetch_assoc($result)) {
                           
                            echo '<form action="downloadreview-02.php" method="POST">
                                        <input type="hidden" name="user" value="' . $row['user'] . '">
                                        <button type="submit" class="downloadreview">查看備審</button>
                                    </form>
                                  ';
                            echo '<form action="viewapplicationorder-02.php" method="POST">
                                        <input type="hidden" name="user" value="' . $row['user'] . '">
                                        <button type="submit" class="viewapplicationorder">查看志願</button>
                                    </form>
                                  ';
                       
                       // }
                    
                } else {
                    echo '<tr><td colspan="2">資料不完整，請重新嘗試。</td></tr>';
                }

                mysqli_close($link);
                ?>
            </tbody>
        </table>
    </div>
</head>

</html>
