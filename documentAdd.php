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
                echo "<div style='color:red'>The Document could not be added </div>";
            }
            elseif(isset($_GET['adderror']) && $_GET['adderror'] == 2){
                echo "<div style='color:red'>Document Title Exist </div>";
            }
            if(isset($_GET['editLerror']) && $_GET['editLerror'] == 2){
                echo "<div style='color:red'>The Document Must be less than 5MB </div>";
            }
            elseif(isset($_GET['editLerror']) && $_GET['editLerror'] == 3){
                echo "<div style='color:red'>This File is not a document </div>";
            }
        ?>
        <div class="">
           <div class="login-form roundborder">
                 <div class="login-logo">
                    <h2> Add Document </h2>
                </div>
                <form  action="documentAfterAdd.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group row col-12 nopadd">
                        <div class="col-2 labelCenter"><label>Title</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="Document Title" name="title" required></div>
                    </div>
                    <div class="form-group row col-12 nopadd">
                        <div class="col-6 col-md-2 labelCenter"><label>Paragraph</label></div>
                        <div class="col-12 col-md-9 nopadd leftmargin"><input type="text" class="form-control" placeholder="Paragraph" name="par"></div>
                    </div>
                    
                    <div class="form-group row col-12 nopadd bottommargin">
                        <div class="col-6 col-md-2 labelCenter"><label>Level</label></div>
                        <div class="btn-group btn-group-toggle col-9 col-md-8 col-lg-7" data-toggle="buttons">
                            <?php
                                $selectr_sql = "SELECT * FROM levels";
                                $resultr = mysqli_query($conn, $selectr_sql);
                                for($i=0;$i<$resultr->num_rows;$i++){
                                    $rowr = $resultr->fetch_assoc();
                            ?> 
                                    <label class="btn btn-secondary <?php if($rowr['id'] == 0){echo 'active';} ?>">
                                        <input type="radio" name="level" value="<?=$rowr['value']?>" <?php if($rowr['id'] == 0){echo 'checked';} ?>> <?=$rowr['level']?>
                                    </label>
                            <?php
                               }
                            ?>
                        </div>
                    </div>
                    
                    <div class="stat-widget-four form-group row col-12 topmargin">
				        <div class="stat-icon dib col-2 center leftmargin middlevertical justifycenter">
                            <i class="fas fa-database database"></i>
                        </div>
                        <div class="stat-content col-9 leftmargin nopadd">
                            <div class="text-left dib row col-12 nomargin nopadd">
                                <div class="stat-heading col-12">Select document to upload (pdf, Word, Excel or PowerPoint only):</div>
                                <div class="stat-text row col-12">
                                    <input type="file" name="url" class="col-12" required/> 
                                    <div class="col-12">MaxSize:50MB</div>
                                </div>
                            </div>
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