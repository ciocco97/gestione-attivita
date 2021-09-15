<?php
session_start();
if (!$_SESSION['logged']) { header("location: index.php"); }
$username = $_SESSION['username'];

require_once 'utils/reuse_of_code.php';

spl_autoload_register(function($class) {
    include 'model/' . $class . '.class.php';
});

$VIEW_INDEX = 0;
$EDIT_INDEX = 1;
$DELETE_INDEX = 2;

$methods = array(
    $VIEW_INDEX => 'Visulizza',
    $EDIT_INDEX => 'Modifica',
    $DELETE_INDEX => 'Elimina'
);

$dataLayer = new DataLayer();

$http_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
if ($http_method == 'POST') {
    $id_activity = filter_input(INPUT_POST, 'id_activity');
    $cliente = filter_input(INPUT_POST, 'costumer'); // Non serve
    $commessa = filter_input(INPUT_POST, 'order');
    $data_e_oraInizio = filter_input(INPUT_POST, 'date_and_startTime');
    $data = substr($data_e_oraInizio, 0, 10);
    $ora_inizio = substr($data_e_oraInizio, 11);
    $ora_fine = filter_input(INPUT_POST, 'endTime');
    $durata = filter_input(INPUT_POST, 'duration');
    $luogo = filter_input(INPUT_POST, 'location');
    $descrizione = filter_input(INPUT_POST, 'description');
    $note = filter_input(INPUT_POST, 'internalNotes');
    $stato = filter_input(INPUT_POST, 'state');
    if (isset($id_activity)) { // edit or delete activity
        if (isset($commessa)) { // edit
            $dataLayer->editActivity($id_activity, $commessa, $data, $ora_inizio, $ora_fine, $durata, $luogo, $descrizione, $note, $stato);
        } else { // delete
            $dataLayer->deleteActivity($id_activity);
        }
    } else { // add activity
        $dataLayer->addActivity($commessa, $data, $ora_inizio, $ora_fine, $durata, $luogo, $descrizione, $note, $stato);
    }

    header("location: technician.php");
}

$id_current_activity = filter_input(INPUT_GET, 'id_activity');
if (isset($id_current_activity)) {
    $current_activity = $dataLayer->getActivityBy_id($id_current_activity);

    if (isset($current_activity)) {
        $current_costumer = $dataLayer->getCostumerByOrder_id($current_activity->get_id_commessa());
        $current_order = $dataLayer->getOrderByActivity_id($current_activity->get_id());
        $current_state = $dataLayer->getStateByActivity_id($current_activity->get_id());
    }

    $method = filter_input(INPUT_GET, 'method');
}

$costumers = $dataLayer->listCostumer();
$orders = $dataLayer->listOrder();
$states = $dataLayer->listActivityState();
?>

<!DOCTYPE html>

<html>
<?php
if (isset($current_activity)) {
    echo html_head('Gestione : ' . strtolower($methods[$method]));
} else {
    echo html_head('Gestione : aggiungi');
}
?>

<body>
<?php echo html_nav($username); ?>


