/**
 * BlogCMS – app.js
 * Client-side enhancements.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-generate slug from title in category forms ──────
    var titleField = document.getElementById('post-title');
    var slugField  = document.getElementById('post-slug');
    if (titleField && slugField) {
        titleField.addEventListener('input', function () {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }

    // ── Bootstrap form validation feedback ───────────────────
    var forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // ── Auto-dismiss flash alerts after 5 seconds ────────────
    var alerts = document.querySelectorAll('.alert.alert-success, .alert.alert-info');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // ── Colour picker sync (category forms) ──────────────────
    var colorPicker = document.querySelector('input[type="color"]');
    var colorText   = document.getElementById('colorText');
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function () {
            colorText.value = this.value;
        });
    }
});
