<div class="container">
    <h1>Facture de la commande</h1>
    <p>Mr: {{$commande->client->prenom}} {{$commande->client->nom}}</p>
    <p>Votre Commande est termine avec success</p>
    <p>Nom du burger: {{$commande->burger->nom}}</p>
    <p>Nom du burger: {{$commande->quantite}}</p>
    <p>Nom du burger: {{$commande->burger->prix}}</p>
    <p>Merci d'avoir choisit cinaye Burger</p>
</div>
