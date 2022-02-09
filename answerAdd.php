<main>
    <section id="lesson">
        <div class="">
            <div class="login-form roundborder">
                <div class="login-logo">
                    <h2> Add Answer </h2>
                </div>
                <?php
                    if(isset($_GET['adderror']) && $_GET['adderror'] == 1){
                        echo "<div style='color:red'>The Document could not be added </div>";
                    }
                    elseif(isset($_GET['adderror']) && $_GET['adderror'] == 2){
                        echo "<div style='color:red'>Please, select homework </div>";
                    }
                    if(isset($_GET['editLerror']) && $_GET['editLerror'] == 2){
                        echo "<div style='color:red'>The Document Must be less than 50MB  </div>";
                    }
                    elseif(isset($_GET['editLerror']) && $_GET['editLerror'] == 3){
                        echo "<div style='color:red'>This File is not a document </div>";
                    }
                ?>
                <form  action="answerAfterAdd" method="POST" enctype="multipart/form-data">
                    <div class="form-group row col-12 nopadd">
                        <div class="col-12 col-md-4 col-lg-3 labelCenter"><label>This Answer for</label></div>
                        <div class="col-12 col-md-7 col-lg-8 nopadd leftmargin">
                            <select class="form-control" name="homework" required>
                                <option value="" selected disabled>Select Homework</option>
                            <?php
                                $select_sql = $connec->query('SELECT * FROM homework WHERE level = ? OR level = "All"', $level)->fetchAll();
                                foreach($select_sql as $homework){
                                    $title = $homework['title'];
                            ?>
                                    <option value="<?=$homework['id']?>"><?=$title?></option>
                            <?php
                                }
                            ?>
                            </select>
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
                                    <input type="file" name="answer" class="col-12" required/> 
                                    <div class="col-12">MaxSize:50MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
					<div class="center">
                        <button type="submit" class="btn btn-success save">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>