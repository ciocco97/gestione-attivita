
function administrative_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#administrative_nav_tab").children().addClass("active");

        $("[id^=show_collapse_]").on("click", function () {
            show_collapse_order($(this).attr("data-order-id"));
        })
    });
}

function show_collapse_order(order_id) {

}
