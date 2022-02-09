<main>
    <?php
        if($type == "teacher"){
            $updatedid = $_GET['id'];
            $select_sql = $connec->query('SELECT user, type, level FROM users WHERE id = ?', $updatedid)->fetchArray();
    ?>
    <section id="lesson">
        <div class="">
            <div class="login-form roundborder">
                <div class="login-logo">
                    <h2> Edit Student's Data </h2>
                </div>
                <?php
                    if(isset($_GET['editDerror']) && $_GET['editDerror'] == 1){
                    echo "<div style='color:red'>The Data could not be updated </div>";
                    }
                    elseif(isset($_GET['editDerror']) && $_GET['editDerror'] == 2){
                        echo "<div style='color:red'>The Student Exist </div>";
                    }
                ?>
                <form  action="userAfterEditData" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$updatedid?>"/>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-2 labelCenter"><label>User Name</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="User Name" name="user" value="<?=$select_sql['user']?>" required></div>
                    </div>
                    
                    <div class="form-group row col-12 nopadd bottommargin">
                       <div class="col-6 col-md-2 labelCenter"><label>Level</label></div>
                        <div class="btn-group btn-group-toggle col-12 col-md-7 col-lg-6" data-toggle="buttons">
                            <?php
                                $selectl_sql = $connec->query('SELECT * FROM levels WHERE id>0')->fetchAll();
                                foreach($selectl_sql as $level){
                            ?> 
                                <label class="btn btn-secondary <?php if($level['value'] == $select_sql['level']){ echo 'active';} ?>">
                                <input type="radio" name="level" value="<?=$level['value']?>" <?php if($level['value'] == $select_sql['level']){ echo 'checked';} ?>> <?=$level['level']?>
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