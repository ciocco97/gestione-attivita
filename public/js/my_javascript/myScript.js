/* Pagina di autenticazione */

function costumer_selected_activity() {
    console.log("{function: costumer_selected_activity}");
    var costumer = $('#costumer').val();
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
            })
        }
    });
}

function order_selected_activity() {
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

function pagination() {
    console.log("{function: pagination}");
    search_in_table($("#master_search").val());
    var n_per_page, rows, current_page, n_pages, a, b;
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
    pagination();
}

function search_in_table(input) {
    console.log("{function: search_in_table}");
    var value = input.toUpperCase();
    console.log("{input: "+value+"}");
    $("#master_tbody tr").filter(function () {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1);
    });
}

function attach_search() {
    console.log("{function: attach_search}");
    $("#master_search").on("keyup", function () {
        pagination()
    });
}

function save_filters() {
    console.log("{function: save_filters}");
    localStorage["master_period_filter"] = $("#master_period_filter").val();
    localStorage["master_costumer_filter"] = $("#master_costumer_filter").val();
    localStorage["master_state_filter"] = $("#master_state_filter").val();
    localStorage["master_date_filter"] = $("#master_date_filter").val();
    $("#master_filter_form").submit();
}

function get_filters() {
    var period = localStorage["master_period_filter"];
    if (period) {
        $("#master_period_filter option[value="+period+"]").prop("selected", true);
        localStorage.removeItem("master_period_filter");
    }
    var costumer = localStorage["master_costumer_filter"];
    if (costumer) {
        $("#master_costumer_filter option[value="+costumer+"]").prop("selected", true);
        localStorage.removeItem("master_costumer_filter");
    }
    var state = localStorage["master_state_filter"];
    if (state) {
        $("#master_state_filter option[value="+state+"]").prop("selected", true);
        localStorage.removeItem("master_state_filter");
    }
    var date = localStorage["master_date_filter"];
    if (date) {
        $("#master_date_filter").val(date);
        localStorage.removeItem("master_date_filter");
    }
}
