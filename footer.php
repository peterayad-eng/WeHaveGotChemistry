	<footer class="backwhite bottom">
		<p class="p4 center copy nomargin"><a href='https://persona-eg.com/' target="_blank" class='footera'>Copyright © 2020 Persona-eg. All rights reserved.</a></p>
	</footer>	
		
		<script src="Bootstrap4.4.1/jquery-3.4.1.min.js"></script>
        <script src="Bootstrap4.4.1/popper.min.js"></script>
        <script src="Bootstrap4.4.1/js/bootstrap.min.js"></script>
		<script src="JS/wow.min.js"></script>

		<script>
            //Initiate WOW plugin
            new WOW().init();
        </script>

		<script>
            // Navbar change active class between pages
            var home = $("#home-nav");
            var documents = $("#documents-nav");
            var homework = $("#homeworks-nav");
            var user = $("#users-nav");
            var items = $(".nav-item")
            var path = window.location.pathname;
            var page = path.split("/").pop();
            $(document).ready(function() {
                if(page=="index.php"){
                    items.removeClass("active");
                    home.addClass("active");
                }
                else if(page==""){
                    items.removeClass("active");
                    home.addClass("active");
                }
                else if(page=="users.php" || page=="usersAdd.php" || page=="usersEditData.php"){
                    items.removeClass("active");
                    user.addClass("active");
                }
                else if(page=="documents.php" || page=="documentsAdd.php" || page=="documentsEditData.php"){
                    items.removeClass("active");
                    documents.addClass("active");
                }
                else if(page=="homeworks.php" || page=="answersView.php" || page=="answersAdd.php" || page=="homeworksAdd.php" || page=="homeworksEditData.php"){
                    items.removeClass("active");
                    homework.addClass("active");
                }
            })
		</script>

        <script>
            // Submit Form before delete item
            var d = document.getElementsByClassName('deleteButton');
            var confirmIt = function (e) {
                if (!confirm('Do you want to delete this item?')) e.preventDefault();
                };
            for (var i = 0, l = d.length; i < l; i++) {
                d[i].addEventListener('click', confirmIt, false);
                }
        </script>
	</body>
</html> 