

function commercial_script() {
    $('document').ready(function () {
        // Evidenzio il tab commercial nella navbar
        $("#commercial_nav_tab").children().addClass("active");

        // Abilito azioni switch report
        $("[id^=report_switch_]").on("change", function () {
            order_change($(this), "report_switch_", AJAX_METHODS.order_change_report_index);
        });

        // Abilito le azioni select state
        $("[id^=order_state_select_]").on("change", function () {
            order_change($(this), "order_state_select_", AJAX_METHODS.order_change_state_index);
        });
    });
}

function order_change(element, id_prefix, ajax_method) {
    let order_id = getSuffix(element, id_prefix);
    let value;
    switch (ajax_method) {
        case AJAX_METHODS.order_change_report_index:
            value = element.is(":checked") ? 1 : 0;
            break
        case AJAX_METHODS.order_change_state_index:
            value = element.val();
            break
    }
    $("[id=" + waiter_prefix + id_prefix + order_id + "]").show();

    $.ajax({
        url: '/ajax/order/change',
        type: 'GET',
        data: {order_id: order_id, value: value, ajax_method: ajax_method},
        success: function (data) {
            $("[id=" + waiter_prefix + id_prefix + order_id + "]").hide();
        }
    });
}
