</div>

</main>
<script src="../assets/js/toggleSidebar.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<?php
    if (isset($scripts)) {
        foreach ($scripts as $script) {
            echo $script;
        }
    }
?>
</body>

</html>