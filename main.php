<main>
    <section id="videos">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 0){
                echo "<div style='color:green'>The Lesson is Added successfully </div>";
            }
            
            if(isset($_GET['editDerror']) && $_GET['editDerror'] == 0){
                echo "<div style='color:green'>The Data is updated successfully </div>";
            }
        
            if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 0){
				echo "<div style='color:green'>The lesson is deleted successfully </div>";
            }
            else if(isset($_GET['deleteerror']) && $_GET['deleteerror'] == 1){
                echo "<div style='color:red'>The lesson could not be deleted </div>";
            }
					
        ?>
        <div class="row col-12 bottommargin">
            <?php
                if($type == 'teacher'){
                    $select_sql = $connec->query('SELECT * FROM videos')->fetchAll();
                }else{
                    $select_sql = $connec->query('SELECT * FROM videos WHERE level = ? OR level = "All"', $level)->fetchAll();
                }
                foreach($select_sql as $video){
                    $id = $video['id'];
            ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card roundborder">
                    <?php
                        if($type == 'teacher'){
                    ?>
                    <div class="card-body nopadd toproundborder">
                        <div class="dropdown float-right topmargin">
                            <button class="btn bg-transparent dropdown-toggle theme-toggle white" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                <i class="fa fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-menu-content">
                                    <a class="dropdown-item" href="lessonsEditImg?id=<?=$id?>">Edit Image</a>
                                    <a class="dropdown-item" href="lessonsEditData?id=<?=$id?>">Edit Data</a>
                                </div>										
                            </div>
                        </div>
                        <div>
                            <p class="card-text white lbutton middlevertical">This video watched (<?=$video['counter']?>) times</p>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <a href="videos?id=<?=$id?>"><img class="card-img-top <?php if($type != 'teacher'){echo "toproundborder";} ?>" src="Images/<?=$video['image']?>" alt="<?=$video['caption']?>"></a>
                    <div class="card-body bottomroundborder">
                        <h5 class="card-title center middlevertical justifycenter"><?=$video['caption']?></h5>
                        <?php
                            if($type == 'teacher'){
                        ?>
                        <div class="bottommargin center">
                                    <a href="lessonAfterDelete?id=<?=$id?>" class="btn btn-secondary btn-lg lbutton deleteButton" role="button" aria-pressed="true"> Delete Lesson</a>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
        <?php      
            if($type == 'teacher'){
                echo '<div class="dbutton center"><a href="lessonsAdd" class="btn btn-secondary btn-lg lbutton" role="button" aria-pressed="true">Add Lesson</a></div>';
            }
        ?>
    </section>
</main>