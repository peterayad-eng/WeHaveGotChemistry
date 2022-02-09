<?php
	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">	
	<head>
	    <title>We have got chemistry</title>
		<meta charset="utf-8" />
        <meta name="keywords" content="chemistry, class, online study">
		<meta name="author" content="Persona, info@persona-eg.com" />
		<meta name="publisher" content="Persona eg" />
        <meta name="language" content="en">
        <meta name="description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
        
        <meta name="DC.creator" content="https://www.persona-eg.com">
		<meta name="DC.Publisher" content="Persona eg" />
		<meta name="DC.Rights" content="Copyright 2021, Persona Team. All rights reserved." />
		<meta name="DC.Type" content="text/html" />
		<meta name="DC.Language" content="en" />
		<meta name="DC.Title" lang="en" content="We have got chemistry" />
		<meta name="DC.Description" xml:lang="en" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta name="DC.Identifier" schema="DCterms:URI" content="https://www.persona-eg.com" />
        
        <meta property="og:type" content="website" />
		<meta property="og:title" content="We have got chemistry" />
		<meta property="og:url" content="https://www.wehavegotchemistry.com" />
		<meta property="og:description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta property="og:site_name" content="We have got chemistry" />
        <meta property="og:image" content="Images/logo.jpg" />
        
        <meta property="twitter:card" content="website" />
		<meta property="twitter:title" content="We have got chemistry" />
		<meta property="twitter:creator" content="https://www.wehavegotchemistry.com" />
		<meta property="twitter:description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta property="twitter:site" content="We have got chemistry" />
        <meta property="twitter:image" content="Images/logo.jpg" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        
        <link rel="canonical" href="https://www.wehavegotchemistry.com/index" />
		<link rel="shortcut icon" href="Images/logo.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="Bootstrap4.4.1/css/bootstrap.min.css">
        <link href="fontawesome5.12/css/fontawesome.css" rel="stylesheet">
        <link href="fontawesome5.12/css/brands.css" rel="stylesheet">
        <link href="fontawesome5.12/css/solid.css" rel="stylesheet">
        <link href="fontawesome5.12/css/regular.css" rel="stylesheet">
		<link rel="stylesheet" href="CSS/animate.css">
		<link rel="stylesheet" href="CSS/CSS.css">
	</head>
    
	<body>
	<?php
        $id = $_GET['id'];
        require_once "connect.php";	
		$connec = new con();
        $select_sql = $connec->query('SELECT user FROM users WHERE id = ?', $id)->fetchArray();
        
    	if(isset($_GET['editPerror']) && $_GET['editPerror'] == 1){
            echo "<div style='color:red'>Password Could Not be Updated</div>";
		}
        else if(isset($_GET['editPerror']) && $_GET['editPerror'] == 2){
            echo "<div style='color:red'>The Passwords Didn't Matched</div>";
        }
	?>
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index">
                        <img class="align-content imgSize roundborder" src="Images/logo.jpg" alt="Web Class">
                    </a>
                    <h1 class="logo-header"> Dr. Maggy Maher </h1>
                </div>
                <div class="login-form roundborder">
                    <div class="login-logo">
                        <h2> Please, Type The New Student's Password </h2>
                    </div>
                    <form  action="userResetPass" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?=$id?>"/>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" placeholder="User Name" value="<?=$select_sql['user']?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="pass">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" placeholder="Confirm Password" name="cpass">
                        </div>

						<button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <footer class="backwhite bottom">
		<p class="p4 center copy nomargin"><a href='https://persona-eg.com/' target="_blank" class='footera'>Copyright © 2020 Persona-eg. All rights reserved.</a></p>
	</footer>	
		
		<script src="Bootstrap4.4.1/jquery-3.4.1.min.js"></script>
        <script src="Bootstrap4.4.1/popper.min.js"></script>
        <script src="Bootstrap4.4.1/js/bootstrap.min.js"></script>
        <script src="JS/main.js"></script>


    </body>
</html>
