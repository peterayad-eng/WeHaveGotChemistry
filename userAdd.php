<main>
    <?php
        $user=$_SESSION['user'];
        $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $type = $row['type'];
    
        if($type == "teacher"){
    ?>
    <section id="lesson">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 1){
                echo "<div style='color:red'>The Student could not be added </div>";
            }
            elseif(isset($_GET['adderror']) && $_GET['adderror'] == 2){
                echo "<div style='color:red'>The Passwords didn't Match </div>";
            }
            elseif(isset($_GET['adderror']) && $_GET['adderror'] == 3){
                echo "<div style='color:red'>The Student Exist </div>";
            }
        ?>
        <div class="">
           <div class="login-form roundborder">
                 <div class="login-logo">
                    <h2> Add Student </h2>
                </div>
                <form  action="userAfterAdd.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-2 labelCenter"><label>User Name</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="User Name" name="user" required></div>
                    </div>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-2 labelCenter"><label>Password</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="password" class="form-control" placeholder="Password" name="pass" required></div>
                    </div>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-2 labelCenter"><label>Confirm Password</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="password" class="form-control" placeholder="Confirm Password" name="cpass" required></div>
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
                                    <label class="btn btn-secondary <?php if($rowr['id'] == 1){echo 'active';} ?>">
                                        <input type="radio" name="level" value="<?=$rowr['value']?>" <?php if($rowr['id'] == 1){echo 'checked';} ?>> <?=$rowr['level']?>
                                    </label>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
					<div class="center">
                        <button type="submit" class="btn btn-success save">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php
        }
    ?>
</main>