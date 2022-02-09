<main>
    <?php
        if($type == 'teacher'){
            $select_sql = $connec->query('SELECT id, user, type, level FROM users WHERE id>0')->fetchAll();
    ?>
    <section id="users">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 0){
                echo "<div style='color:green'>The Student Added successfully </div>";
            }
            
            if(isset($_GET['editDerror']) && $_GET['editDerror'] == 0){
                echo "<div style='color:green'>The Student data modified successfully </div>";
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
                            $counter=0;
                            foreach($select_sql as $user){
                                $id = $user['id'];
                                $counter++;
                        ?>
                            <tr>
                                <th scope="row"><?=$counter?></th>
                                <td><?=$user['user']?></td>
                                <td>
                                    <?php
                                        if ($user['type'] == "teacher"){
                                            echo $user['level'];
                                        }else{
                                            $userLevel=$user['level'];
                                            $selectl_sql = $connec->query('SELECT level FROM levels WHERE value = ?', $userLevel)->fetchArray();
                                            echo $selectl_sql['level'];
                                        }
                                    ?>
                                </td>
                                <?php
                                    if ($user['type'] != "teacher"){
                                ?>
                                <td><a href="usersEditData?id=<?=$id?>" class="btn btn-secondary btn-lg editButton" role="button" aria-pressed="true">Edit</a></td>
                                <td><a href="confirmPass?flag=0&id=<?=$id?>" class="btn btn-secondary btn-lg resetButton" role="button" aria-pressed="true">Reset</a></td>
                                <td><a href="confirmPass?flag=1&id=<?=$id?>" class="btn btn-secondary btn-lg buttonpadd" role="button" aria-pressed="true">Delete</a></td>
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
            <a href="usersAdd" class="btn btn-secondary btn-lg lbutton" role="button" aria-pressed="true">Add Student</a>
        </div>
    </section>
        <?php
            }
        ?>
</main>