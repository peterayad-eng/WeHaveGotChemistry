<!DOCTYPE html>
<html>	
	<head lang="en">
	    <title>We have got chemistry</title>
		<meta charset="utf-8" />
        <meta name="keywords" content="chemistry, class, online study">
		<meta name="author" content="Persona, info@persona-eg.com" />
		<meta name="publisher" content="Persona eg" />
		<meta name="language" content="en">
		<meta name="copyright"content="Copyright 2020, Persona Team. All rights reserved.">
        <meta name="description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
        
        <meta name="DC.creator" content="https://www.persona-eg.com/index.php">
		<meta name="DC.Publisher" content="Persona eg" />
		<meta name="DC.Rights" content="Copyright 2020, Persona Team. All rights reserved." />
		<meta name="DC.Type" content="text/html" />
		<meta name="DC.Language" content="en" />
		<meta name="DC.Title" lang="en" content="We have got chemistry" />
		<meta name="DC.Description" xml:lang="en" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta name="DC.Identifier" schema="DCterms:URI" content="https://www.persona-eg.com/index.php" />
        
        <meta property="og:type" content="website" />
		<meta property="og:title" content="We have got chemistry" />
		<meta property="og:url" content="https://www.wehavegotchemistry.com" />
		<meta property="og:description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta property="og:site_name" content="We have got chemistry" />
        <meta property="og:image" content="Images/logo.jpg" />
        
        <meta property="twitter:card" content="website" />
		<meta property="twitter:title" content="We have got chemistry" />
		<meta property="twitter:creator" content="https://www.wehavegotchemistry.com/index.php" />
		<meta property="twitter:description" content="It's a chemistry webclass introduced by dr. Maggy Maher for subscribed students only." />
		<meta property="twitter:site" content="We have got chemistry" />
        <meta property="twitter:image" content="Images/logo.jpg" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name=”robots” content=”index, follow”>

		<link rel="shortcut icon" href="Images/logo.ico" type="image/x-icon" /> 
        <link rel="stylesheet" href="Bootstrap4.4.1/css/bootstrap.min.css">
        <link href="fontawesome5.12/css/fontawesome.css" rel="stylesheet">
        <link href="fontawesome5.12/css/brands.css" rel="stylesheet">
        <link href="fontawesome5.12/css/solid.css" rel="stylesheet">
        <link href="fontawesome5.12/css/regular.css" rel="stylesheet">
		<link rel="stylesheet" href="CSS/animate.css">
		<link rel="stylesheet" href="CSS/CSS.css">
	</head>
	<body>
        <header class="navbar navbar-expand-lg navbar-light bg-ligh">
            <?php
                $user=$_SESSION['user'];
                $select_sql = "SELECT id, type, level FROM users WHERE user = '{$user}'";
                $result = mysqli_query($conn, $select_sql);
                $row = $result->fetch_assoc();
                $type = $row['type'];
                $level = $row['level'];
            ?>
            <div class="nav-t nav-padd">
            <!-- Brand and toggle get grouped for better mobile display -->
                <div class="form-inline">
                    <a class="navbar-brand" href="index.php"><h1 class="textcolor"><img class="align-content header-imgSize" src="Images/logo.jpg" alt="Web Class"></h1></a>
                    <h2 class="white username">Hello, <?=$user?></h2>
                </div>
                <button class="navbar-toggler backwhite" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                </button>
            </div>
                    
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse nav-t" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li id="home-nav" class="nav-item active"><a href="index.php" class="nav-link">Videos</a></li>
                    <li id="documents-nav" class="nav-item"><a href="documents.php" class="nav-link">Documents</a></li>
                    <li id="homeworks-nav" class="nav-item"><a href="homeworks.php" class="nav-link">Homework</a></li>
                    <?php
                        if($type == 'teacher'){
                            echo '<li id="users-nav" class="nav-item"><a href="users.php" class="nav-link">Users</a></li>';
                        }
                    ?>
                    <li id="logout" class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </header>