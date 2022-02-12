function common_for_tma_scripts(page) {
    // E' importante che prima ci sia il table setup e poi il change state setup
    table_setup(page);
    change_state_setup(page);
    $("#call_legenda").on("click", function () {
        $("#modal_legenda").modal("toggle")
    })
}

function technician_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        focus_nav_tab("technician");
        common_for_tma_scripts(GLOBAL.PAGES['TECHNICIAN'])

        $("#activities_change_btn").removeClass("px-1");

    });
}

function manager_script() {
    $('document').ready(function () {
        // Evidenzio il tab manager nella navbar
        focus_nav_tab("manager");
        common_for_tma_scripts(GLOBAL.PAGES['MANAGER'])
    });

}

function administrative_activity_script() {
    $('document').ready(function () {
        // Evidenzio il tab administrative nella navbar
        focus_nav_tab("administrative");
        common_for_tma_scripts(GLOBAL.PAGES['ADMINISTRATIVE'])
        $("#button_download_csv").on("click", function () {
            window.location.assign('/export/activity');
        })
    });
}

function show_activity_script() {
    $("document").ready(function () {
        $("#costumer").change(function () {
            filter_orders_when_costumer_selected();
        });
        $("#order").change(function () {
            filter_costumers_when_order_selected();
        });
        $("#costumer").change()
        $("[id='startTime'],[id='endTime']").change(function () {
            compare_and_switch_moments_if_necessary()
            compute_duration_in_activity_show();
        })
        $("#compute_duration").click(function () {
            $("#endTime").trigger("change")
        })
    });
}

function disable_edit_delete_button(id) {
    $("td > a.btn[id$=_" + id + "][id!=show_" + id + "]")
        .addClass('disabled')
        .children().removeClass('text-danger text-warning text-success');
}

function disable_report_button(id) {
    $("#send_report_" + id).addClass("disabled").children().removeClass("text-primary text-success text-warning")
}

function disable_row(id, administrative, accounted) {
    if (!administrative) {
        disable_edit_delete_button(id)
        $("td > div > input[id$=_" + id + "]").attr("disabled", true);
        $("td > div > select[id$=_" + id + "]").attr("disabled", true);
        disable_report_button(id);
    }
}

function table_setup(page) {
    let technician = page == GLOBAL.PAGES['TECHNICIAN'];
    let manager = page == GLOBAL.PAGES['MANAGER'];
    let administrative = page == GLOBAL.PAGES['ADMINISTRATIVE'];

    let num_selectable_rows = 0;

    if (!administrative) {

        // Modifico possibili azioni e colori per ogni attività
        $("tbody tr td[id^=state]").each(function () {
            let id = getSuffix($(this), "state_"); // Prendo l'activity_id dalla prima colonna della riga di appartenenza
            let state_id = parseInt($(this).attr("data-state-id"));
            let description_td = $("#desc_" + id);
            switch (state_id) {
                case GLOBAL.ACTIVITY_STATES['COMPLETE']:
                    description_td.addClass('text-primary', 1000); // Coloro di blu la descrizione dell'attività
                    $(this).addClass('text-primary'); // Coloro di blu lo stato dell'attività
                    num_selectable_rows++;
                    break
                case GLOBAL.ACTIVITY_STATES['OPEN']:
                    // Non faccio nulla
                    num_selectable_rows++;
                    break
                case GLOBAL.ACTIVITY_STATES['CANCELLED']:
                    disable_report_button(id);
                    description_td.addClass('text-secondary', 1000); // Coloro di grigio la descrizione dell'attività
                    $(this).addClass('text-secondary'); // Coloro di grigio lo stato dell'attività
                    num_selectable_rows++;
                    break
                case GLOBAL.ACTIVITY_STATES['APPROVED']:
                    if (!manager) {
                        disable_row(id)
                    } else {
                        let row = $("#activity_row_" + id)
                        if (row.attr("data-accounted") == GLOBAL.ACTIVITY_ACCOUNTED_STATES['NOT_ACCOUNTED']) {
                            num_selectable_rows++
                        }
                    }
                    description_td.addClass('text-success', 1000); // Coloro di verde la descrizione dell'attività
                    $(this).addClass('text-success'); // Coloro di verde lo stato dell'attività
                    break
                default:
                    console.log("Stato attività non corretto (Table setup)")
            }
        });

        $("[id^='send_report_']").children().filter(function () {
            return $(this).attr('data-report-to-send') == 1
        }).each(function () {
            let id = getSuffix($(this).parent(), 'send_report_')
            $("#activity_row_" + id).css("background-color", YELLOW_COLOR)
        })

        $("[id^=send_report_]").on("click", function () {
            activity_change($(this), "send_report_", GLOBAL.AJAX_METHODS['activity_send_report_index']);
        });

        $("[id^=billable_duration_]").on("change", function () {
            activity_change($(this), "billable_duration_input_", GLOBAL.AJAX_METHODS['activity_change_billable_duration_index']);
        });

        $("[id^=billing_state_select_]").on("change", function () {
            activity_change($(this), "billing_state_select_", GLOBAL.AJAX_METHODS['activity_change_billing_state_index']);
        })
    }

    $("[id^=activity_row_]").each(function () {
        let row = $(this);
        if (row.attr("data-accounted") == GLOBAL.ACTIVITY_ACCOUNTED_STATES['ACCOUNTED']) {
            disable_row(getSuffix($(this), "activity_row_"), administrative, true);
            row.css("background-color", GREEN_COLOR);
        }
        if (administrative) {
            num_selectable_rows++;
        }
    });

    GLOBAL.NUM_SELECTABLE_ROWS = num_selectable_rows;

    attach_table_tools("#master_tbody",
        "#master_search",
        "#master_num_rows",
        "#pagination_selector_1");

    filter_setup();

}


