<?php if ($headerfooter) { ?>
    </div>
<?php } ?>

</main>
<?php if ($headerfooter) { ?>
    <div class="container text-center">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center"> <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1" aria-label="Bootstrap"> </a> <span class="mb-3 mb-md-0 text-body-secondary">
                    <p>Made in 2025 by Joey Peter</p>
                </span> </div>
        </footer>
    </div>
    <?php
    if (isLoggedIn()) {
        ?>
        <script src="/js/scripts.js"></script>
        <script src="/js/functions.js"></script>
    <?php } ?>
<?php } ?>
<script src ="/js/base.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<?php
if (isset($customjs) && $customjs !== 'null') {
    ?>
    <script type="text/javascript" src="/js/<?php echo $customjs ?>"></script>
    <?php
}
?>
</body>
</html>