<div class="container mt-lg-4"> <!-- Corpo della pagina -->
    <div class="row">
        <div class="col-md-1 col-lg-2"></div>

        <div class="col-md-10 col-lg-8">
            <form name="activity" action="#" method="post"> <!-- form -->
                <?php
                if (isset($current_activity)) {
                    echo '<h3>' . $methods[$method] . ' attività</h3>';
                    echo '<input name="id_activity" value="' . $current_activity->get_id() . '" hidden>';
                } else {
                    echo '<h3>Aggiungi attività</h3>';
                }
                ?>

                <div class="row mb-md-2"> <!-- Primi due campi -->
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label class="form-label" for="costumer">Cliente</label>

                        <?php
                        if (isset($current_activity)) {
                            echo '<select class="form-select" id="costumer" name="costumer"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                            foreach ($costumers as $costumer) {
                                echo '<option value="' . $costumer->get_id() . '" ' .
                                    ($costumer->get_id() == $current_costumer->get_id() ? 'selected' : '')
                                    . '>' . $costumer->getNome() . '</option>';
                            }
                        } else {
                            echo '<select class="form-select" id="costumer" name="costumer">';
                            echo '<option value="" disabled selected hidden>seleziona cliente</option>';
                            foreach ($costumers as $costumer) {
                                echo '<option value="' . $costumer->get_id() . '">' . $costumer->getNome() . '</option>';
                            }
                        }
                        ?>
                        </select>

                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">

                        <label class="form-label" for="order">Progetto/Commessa</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<select class="form-select" id="order" name="order"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                            foreach ($orders as $order) {
                                echo '<option value="' . $order->get_id() . '" '
                                    . ($order->get_id() == $current_order->get_id() ? 'selected' : '')
                                    . '>' . $order->getDescrizione() . '</option>';
                            }
                        } else {
                            echo '<select class="form-select" id="order" name="order">';
                            echo '<option value="" disabled selected hidden>seleziona commessa</option>';
                            foreach ($orders as $order) {
                                echo '<option value="' . $order->get_id() . '">' . $order->getDescrizione() . '</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-md-2"> <!-- Secondi tre campi -->
                    <div class="col-md-6 mb-2">
                        <label class="form-label" for="date_and_startTime">Data e ora di inizio</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<input class="form-control" type="datetime-local" '
                                . 'id="date_and_startTime" name="date_and_startTime" value="'
                                . $current_activity->getData()
                                . 'T'
                                . $current_activity->getOra_inizio()
                                . '"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                        } else {
                            echo '<input class="form-control" type="datetime-local" id="date_and_startTime" name="date_and_startTime">';
                        }
                        ?>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label" for="endTime">Ora di fine</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<input class="form-control" type="time" id="endTime" name="endTime" value="'
                                . $current_activity->getOra_fine()
                                . '"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                        } else {
                            echo '<input class="form-control" type="time" id="endTime" name="endTime">';
                        }
                        ?>

                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label class="form-label" for="duration">Durata</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<input class="form-control" type="time" id="duration" name="duration" value="'
                                . $current_activity->getDurata()
                                . '"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                        } else {
                            echo '<input class="form-control" type="time" id="duration" name="duration">';
                        }
                        ?>

                    </div>
                </div>

                <div class="row mb-md-2"> <!-- Terzo gruppo -->
                    <div class="col-md-12 mb-2 mb-md-0">
                        <label class="form-label" for="location">Luogo</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<input class="form-control" type="text" id="location" name="location" value="'
                                . $current_activity->getLuogo()
                                . '"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                        } else {
                            echo '<input class="form-control" type="text" id="location" name="location">';
                        }
                        ?>

                    </div>
                </div>

                <div class="row mb-md-2"> <!-- Quarto -->
                    <div class="col-md-12 mb-2 mb-md-0">
                        <label class="form-label" for="description">Descrizione</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<textarea class="form-control" id="description" name="description"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>'
                                . $current_activity->getDescrizione()
                                . '</textarea>';
                        } else {
                            echo '<textarea class="form-control" id="description" name="description"></textarea>';
                        }
                        ?>

                    </div>
                </div>

                <div class="row mb-md-2"> <!-- Quinto -->
                    <div class="col-md-12 mb-2 mb-md-0">
                        <label class="form-label" for="internalNotes">Note interne</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<input class="form-control" type="text" id="internalNotes" name="internalNotes" value="'
                                . $current_activity->getNote_interne()
                                . '"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                        } else {
                            echo '<input class="form-control" type="text" id="internalNotes" name="internalNotes">';
                        }
                        ?>

                    </div>
                </div>

                <div class="row mb-md-2"> <!-- Ultimo -->
                    <div class="col-md-12 mb-2 mb-md-0">
                        <label class="form-label" for="state">Stato</label>
                        <?php
                        if (isset($current_activity)) {
                            echo '<select class="form-select" id="state" name="state"'
                                . ($method == $EDIT_INDEX ? '' : ' disabled')
                                . '>';
                            foreach ($states as $state) {
                                echo '<option value="' . $state->get_id() . '" ' .
                                    ($state->get_id() == $current_state->get_id() ? 'selected' : '') .
                                    '>' . $state->getDescrizione() . '</option>';
                            }
                        } else {
                            echo '<select class="form-select" id="state" name="state">';
                            echo '<option value="" disabled selected hidden>seleziona stato</option>';
                            foreach ($states as $state) {
                                echo '<option value="' . $state->get_id() . '">' . $state->getDescrizione() . '</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <div class="row pt-3 pt-lg-4 mb-3"> <!-- Bottoni ai piedi della form -->
                    <div class="col-lg-6"></div>
                    <?php
                    if (isset($current_activity)) {
                        if ($method == $EDIT_INDEX) {
                            echo '  <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                                    <a class="btn btn-secondary w-100" href="technician.php">Annulla</a>
                                                </div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <button class="btn btn-primary w-100" type="submit">' . $methods[$method] . '</button>';
                        } else if ($method == $VIEW_INDEX) {
                            echo '  <div class="col-sm-6 col-lg-3 mb-2 mb-md-0"></div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <a class="btn btn-secondary w-100" href="technician.php">Torna indietro</a>';
                        } else if ($method == $DELETE_INDEX) {
                            echo '  <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                                    <a class="btn btn-secondary w-100" href="technician.php">Annulla</a>
                                                </div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <button class="btn btn-danger w-100" type="submit">' . $methods[$method] . '</button>';
                        } else {
                            echo 'There is something wrong';
                        }
                    } else {
                        echo '  <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                                <a class="btn btn-danger w-100" href="technician.php">Annulla</a>
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <button class="btn btn-primary w-100" type="submit">Aggiungi</button>';
                    }
                    ?>
                </div>
        </div>
        </form>
    </div>

    <div class="col-md-1 col-lg-2"></div>
</div>
</div>
</body>
</html>