function attach_table_tools(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id) {
    let num_rows_chosen = localStorage["num_rows"];
    if (num_rows_chosen) {
        $(select_num_rows_id).val(num_rows_chosen)
    }

    $(search_field_id).on("keyup", function () {
        search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id);
    });
    $(select_num_rows_id).on("change", function () {
        search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id);
    })

    change_pag_function_to_call = function f(method, clicked_element) {
        if (!clicked_element.hasClass("disabled")) {
            search_in_table(tbody_id, $(search_field_id).val());
            change_pag(method, clicked_element, save_current_page_element_id, tbody_id, select_num_rows_id);
        }
    }

    $("#pagination_back").on("click", function () {
        change_pag_function_to_call(0, $(this));
    });
    $('#pagination_selector_1').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $('#pagination_selector_2').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $('#pagination_selector_3').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $("#pagination_forward").on("click", function () {
        change_pag_function_to_call(3, $(this));
    });

    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

function search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id) {
    console.log("{function: search_and_pagination}");
    localStorage["num_rows"] = $(select_num_rows_id).val();
    search_in_table(tbody_id, $(search_field_id).val());
    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

function search_in_table(tbody_id, input) {
    console.log("{function: search_in_table}");
    let value = input.toUpperCase();
    console.log("{input: " + value + "}");
    $(tbody_id + " tr").filter(function () {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1);
    });
}

function pagination(tbody_id, select_num_rows_id, save_current_page_element_id) {
    let n_per_page, rows, current_page, n_pages, a, b;
    n_per_page = Number($(select_num_rows_id).val());
    rows = $(tbody_id + " tr:visible");
    current_page = Number($(save_current_page_element_id).attr("data-current"));
    n_pages = Math.ceil(rows.length / n_per_page);

    if (current_page > n_pages) {
        current_page = n_pages == 0 ? 1 : n_pages
        $(save_current_page_element_id).attr("data-current", current_page)
    }

    let back = $("#pagination_back");
    let sel_1 = $('#pagination_selector_1');
    let sel_2 = $('#pagination_selector_2');
    let sel_3 = $('#pagination_selector_3');
    let forward = $("#pagination_forward");

    console.log("{num_rows: " + n_per_page + ", " +
        "total: " + rows.length + ", " +
        "n_pages: " + n_pages + ", " +
        "current_page: " + current_page + "}");

    // Manca il caso in cui la current_page è maggiore del n_pages

    // Abilita o disabilita forward e back pagination e costruisce la navbar delle pagine
    if (current_page == n_pages) {
        forward.addClass('disabled');
        b = true;
    } else {
        forward.removeClass('disabled');
        b = false;
    }
    if (current_page == 1) {
        back.addClass('disabled');
        sel_1.addClass("active").children().first().text(1);
        a = true;
    } else {
        back.removeClass('disabled');
        a = false;
    }

    if (a && b || rows.length === 0) {
        sel_2.toggle(false);
        sel_3.toggle(false);
    } else if (!a && !b) {
        sel_1.removeClass("active").children().first().text(current_page - 1);
        sel_2.addClass("active").toggle(true).children().first().text(current_page);
        sel_3.removeClass("active").toggle(true).children().first().text(current_page + 1);
    } else if (a) {
        sel_2.removeClass("active").toggle(true).children().first().text(2);
        if (3 > n_pages) {
            sel_3.toggle(false);
        } else {
            sel_3.removeClass("active").toggle(true).children().first().text(3);
        }
    } else {
        if (current_page - 2 > 0) {
            sel_1.removeClass("active").children().first().text(current_page - 2);
            sel_2.removeClass("active").toggle(true).children().first().text(current_page - 1);
            sel_3.addClass("active").toggle(true).children().first().text(current_page);
        } else {
            sel_1.removeClass("active").children().first().text(1);
            sel_2.addClass("active").toggle(true).children().first().text(2);
        }
    }

    rows.each(function (index) {
        if (index < (current_page - 1) * n_per_page || index > (current_page * n_per_page)) {
            $(this).toggle(false);
        } else {
            $(this).toggle(true);
        }
    })
}

