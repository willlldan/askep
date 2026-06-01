<script src="assets/vendor/sweetalert2/package/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.querySelector('select[name="identitas_id"]');
        var hiddenInput = document.getElementById('hidden-id-identitas');
        var mainForm = document.querySelector('form.needs-validation');
        var isDirty = false;
        var initialSnapshot = '';

        function snapshotForm(form) {
            return new URLSearchParams(new FormData(form)).toString();
        }

        if (dropdown && hiddenInput) {
            hiddenInput.value = dropdown.value;
            dropdown.addEventListener('change', function() {
                hiddenInput.value = this.value;
            });
        }

        if (mainForm) {
            initialSnapshot = snapshotForm(mainForm);

            var markDirty = function() {
                isDirty = snapshotForm(mainForm) !== initialSnapshot;
            };

            mainForm.addEventListener('input', markDirty);
            mainForm.addEventListener('change', markDirty);
            mainForm.addEventListener('submit', function() {
                isDirty = false;
            });

            document.querySelectorAll('.js-nav-tab').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (!isDirty) return;

                    e.preventDefault();
                    var targetUrl = link.getAttribute('href');

                    if (window.Swal) {
                        Swal.fire({
                            title: 'Perubahan belum disimpan',
                            text: 'Silakan simpan data terlebih dahulu agar perubahan tidak hilang. Apakah Anda tetap ingin melanjutkan ke section lain?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Tetap Pindah',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = targetUrl;
                            }
                        });
                    } else if (confirm('Perubahan belum disimpan. Tetap pindah section?')) {
                        window.location.href = targetUrl;
                    }
                });
            });
        }
    });
</script>
