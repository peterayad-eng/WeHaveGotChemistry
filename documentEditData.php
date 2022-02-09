<main>
    <?php
        if($type == "teacher"){
            $updatedid = $_GET['id'];
            $select_sql = $connec->query('SELECT * FROM documents WHERE id = ?', $updatedid)->fetchArray(); 
    ?>
    <section id="lesson">
        <div class="">
           <div class="login-form roundborder">
                <div class="login-logo">
                    <h2> Edit Document's Data </h2>
                </div>
                <?php
                    if(isset($_GET['editDerror']) && $_GET['editDerror'] == 1){
                    echo "<div style='color:red'>The Data could not be updated </div>";
                    }
                    elseif(isset($_GET['editDerror']) && $_GET['editDerror'] == 2){
                        echo "<div style='color:red'>Document Title Exist </div>";
                    }
                ?>
                <form  action="documentAfterEditData" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$updatedid?>"/>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-2 labelCenter"><label>Title</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="Document Title" name="title" value="<?=$select_sql['title']?>" required></div>
                    </div>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-6 col-md-2 labelCenter"><label>Paragraph</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="Paragraph" name="par" value="<?=$select_sql['par']?>"></div>
                    </div>
                    
                    <div class="form-group row col-12 nopadd">
                        <div class="col-6 col-md-2 labelCenter"><label>Level</label></div>
                        <div class="btn-group btn-group-toggle col-9 col-md-8 col-lg-7" data-toggle="buttons">
                            <?php
                                $selectl_sql = $connec->query('SELECT * FROM levels')->fetchAll();
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