function change_pag(method, clicked_element, save_current_page_element_id, tbody_id, select_num_rows_id) {
    console.log("{function: change_pag}");
    let new_current;
    let current = $(save_current_page_element_id);
    if (method == 0) {
        new_current = Number(current.attr("data-current")) - 1;
    } else if (method == 1) {
        new_current = clicked_element.children().first().text();
    } else {
        new_current = Number(current.attr("data-current")) + 1;
    }
    current.attr("data-current", new_current);
    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}


function filter_setup() {

    $("#master_period_filter").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            // $("#master_date_filter").prop("disabled", false);
        } else {
            // $("#master_date_filter").prop("disabled", true);
            $("[id^='master_date_filter']").val("")
        }
    })
    $("[id^='master_date_filter']").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            // $("#master_period_filter").prop("disabled", false);
        } else {
            let master_period_filter = $("#master_period_filter");
            // master_period_filter.prop("disabled", true);
            for (let i = 0; i < master_period_filter.length; i++) {
                master_period_filter[i].selectedIndex = 0;
            }
        }
        let start_date_str = $("#master_date_filter1").val()
        let end_date_str = $("#master_date_filter2").val()
        let start_date = moment(start_date_str)
        let end_date = moment(end_date_str)
        if (end_date.diff(start_date) < 0) {
            $("#master_date_filter1").val(end_date_str)
            $("#master_date_filter2").val(start_date_str)
        }
    })

    let pathname = window.location.pathname;
    if (pathname.includes("filter")) {
        let period = localStorage["master_period_filter"];
        if (period) {
            if (period !== "") {
                $("#master_date_filter").prop('disabled', true)
            } // Se il periodo è settato disabilita il filtro sulla data
            $("#master_period_filter option[value=" + period + "]").prop("selected", true);
        } else {
            $("#master_period_filter").children().first().prop('selected', true)
        }
        let costumer = localStorage["master_costumer_filter"];
        if (costumer) {
            $("#master_costumer_filter option[value=" + costumer + "]").prop("selected", true);
        }
        let state = localStorage["master_state_filter"];
        if (state) {
            $("#master_state_filter option[value=" + state + "]").prop("selected", true);
        }
        let user = localStorage["master_user_filter"];
        if (user) {
            $("#master_user_filter option[value=" + user + "]").prop("selected", true);
        }
        let billing_state = localStorage["master_billing_state_filter"];
        if (billing_state) {
            $("#master_billing_state_filter option[value=" + billing_state + "]").prop("selected", true);
        }
        let date = localStorage["master_date_filter1"];
        if (date) {
            if (date !== "") {
                // $("#master_period_filter1").prop('disabled', true)
            } // Se la data è settata disabilita il filtro sul periodo
            $("#master_date_filter1").val(date);
        }
        date = localStorage["master_date_filter2"];
        if (date) {
            if (date !== "") {
                // $("#master_period_filter").prop('disabled', true)
            } // Se la data è settata disabilita il filtro sul periodo
            $("#master_date_filter2").val(date);
        }
        let order_state = localStorage["master_order_state_filter"];
        if (order_state) {
            $("#master_order_state_filter option[value=" + order_state + "]").prop("selected", true);
        }
    } else {
        filter_reset();
    }

}

