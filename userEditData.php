<main>
    <?php
        $user=$_SESSION['user'];
        $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $type = $row['type'];
    
        if($type == "teacher"){
            $updatedid = $_GET['id'];
            $select_sqlu = "SELECT user, type, level FROM users WHERE id = '{$updatedid}'";
            $resultu = mysqli_query($conn, $select_sqlu);
            $rowu = $resultu->fetch_assoc();
    ?>
    <section id="lesson">
        <?php
            if(isset($_GET['editDerror']) && $_GET['editDerror'] == 1){
			echo "<div style='color:red'>The Data could not be updated </div>";
            }
            elseif(isset($_GET['editDerror']) && $_GET['editDerror'] == 2){
                echo "<div style='color:red'>The Student Exist </div>";
            }
        ?>
        <div class="">
           <div class="login-form roundborder">
                 <div class="login-logo">
                    <h2> Edit Student's Data </h2>
                </div>
                <form  action="userAfterEditData.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$updatedid?>"/>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-2 labelCenter"><label>User Name</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="User Name" name="user" value="<?=$rowu['user']?>" required></div>
                    </div>
                    
                    <div class="form-group row col-12 nopadd bottommargin">
                       <div class="col-6 col-md-2 labelCenter"><label>Level</label></div>
                        <div class="btn-group btn-group-toggle col-12 col-md-7 col-lg-6" data-toggle="buttons">
                            <?php
                                $selectr_sql = "SELECT * FROM levels WHERE id>'0'";
                                $resultr = mysqli_query($conn, $selectr_sql);
                                for($i=0;$i<$resultr->num_rows;$i++){
                                    $rowr = $resultr->fetch_assoc();
                            ?> 
                                <label class="btn btn-secondary <?php if($rowr['value'] == $rowu['level']){ echo 'active';} ?>">
                                <input type="radio" name="level" value="<?=$rowr['value']?>" <?php if($rowr['value'] == $rowu['level']){ echo 'checked';} ?>> <?=$rowr['level']?>
                                    </label>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    
					<div class="center">
                        <button type="submit" class="btn btn-success save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php
        }
    ?>
</main>