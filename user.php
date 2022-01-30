<main>
    <?php
        $user=$_SESSION['user'];
        $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $type = $row['type'];
        $level = $row['level'];
        if($type == 'teacher'){
            $select_sqlu = "SELECT id, user, type, level FROM users WHERE id>'0'";
		    $resultu = mysqli_query($conn, $select_sqlu);
    ?>
    <section id="users">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 0){
                echo "<div style='color:green'>The Student Added successfully </div>";
            }
            
            if(isset($_GET['editPerror']) && $_GET['editPerror'] == 0){
                echo "<div style='color:green'>The Password Reset successfully </div>";
            }
        
            if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 0){
				echo "<div style='color:green'>The Student deleted successfully </div>";
            }
            else if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 1){
                echo "<div style='color:red'>The Student could not be deleted </div>";
            }
					
        ?>
        <div class="center white"><h3>Users</h3></div>
        <div class="row col-12 nopadd nomargin bottommargin">
            <div class="col-1"></div>
            <div class="col-12 col-md-10 nopadd">
                <table class="table table-dark table-striped table-bordered center">
                  <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Level</th>
                        <th scope="col">Data</th>
                        <th scope="col">Password</th>
                        <th scope="col">Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        for($i=0;$i<$resultu->num_rows;$i++){
                            $rowu = $resultu->fetch_assoc();
                            $id = $rowu['id'];
                    ?>
                        <tr>
                            <th scope="row"><?=$rowu['id']?></th>
                            <td><?=$rowu['user']?></td>
                            <td>
                                <?php
                                    if ($rowu['type'] == "teacher"){
                                        echo $rowu['level'];
                                    }else{
                                        $userLevel=$rowu['level'];
                                        $select_sqlr = "SELECT level FROM levels WHERE value = '{$userLevel}'";
                                        $resultr = mysqli_query($conn, $select_sqlr);
                                        $rowr = $resultr->fetch_assoc();
                                        echo $rowr['level'];
                                    }
                                ?>
                            </td>
                            <?php
                                if ($rowu['type'] != "teacher"){
                            ?>
                            <td><a href="usersEditData.php?id=<?=$id?>" class="btn btn-secondary btn-lg editButton" role="button" aria-pressed="true">Edit</a></td>
                            <td><a href="confirmPass.php?flag=0&id=<?=$id?>" class="btn btn-secondary btn-lg resetButton" role="button" aria-pressed="true">Reset</a></td>
                            <td><a href="confirmPass.php?flag=1&id=<?=$id?>" class="btn btn-secondary btn-lg buttonpadd" role="button" aria-pressed="true">Delete</a></td>
                            <?php
                                }else{
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php
                                }
                            ?>
                        </tr> 
                    <?php
                        }
                    ?>
                  </tbody>
                </table>
            </div>
            <div class="col-1"></div>
        </div>
        <div class="dbutton center">
            <a href="usersAdd.php" class="btn btn-secondary btn-lg lbutton" role="button" aria-pressed="true">Add Student</a>
        </div>
    </section>
        <?php
            }
        ?>
</main>