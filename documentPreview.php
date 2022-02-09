<main>
    <section id="videos">
        <?php
            if(isset($_GET['adderror']) && $_GET['adderror'] == 0){
                echo "<div style='color:green'>The Document is Added successfully </div>";
            }
            
            if(isset($_GET['editDerror']) && $_GET['editDerror'] == 0){
                echo "<div style='color:green'>The Data is updated successfully </div>";
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
                    $select_sql = $connec->query('SELECT * FROM documents')->fetchAll();
                }else{
                    $select_sql = $connec->query('SELECT * FROM documents WHERE level = ? OR level = "All"', $level)->fetchAll();
                }
                foreach($select_sql as $document){
                    $id = $document['id'];
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
                                    <a class="dropdown-item" href="documentsEditData?id=<?=$id?>">Edit Data</a>
                                    <a class="dropdown-item deleteButton" href="documentAfterDelete?id=<?=$id?>">Delete Document</a>
                                </div>										
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="card-body <?php if($type != 'teacher'){echo "roundborder";}else{echo "bottomroundborder";} ?>">
                        <h5 class="card-title middlevertical justifyleft"><?=$document['title']?></h5>
                        <p class="card-text middlevertical justifyleft"><?=$document['par']?></p>
                        <div class="center"><a href="documents/<?=$document['url']?>" class="card-link">Download</a></div>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
        <?php    
            if($type == 'teacher'){
                echo '<div class="dbutton center"><a href="documentsAdd" class="btn btn-secondary btn-lg lbutton" role="button" aria-pressed="true">Add Document</a></div>';
            }
        ?>
    </section>
</main>