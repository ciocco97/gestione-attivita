
function technician_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#technician_nav_tab").children().addClass("active");

        table_setup();

    });
}

function manager_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#manager_nav_tab").children().addClass("active");

        table_setup(true);

        checked_activity_ids = [];
        $("input:checkbox").on("change", function () { activity_checked($(this)); });

        $("[id^=activities_change_]").not("[id=activities_change_btn]").on("click", function(){ activities_approve_confirmation($(this)) });
    });

}

function activities_approve_confirmation(button) {
    let state = button.attr("data-state");
    let titles = $("#modal_confirmation_title").attr("data-titles").trim().replace(/(\r\n|\n|\r)/gm, "").split("-");
    titles.forEach((val, index) => titles[index] = val.trim());
    $("#modal_confirmation_title").text(titles[state-1]);
    $("#modal_confirmation_num_activities").text(checked_activity_ids.length);
    $("#modal_confirmation_confirm").on("click", function () { activities_change_state(state); });
    $("#modal_confirmation").modal("show");
}

function activities_change_state(state) {
    $.ajax({
        url: '/ajax/activity/mass/change',
        type: 'GET',
        data: { ids: checked_activity_ids, state: state },
        success: function (data) {
            location.reload();
        }
    });
}

function activity_checked(check) {
    let current_row = check.parent().parent().parent();
    let activity_id = current_row.children().first().text();
    if (check.prop("checked")) {
        checked_activity_ids.push(activity_id);
        current_row.addClass("table-active");
        if (checked_activity_ids.length === 1) {
            $("#activities_change_4").fadeIn("fast");
            $("#activities_change_btn").fadeIn("fast");
        }
    } else {
        checked_activity_ids = checked_activity_ids.filter(function (id) {
            return id !== activity_id;
        })
        current_row.removeClass("table-active");
        if (checked_activity_ids.length === 0) {
            $("#activities_change_4").fadeOut("fast");
            $("#activities_change_btn").fadeOut("fast");
        }
    }
}

function table_setup(manager = false) {
    // Modifico possibili azioni e colori per ogni attività
    $("tbody tr td[id^=state]").each(function () {
        let id = $(this).parent().children().first().text(); // Prendo l'activity_id dalla prima colonna della riga di appartenenza
        let state_value = $(this).text();
        let description_td = $("#desc_" + id);
        switch (state_value) {
            case "completata":
                description_td.addClass('text-primary', 1000); // Coloro di grigio la descrizione dell'attività
                $(this).addClass('text-primary'); // Coloro di blu lo stato dell'attività
                break
            case "aperta":
                // Non faccio nulla
                break
            case "annullata":
                $("#report_" + id).addClass('disabled') // Disabilito l'invio del rapportino
                    .children().removeClass('text-primary text-success text-danger'); // Scoloro il relativo bottone
                description_td.addClass('text-secondary', 1000); // Coloro di grigio la descrizione dell'attività
                $(this).addClass('text-secondary'); // Coloro di grigio lo stato dell'attività
                break
            case "approvata":
                if (!manager) {
                    $("a.btn[id$=" + id + "][id!=show_" + id + "][id!=report_" + id + "]").addClass('disabled') // Disabilito la modifica e l'eliminazione
                        .children().removeClass('text-danger text-warning'); // Scoloro i relativi bottoni
                }
                description_td.addClass('text-success', 1000); // Coloro di verde la descrizione dell'attività
                $(this).addClass('text-success'); // Coloro di verde lo stato dell'attività
                break
        }
    });

    // Aggiungo l'event listener alla barra di ricerca sopra la tabella
    $("#master_search").on("keyup", function () {
        search_and_pagination();
    });
    pagination(); // Effettuo la prima paginazione

    filter_setup();
}

// Nascondi nella tabella con id master_table le righe in cui non compare la stringa input
function search_in_table(input) {
    console.log("{function: search_in_table}");
    let value = input.toUpperCase();
    console.log("{input: "+value+"}");
    $("#master_tbody tr").filter(function () {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1);
    });
}

// Pagina la tabella con id master_table in base al valore nal campo con id master_num_rowsLa
// La pagina corrente verrà salvata nell'elemento con id pagination_selector_1
function pagination() {
    let n_per_page, rows, current_page, n_pages, a, b;
    n_per_page = Number($("#master_num_rows").val());
    rows = $("#master_tbody tr:visible");
    current_page = Number($("#pagination_selector_1").attr("data-current"));
    n_pages = Math.ceil(rows.length/n_per_page);

    console.log("{num_rows: "+n_per_page+", " +
        "total: "+rows.length+", " +
        "n_pages: "+n_pages+", " +
        "current_page: "+current_page+"}");

    // Manca il caso in cui la current_page è maggiore del n_pages

    // Abilita o disabilita forward e back pagination e costruisce la navbar delle pagine
    if(current_page == n_pages) {
        $("#pagination_forward").addClass('disabled');
        b=true;
    } else {
        $("#pagination_forward").removeClass('disabled');
        b=false;
    }
    if(current_page == 1) {
        $("#pagination_back").addClass('disabled');
        $('#pagination_selector_1').addClass("active").children().first().text(1);
        a=true;
    } else {
        $("#pagination_back").removeClass('disabled');
        a=false;
    }

    if(a && b || rows.length === 0) {
        $('#pagination_selector_2').toggle(false);
        $('#pagination_selector_3').toggle(false);
    } else if (!a && !b) {
        $('#pagination_selector_1').removeClass("active").children().first().text(current_page-1);
        $('#pagination_selector_2').addClass("active").toggle(true).children().first().text(current_page);
        $('#pagination_selector_3').removeClass("active").toggle(true).children().first().text(current_page+1);
    } else if (a) {
        $('#pagination_selector_2').removeClass("active").toggle(true).children().first().text(2);
        if(3>n_pages){$('#pagination_selector_3').toggle(false);}
        else {$('#pagination_selector_3').removeClass("active").toggle(true).children().first().text(3);}
    } else {
        if(current_page-2 > 0) {
            $('#pagination_selector_1').removeClass("active").children().first().text(current_page-2);
            $('#pagination_selector_2').removeClass("active").toggle(true).children().first().text(current_page-1);
            $('#pagination_selector_3').addClass("active").toggle(true).children().first().text(current_page);
        } else {
            $('#pagination_selector_1').removeClass("active").children().first().text(1);
            $('#pagination_selector_2').addClass("active").toggle(true).children().first().text(2);
        }
    }

    rows.each(function (index) {
        if (index<(current_page-1)*n_per_page || index>(current_page*n_per_page)) {
            $(this).toggle(false);
        } else {
            $(this).toggle(true);
        }
    })
}