function save_filters() {
    console.log("{function: save_filters}");
    localStorage["master_period_filter"] = $("#master_period_filter").val();
    localStorage["master_costumer_filter"] = $("#master_costumer_filter").val();
    localStorage["master_state_filter"] = $("#master_state_filter").val();
    localStorage["master_date_filter1"] = $("#master_date_filter1").val();
    localStorage["master_date_filter2"] = $("#master_date_filter2").val();
    localStorage["master_user_filter"] = $("#master_user_filter").val();
    localStorage["master_billing_state_filter"] = $("#master_billing_state_filter").val();
    localStorage["master_order_state_filter"] = $("#master_order_state_filter").val();
    $("#master_filter_form").submit();
}

function filter_reset() {
    localStorage.removeItem("master_period_filter");
    localStorage.removeItem("master_costumer_filter");
    localStorage.removeItem("master_state_filter");
    localStorage.removeItem("master_date_filter1");
    localStorage.removeItem("master_date_filter2");
    localStorage.removeItem("master_user_filter");
    localStorage.removeItem("master_billing_state_filter");
    localStorage.removeItem("master_order_state_filter");
}


function change_activity_send_report_button(id_prefix, activity_id) {
    let report_button = $("[id=" + id_prefix + activity_id + "]");
    let row = $("[id=activity_row_" + activity_id + "]");
    if (report_button.attr("data-report-sent") == GLOBAL.REPORT_SENT["sent"]) {
        report_button.html("<i class=\"bi bi-send-fill text-primary\"></i>");
        report_button.attr("data-report-sent", GLOBAL.REPORT_SENT["not_sent"]);
        row.css("background-color", YELLOW_COLOR)
    } else {
        report_button.html("<i class=\"bi bi-send-check-fill text-success\"></i>");
        report_button.attr("data-report-sent", GLOBAL.REPORT_SENT["sent"]);
        row.css("background-color", "")
    }

}

function activity_change(element, id_prefix, ajax_method) {
    let activity_id = getSuffix(element, id_prefix);
    let value = element.val();
    switch (ajax_method) {
        case GLOBAL.AJAX_METHODS['activity_change_billing_state_index']:
            break
        case GLOBAL.AJAX_METHODS['activity_change_billable_duration_index']:
            break
        case GLOBAL.AJAX_METHODS['activity_send_report_index']:
            break
    }
    $("[id=" + waiter_prefix + id_prefix + activity_id + "]").show();

    $.ajax({
        url: '/ajax/activity/change',
        type: 'GET',
        data: {activity_id: activity_id, value: value, ajax_method: ajax_method},
        success: function (data) {
            $("[id=" + waiter_prefix + id_prefix + activity_id + "]").hide();
            if (ajax_method == GLOBAL.AJAX_METHODS['activity_send_report_index'] && data) {
                change_activity_send_report_button(id_prefix, activity_id);
            }
        }
    });
}

function activities_change(element, ajax_method) {
    let activity_ids = checked_activity_ids;
    let value;
    value = element.attr("data-state");
    switch (ajax_method) {
        case GLOBAL.AJAX_METHODS['activities_change_accounted_index']:
            break
        case GLOBAL.AJAX_METHODS['activities_change_accounted_index']:
            break
    }

    $.ajax({
        url: '/ajax/activities/change',
        type: 'GET',
        data: {activity_ids: activity_ids, value: value, ajax_method: ajax_method},
        success: function (data) {
            if (data) {
                location.reload();
            } else {
                console.log("error")
            }
        }
    });
}


