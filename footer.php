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
                if(page=="index"){
                    items.removeClass("active");
                    home.addClass("active");
                }
                else if(page==""){
                    items.removeClass("active");
                    home.addClass("active");
                }
                else if(page=="users" || page=="usersAdd" || page=="usersEditData"){
                    items.removeClass("active");
                    user.addClass("active");
                }
                else if(page=="documents" || page=="documentsAdd" || page=="documentsEditData"){
                    items.removeClass("active");
                    documents.addClass("active");
                }
                else if(page=="homeworks" || page=="answersView" || page=="answersAdd" || page=="homeworksAdd" || page=="homeworksEditData"){
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