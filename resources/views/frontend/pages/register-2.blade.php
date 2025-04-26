<form method="POST" action="{{ route('register.postStep2') }}">
    @csrf

    <!-- Champs email, téléphone... -->

    <label>
        <input type="checkbox" name="agree_info" required>
        J’atteste sur l'honneur, l'exactitude des renseignements fournis et m'engage à prévenir l’Association de tout changement éventuel
    </label><br>

    <label>
        <input type="checkbox" name="agree_rules" required>
        Je reconnais avoir pris connaissance du règlement intérieur et m’engage à m'y conformer
    </label><br>

    <label>
        <input type="checkbox" name="agree_data" required>
        Je reconnais avoir pris connaissance que les informations recueillies font l'objet d'un traitement informatique
    </label><br>

    <label>
        Fait à <input type="text" name="signed_at" required>
    </label><br>

    <button type="submit">Suivant</button>
</form>
