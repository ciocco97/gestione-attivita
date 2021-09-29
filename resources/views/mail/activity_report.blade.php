<div class="card">
    <div class="card-header">
        Report attività
    </div>
    <div class="card-body">
        <h5 class="card-title">Svolta da {{ $activity->nome }}</h5>
        <p class="card-text">
            Descrizione: {{ $activity->descrizione_attivita }}<br>
            Durata: {{ $activity->durata }}
        </p>
    </div>
</div>