function change_state_setup(page) {
    checked_activity_ids = [];
    $("input:checkbox[id^=check_select_]").on("change", function () {
        activity_checked($(this), page == GLOBAL.PAGES['ADMINISTRATIVE']);
    });
    $("#universal_activity_check_best_evvah_in_the_world").on("change", function () {
        if ($(this).prop("checked")) {
            $("input:checkbox[id^=check_select_]").not(":disabled").prop("checked", true).each(function () {
                let activity_id = getSuffix($(this), "check_select_")
                change_checked_activity_ids(activity_id)
            })
        } else {
            $("input:checkbox[id^=check_select_]").not(":disabled").prop("checked", false).each(function () {
                let activity_id = getSuffix($(this), "check_select_")
                change_checked_activity_ids(activity_id, true)
            })
        }
    })

    $("[id^=activities_change_]").not("[id=activities_change_btn]").on("click", function () {
        if (page == GLOBAL.PAGES['ADMINISTRATIVE']) {
            activities_change($(this), GLOBAL.AJAX_METHODS['activities_change_accounted_index']);
        } else {
            activities_change($(this), GLOBAL.AJAX_METHODS['activities_change_state_index']);
        }
    });

}

function activity_checked(check) {
    let activity_id = getSuffix(check, "check_select_");
    if (check.prop("checked")) {
        change_checked_activity_ids(activity_id)
    } else {
        change_checked_activity_ids(activity_id, true)
    }
}

function change_checked_activity_ids(activity_id, remove) {
    let current_row = $("[id=activity_row_" + activity_id + "]");
    let index_of_activity = checked_activity_ids.indexOf(activity_id)
    if (remove) {
        if (index_of_activity !== -1) {
            current_row.removeClass("table-active");
            checked_activity_ids.splice(index_of_activity, 1)
        }
    } else {
        if (index_of_activity === -1) {
            checked_activity_ids.push(activity_id);
            current_row.addClass("table-active");
        }
    }

    if (checked_activity_ids.length >= 1) {
        $("#activities_change_btn").fadeIn("fast");
        $("#activities_change_main").fadeIn("fast");
    } else if (checked_activity_ids.length === 0) {
        $("#activities_change_main").fadeOut("fast");
        $("#activities_change_btn").fadeOut("fast");
    }

    if (checked_activity_ids.length === GLOBAL.NUM_SELECTABLE_ROWS) {
        $("#universal_activity_check_best_evvah_in_the_world").prop("indeterminate", false);
        $("#universal_activity_check_best_evvah_in_the_world").prop("checked", true)
    } else if (checked_activity_ids.length === 0) {
        $("#universal_activity_check_best_evvah_in_the_world").prop("indeterminate", false);
        $("#universal_activity_check_best_evvah_in_the_world").prop("checked", false)
    } else {
        $("#universal_activity_check_best_evvah_in_the_world").prop("indeterminate", true);
    }
}


function filter_orders_when_costumer_selected() {
    console.log("{function: costumer_selected_activity}");
    let costumer = $("#costumer").val();
    console.log("{costumer_id: " + costumer + "}");
    $.ajax({
        url: '/ajax/ordersByCostumer',
        type: 'GET',
        data: {costumer_id: costumer},
        success: function (data) {
            let order_select = $('#order');
            order_select.find('option').remove().end();
            $.each(data, function () {
                order_select.append($("<option />").val(this.id).text(this.descrizione_commessa));
            });
        }
    });
}

function filter_costumers_when_order_selected() {
    console.log("{function: order_selected_activity}");
    var costumer = $('#costumer').val();
    var order = $("#order").val();
    console.log("{order_id: " + order + "}");
    $.ajax({
        url: '/ajax/costumerByOrder',
        type: 'GET',
        data: {order_id: order},
        success: function (data) {
            $("#costumer").val(data.id);
        }
    });
}

function compute_duration_in_activity_show() {
    let start_time = $("#startTime").val();
    let end_time = $("#endTime").val();
    if (end_time !== "") {
        start_time = moment(start_time, "HH:mm");
        end_time = moment(end_time, "HH:mm");

        let duration = moment.duration(end_time.diff(start_time));

        let hours = Math.abs(parseInt(duration.asHours()));
        let minutes = parseInt(duration.asMinutes()) % 60;

        $("#duration").val(("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2))
    }
}

function compare_and_switch_moments_if_necessary() {
    let start_time_string = $("#startTime").val();
    let end_time_string = $("#endTime").val();
    if (end_time_string !== "") {
        let start_time = moment(start_time_string, "hh:mm");
        let end_time = moment(end_time_string, "hh:mm");
        if (end_time.diff(start_time) < 0) {
            $("#startTime").val(end_time_string)
            $("#endTime").val(start_time_string);
        }

    }

}
