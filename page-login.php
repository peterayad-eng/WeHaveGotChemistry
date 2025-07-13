<?php session_start(); ?>
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
        <meta name="robots" content="index, follow">
        
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
		<section id="fixedbg"></section>
        	<section id="content">
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
		                            <h2> Login To Your Web Class </h2>
		                        </div>
		                        <?php
		                            if(isset($_GET['error']) && $_GET['error'] == 1){
		                                echo "<div style='color:red'>The username or password isn't correct</div>";
		                            }
		                            else if(isset($_GET['error']) && $_GET['error'] == 2){
		                                echo "<div style='color:red'>You have exceeded the number of attempts. Please, try again later</div>";
		                            }
		
		                            function get_client_ip(){
		                                $str="Unknown";
		                                foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
		                                    if (array_key_exists($key, $_SERVER) === true){
		                                        foreach (explode(',', $_SERVER[$key]) as $ip){
		                                            $ip = trim($ip); 
		
		                                            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
		                                                $str = $key." is ".$ip."    ";;
		                                            }
		                                        }
		                                    }
		                                }
		                                return $str;
		                            }
		
		                            $ip = get_client_ip();
		                        ?>
		                        <form  action="loginsubmit" method="POST" enctype="multipart/form-data">
		                            <input id='ip' name='ip' value='<?=$ip?>' type='hidden'/>
		                            <div class="form-group">
		                                <label>User Name</label>
		                                <input type="text" class="form-control" placeholder="User Name" name="user" required>
		                            </div>
		                            <div class="form-group">
		                                <label>Password</label>
		                                <input type="password" class="form-control" placeholder="Password" name="pass" required>
		                            </div>
		                            <div class="verifyingcode"><input id="verifyingcode" name="verifyingcode" type="text" placeholder='Verifying code' class="verifyingcode"/></div>
		
		                            <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
		
		        <footer class="backwhite bottom">
		            <p class="p4 center copy nomargin"><a href='https://persona-eg.com/' target="_blank" class='footera'>Copyright © 2020 Persona-eg. All rights reserved.</a></p>
		        </footer>	
		</section>
		
		<script src="Bootstrap4.4.1/jquery-3.4.1.min.js"></script>
	        <script src="Bootstrap4.4.1/popper.min.js"></script>
	        <script src="Bootstrap4.4.1/js/bootstrap.min.js"></script>
	        <script src="JS/main.js"></script>
	</body>
</html>
