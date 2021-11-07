<?php

namespace App\Http\Controllers;

class Shared
{
    const METHODS = array(
        'SHOW' => 0,
        'EDIT' => 1,
        'DELETE' => 2,
        'ADD' => 3
    );

    const PAGES = array(
        'TECHNICIAN' => 0,
        'MANAGER' => 1,
        'ADMINISTRATIVE' => 2,
        'COMMERCIAL' => 3,
        'ADMINISTRATOR' => 4
    );

    const ROLES = array(
        'MANAGER' => 3,
        'ADMINISTRATIVE' => 1,
        'COMMERCIAL' => 2,
        'ADMINISTRATOR' => 4
    );

    const ACTIVITY_STATES = array(
        'COMPLETE' => 1,
        'OPEN' => 2,
        'CANCELLED' => 3,
        'APPROVED' => 4
    );

    const ACTIVITY_BILLING_STATES = array(
        'TO_BILL' => 1,
        'CONTRACT' => 2,
        'NOT_BILLABLE' => 3
    );

    const ACTIVITY_ACCOUNTED_STATES = array(
        'NOT_ACCOUNTED' => 1,
        'ACCOUNTED' => 2
    );


    const FILTER_ACCOUNTED = array(
        'ACCOUNTED' => 10,
        'NOT_ACCOUNTED' => 11
    );

    const FILTER_TEAM = array(
        'TEAM_MEMBER_NOT_SELECTED' => -2,
    );

    const FILTER_PERIOD = array(
        'CURRENT_WEEK' => 1,
        'CURRENT_TWO_WEEKS' => 2,
        'CURRENT_MONTH' => 3,
        'LAST_MONTH' => 4,
        'ALL' => 5
    );

//    Sto parametrizzando i valori dei filtri
//    Ma quello che stavo cercando di capire è come mai se dalla pagina manager
//    filtro senza selezionare un team member, mi fa lo stesso filtro del tecnico
//    (vedo solamente le attività del curfrent user)

}
