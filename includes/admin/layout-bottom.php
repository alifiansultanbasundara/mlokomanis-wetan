</main>

<?php include APP_PATH . 'includes/admin/footer.php'; ?>

</div>

</div>

<?php include APP_PATH . 'includes/scripts.php'; ?>

</body>

</html>

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.store('sidebar', {

            open: JSON.parse(localStorage.getItem('sidebar-open') ?? 'true'),

            toggle() {
                this.open = !this.open;
                localStorage.setItem('sidebar-open', JSON.stringify(this.open));
            }

        });

    });
</script>