function commercial_script() {
    $('document').ready(function () {
        // Evidenzio il tab commercial nella navbar
        focus_nav_tab("commercial");

        // Abilito azioni switch report
        $("[id^=report_switch_]").on("change", function () {
            order_change($(this), "report_switch_", GLOBAL.AJAX_METHODS['order_change_report_index']);
        });

        // Abilito le azioni select state
        $("[id^=order_state_select_]").on("change", function () {
            order_change($(this), "order_state_select_", GLOBAL.AJAX_METHODS['order_change_state_index']);
        });

        current_costumer_selected_setup()

        filter_setup()
    });
}

function current_costumer_selected_setup() {
    let current_selected_costumer = localStorage["current_selected_costumer"]
    let current = $("#costumer_number_"+current_selected_costumer)
    if (current_selected_costumer != -1 && current.length > 0) {
        $([document.documentElement, document.body]).animate({
            scrollTop: current.offset().top-100
        }, 200);
        $("#show_collapse_" + current_selected_costumer).trigger("click")
    }
    $("button[id^=show_collapse_]").on('click', function () {
        if ($(this).attr("aria-expanded") == "true") {
            localStorage["current_selected_costumer"] = getSuffix($(this), "show_collapse_")
        } else {
            localStorage["current_selected_costumer"] = -1;
        }
    })
}

function order_change(element, id_prefix, ajax_method) {
    let order_id = getSuffix(element, id_prefix);
    let value;
    switch (ajax_method) {
        case GLOBAL.AJAX_METHODS['order_change_report_index']:
            value = element.is(":checked") ? 1 : 0;
            break
        case GLOBAL.AJAX_METHODS['order_change_state_index']:
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
