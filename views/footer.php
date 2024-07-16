<!-- views/footer.php -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="/oficina/assets/js/bootstrap.min.js"></script>
<?php
if (isset($customJS)) {
    foreach ($customJS as $js) {
        echo "<script src='/oficina/assets/js/$js'></script>";
    }
}
?>
</body>
</html>
