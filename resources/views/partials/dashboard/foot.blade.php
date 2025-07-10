<script>
    let imagesBasePath = "{{ asset('/storage/Images') }}";
    let currency = " {{ __(settings()->getSettings('currency')) }} ";
    let locale = "{{ getLocale() }}";
    let ordersStatuses = @json(settings()->getOrdersStatus());
    var faviconPath = '{{ asset(getImagePathFromDirectory(settings()->getSettings('favicon'), 'Settings')) }}';
</script>
<script src="{{ asset('dashboard-assets/plugins/global/plugins.bundle.js') }}"></script>

<script src="{{ asset('dashboard-assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('js/dashboard/translations.js') }}"></script>
<script src="{{ asset('js/dashboard/global_scripts.js') }}"></script>
<script src="{{ asset('dashboard-assets/js/favicon-badge.js') }}"></script>
<script>
    $(window).on('load', function() {
        setTimeout(() => $("#loading-div").hide(), 200);
    });
</script>
<script>
    var alertUploadFileHtml = `{!! alertUploadFileHtml() !!}`
    if ($(`input[type="file"]`).length > 0) {
        $(`#dashboardContent`).prepend(alertUploadFileHtml);
    }
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-analytics.js"></script>
<script src="{{ asset('js/dashboard/listen-to-firebase-notification.js') }}"></script>
<script>
    function handleImageChange(name) {
        const input = document.querySelector(`input[name="${name}"]`);
        const preview = document.querySelector(`.preview-${name}`);

        if (input && input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.backgroundImage = `url('${e.target.result}')`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetToOriginalImage(name) {
        const input = document.querySelector(`input[name="${name}"]`);
        const preview = document.querySelector(`.preview-${name}`);
        const originalImage = preview.getAttribute('data-original');

        if (input) input.value = ''; // Clear uploaded file
        if (preview && originalImage) {
            preview.style.backgroundImage = `url('${originalImage}')`;
        }
    }
</script>

@stack('scripts')
