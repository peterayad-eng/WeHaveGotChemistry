<main>
    <?php
        $id = $_GET['id'];
        $select_sql = "SELECT * FROM videos WHERE id = '$id'";
        $result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $url = $row['url'];
        $caption = $row['caption'];
        $counter = $row['counter'] + 1;
        $update_sql = "UPDATE videos SET counter='{$counter}'  WHERE id = '{$id}'";
        $conn->query($update_sql);
    ?>
    <section id="video">
         <div class="center white"><?php echo $caption; ?></div>
         <div  class="height1"><iframe src="<?=$url?>"></iframe></div>
    </section>
</main>