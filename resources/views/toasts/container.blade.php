<div aria-live="polite" aria-atomic="true">
    <!-- Position it: -->
    <!-- - `.toast-container` for spacing between toasts -->
    <!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
    <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->

    <!-- button to initialize toast -->
    <button type="button" class="btn btn-primary" id="toastbtn">Initialize toast</button>

    <div class="toast-container position-fixed end-0 bottom-0 p-3">

        <!-- Toast -->
        <div class="toast" role="status" data-bs-delay="2000">
            <div class="toast-header">
                <img class="rounded me-2" alt="...">
                <strong class="me-auto">Messaggio</strong>
                <small>Ora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById("toastbtn").onclick = function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            // Creates an array of toasts (it only initializes them)
            return new bootstrap.Toast(toastEl) // No need for options; use the default options
        });
        toastList.forEach(toast => toast.show()); // This show them

        console.log(toastList); // Testing to see if it works
    };

</script>

{{--<script>--}}
{{--    var toastElList = [].slice.call(document.querySelectorAll('.toast'))--}}
{{--    var toastList = toastElList.map(function (toastEl) {--}}
{{--        return new bootstrap.Toast(toastEl)--}}
{{--    })--}}
{{--</script>--}}
