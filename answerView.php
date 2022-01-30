<main>
    <?php
        $user=$_SESSION['user'];
        $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $type = $row['type'];

        if($type == "teacher"){
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
            
            $selectr_sql = "SELECT * FROM levels WHERE id>'0'";
            $resultr = mysqli_query($conn, $selectr_sql);
            for($j=0;$j<$resultr->num_rows;$j++){
                $rowr = $resultr->fetch_assoc();
                $levelValue=$rowr['value'];
                if($rowr['id'] != 1){echo "<hr/>";}
        ?> 
                <div class="center white topmargin"><?=$rowr['level']?></div>
                <div class="row col-12 bottommargin">
                <?php
                    $selecti_sql = "SELECT * FROM answers WHERE level = '{$levelValue}'";
                    $resulti = mysqli_query($conn, $selecti_sql);

                    for($i=0;$i<$resulti->num_rows;$i++){
                    $rowi = $resulti->fetch_assoc();
                    $id = $rowi['id'];
                ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card roundborder">
                              <div class="card-body nopadd toproundborder">
                                  <div class="dropdown float-right">
                                    <button class="btn bg-transparent dropdown-toggle theme-toggle white" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                          <i class="fa fa-cog"></i>
                                    </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <div class="dropdown-menu-content">
                                                    <a class="dropdown-item deleteButton" href="answerAfterDelete.php?id=<?=$id?>">Delete answer</a>
                                            </div>										
                                        </div>
                                  </div>
                                </div>
                            <div class="card-body bottomroundborder">
                                <h5 class="card-title"><?=$rowi['title']?></h5>
                                <br/>
                                <div class="center"><a href="answers/<?=$rowi['url']?>" class="card-link">Download</a></div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
                </div>
        <?php
            }
        ?>
    </section>
    <?php
        }
    ?>
</main>