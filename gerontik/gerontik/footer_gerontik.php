<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.querySelector('select[name="identitas_id"]');
        var hiddenInput = document.getElementById('hidden-id-identitas');
        if (dropdown && hiddenInput) {
            // Set hidden input saat load
            hiddenInput.value = dropdown.value;
            // Update hidden input setiap dropdown berubah
            dropdown.addEventListener('change', function() {
                hiddenInput.value = this.value;
            });
        }
    });
</script>