// Cerca e poi pagina
function search_and_pagination() {
    console.log("{function: search_and_pagination}");
    search_in_table($("#master_search").val());
    pagination();
}

function change_pag(method, element) {
    console.log("{function: change_pag}");
    if (!element.hasClass("disabled")) {
        var new_current;
        if(method == 0){
            new_current = Number($("#pagination_selector_1").attr("data-current")) - 1;
        } else if (method == 1) {
            new_current = element.children().first().text();
        } else {
            new_current = Number($("#pagination_selector_1").attr("data-current")) + 1;
        }
        $("#pagination_selector_1").attr("data-current", new_current);
    }
    search_and_pagination();
}

function filter_setup() {

    $("#master_period_filter").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            $("#master_date_filter").prop("disabled", false);
        } else {
            $("#master_date_filter").prop("disabled", true);
        }
    })
    $("#master_date_filter").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            $("#master_period_filter").prop("disabled", false);
        } else {
            $("#master_period_filter").prop("disabled", true);
        }
    })

    let pathname = window.location.pathname;
    if (pathname.includes("filter")) {
        let period = localStorage["master_period_filter"];
        if (period) {
            if (period !== "") { $("#master_date_filter").prop('disabled', true) } // Se il periodo è settato disabilita il filtro sulla data
            $("#master_period_filter option[value="+period+"]").prop("selected", true);
        }
        let costumer = localStorage["master_costumer_filter"];
        if (costumer) {
            $("#master_costumer_filter option[value="+costumer+"]").prop("selected", true);
        }
        let state = localStorage["master_state_filter"];
        if (state) {
            $("#master_state_filter option[value="+state+"]").prop("selected", true);
        }
        let user = localStorage["master_user_filter"];
        if (user) {
            $("#master_user_filter option[value="+user+"]").prop("selected", true);
        }
        let date = localStorage["master_date_filter"];
        if (date) {
            if (date !== "") { $("#master_period_filter").prop('disabled', true) } // Se la data è settata disabilita il filtro sul periodo
            $("#master_date_filter").val(date);
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
    localStorage["master_date_filter"] = $("#master_date_filter").val();
    localStorage["master_user_filter"] = $("#master_user_filter").val();
    $("#master_filter_form").submit();
}

function filter_reset() {
    localStorage.removeItem("master_period_filter");
    localStorage.removeItem("master_costumer_filter");
    localStorage.removeItem("master_state_filter");
    localStorage.removeItem("master_date_filter");
    localStorage.removeItem("master_user_filter");
}

function show_activity_script() {
    $("document").ready(function () {
        $("#costumer").change(function () { filter_orders_when_costumer_selected(); });
        $("#order").change(function () { filter_costumers_when_order_selected(); });
    });
}

function filter_orders_when_costumer_selected() {
    console.log("{function: costumer_selected_activity}");
    var costumer = $("#costumer").val();
    console.log("{costumer_id: " + costumer + "}");
    $.ajax({
        url: '/ajax/orders',
        type: 'GET',
        data: {costumer_id: costumer},
        success: function (data) {
            var order_select = $('#order');
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
        url: '/ajax/costumer',
        type: 'GET',
        data: {order_id: order},
        success: function (data) {
            $("#costumer").val(data.id);
        }
    });
}

function change_password_script() {
    $("document").ready(function () {
        $("#password_validity_alert").toggle(false);
        $("#retype_password_alert").toggle(false);

        $("#newPassword").on('keyup', function () {
            check_password_validity($(this).val());
        });
        $("#retype_password").on('keyup', function () {
            check_password_equality($("#newPassword").val(), $(this).val())
        });
        $("change_password_button").on('click', function () {
            if (check_password_validity($("#newPassword").val()) &&
                check_password_equality($("#newPassword").val(), $("#retype_password").val())) {
                $("#change_password_form").submit();
            }
        });
    });
}

function check_password_validity(password) {
    let regular_expression = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/;
    let valid = password.search(regular_expression) !== -1;
    console.log(valid);
    $("#password_validity_alert").toggle(!valid && password !== "");
    return valid;
}

function check_password_equality(p_new, p_retipe) {
    let equal = p_new === p_retipe;
    $("#retype_password_alert").toggle(!equal && p_retipe !== "");
    return equal;
}

function nav_script() {
    $("document").ready(function () {
        console.log("{function: nav_script}");
        $.ajax({
            url: '/ajax/user/roles',
            type: 'GET',
            data: {},
            success: function (data) {
                if (!data.includes(3)) {
                    $("#manager_nav_tab").hide();
                }
                if (!data.includes(1)) {
                    $("#admin_nav_tab").hide();
                }
                if (!data.includes(2)) {
                    $("#commercial_nav_tab").hide();
                }
            }
        });
    });
}
