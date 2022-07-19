<main>
    <?php
        $id = $_GET['id'];
        $select_sql = $connec->query('SELECT * FROM videos WHERE id = ?', $id)->fetchArray();
        $url = $select_sql['url'];
        $caption = $select_sql['caption'];
        $counter = $select_sql['counter'] + 1;
        $update_sql = $connec->query('UPDATE videos SET counter= ? WHERE id = ?', $counter, $id);
    ?>
    <section id="video">
         <div class="center white"><?php echo $caption; ?></div>
         <div  class="height1"><iframe src="<?=$url?>" sandbox='allow-same-origin allow-scripts'></iframe><div style="width: 80px; height: 80px; position: absolute; opacity: 0; right: 0px; top: 0px;"></div></div>
    </section>
</main>
