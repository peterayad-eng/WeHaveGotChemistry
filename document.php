<main>
    <?php
        $user=$_SESSION['user'];
        $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $type = $row['type'];
        $level = $row['level'];

    ?>
    <section id="videos">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 0){
                echo "<div style='color:green'>The Document Added successfully </div>";
            }
            
            if(isset($_GET['editDerror']) && $_GET['editDerror'] == 0){
                echo "<div style='color:green'>The Data updated successfully </div>";
            }
        
            if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 0){
				echo "<div style='color:green'>The document deleted successfully </div>";
            }
            else if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 1){
                echo "<div style='color:red'>The document could not be deleted </div>";
            }
					
        ?>
        <div class="row col-12 bottommargin">
            <?php
                if($type == 'teacher'){
                    $select_sql = "SELECT * FROM documents";
                    $resultv = mysqli_query($conn, $select_sql);
                }else{
                    $select_sql = "SELECT * FROM documents WHERE level = '{$level}' OR level = 'All'";
                    $resultv = mysqli_query($conn, $select_sql);
                }
                for($i=0;$i<$resultv->num_rows;$i++){
                    $rowv = $resultv->fetch_assoc();
                    $id = $rowv['id'];
            ?>
            <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card roundborder">
                        <?php
                        if($type == 'teacher'){
                        ?>
                        <div class="card-body nopadd toproundborder">
                            <div class="dropdown float-right">
                                <button class="btn bg-transparent dropdown-toggle theme-toggle white" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-menu-content">
                                        <a class="dropdown-item" href="documentsEditData.php?id=<?=$id?>">Edit Data</a>
                                        <a class="dropdown-item deleteButton" href="documentAfterDelete.php?id=<?=$id?>">Delete Document</a>
                                    </div>										
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    <div class="card-body <?php if($type != 'teacher'){echo "roundborder";}else{echo "bottomroundborder";} ?>">
                        <h5 class="card-title middlevertical justifyleft"><?=$rowv['title']?></h5>
                        <p class="card-text middlevertical justifyleft"><?=$rowv['par']?></p>
                        <div class="center"><a href="documents/<?=$rowv['url']?>" class="card-link">Download</a></div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
        <?php
                
            if($type == 'teacher'){
                echo '<div class="dbutton center">
                        <a href="documentsAdd.php" class="btn btn-secondary btn-lg lbutton" role="button" aria-pressed="true">Add Document</a>
                </div>';
            }
        ?>
    </section>
</main>