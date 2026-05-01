<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.min.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
<script>
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    if (urlParams.get('page')) {
        document.querySelectorAll('.nav-link').forEach(function(value, index) {
            if (value.id == urlParams.get('page')) {
                value.style.color = "#4154F1";
                value.style.backgroundColor = "#F6F9FF";
                value.classList.remove("collapsed");
                value.setAttribute("aria-expanded", "false");

                value.nextElementSibling.classList.add("show");
                for (let item of value.nextElementSibling.children) {
                    if (urlParams.get('item') == item.children[0].id) {
                        item.children[0].classList.add("active");
                    }
                }
            } else {
                value.style.color = "#012960";
                value.style.backgroundColor = "#FFFFFF";
            }
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // simpan semua input otomatis
        document.querySelectorAll("input, select, textarea").forEach(function(el) {

            el.addEventListener("input", function() {
                if (el.type === 'checkbox') {
                    let saved = JSON.parse(localStorage.getItem(el.name) || '[]');
                    if (el.checked) {
                        if (!saved.includes(el.value)) saved.push(el.value);
                    } else {
                        saved = saved.filter(v => v !== el.value);
                    }
                    localStorage.setItem(el.name, JSON.stringify(saved));
                    return;
                }

                // ✅ TAMBAH INI
                if (el.type === 'radio') {
                    if (el.checked) {
                        localStorage.setItem(el.name, el.value);
                    }
                    return;
                }

                if (el.name) {
                    localStorage.setItem(el.name, el.value);
                }
            });

        });

        // load ulang saat halaman dibuka
        document.querySelectorAll("input, select, textarea").forEach(function(el) {
            if (el.name) {
                let value = localStorage.getItem(el.name);
                if (el.type === 'file') return;

                if (el.type === 'checkbox') {
                    let saved = [];
                    try {
                        saved = JSON.parse(localStorage.getItem(el.name) || '[]');
                        if (!Array.isArray(saved)) saved = [];
                    } catch (e) {
                        localStorage.removeItem(el.name);
                    }
                    if (saved.includes(el.value)) {
                        el.checked = true;
                    }
                    return;
                }

                // ✅ TAMBAH INI
                if (el.type === 'radio') {
                    if (value && el.value === value) {
                        el.checked = true;
                    }
                    return;
                }

                if (value) {
                    el.value = value;
                }
            }
        });

    });
</script>

</body>

</html>