

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>test</h1>
    
<?php
    if(isset($_POST['prix']) && !empty($_POST['prix'])) {
        //créer une intention d'achat/paiement - régulation européenne : avant même de pouvoir procéder à un paiement en ligne, il faut que le serveur / système de gestion du paiement soit prévenu d'une intention et il renvoie un premier code qui sera la validation de l'accusé de réception  et qq infos complémentaires.

        //charger la bibliothèque de stripe
        require_once('../vendor/autoload.php');
        $prix = (float)$_POST['prix'];

        //envoyer intention d'achat à stripe
        //instancier stripe, qu'on lui donne l'info de notre identité (nous développeur) en lui passant la clef secrète

        \Stripe\Stripe::setApiKey('sk_test_51OOfxmFInhPlxmzG0BuQ347vV5XipJaK5kaF3QWlN7GFwdJE78EtYLCQve2pT7BeqE0VoxX9qjvn6hi87wYry67B00g9GKqSln');

        $intent =  \Stripe\PaymentIntent::create([
            // tableau contient deux infos 
            // montant en centimes
            // et la monnaie

            'amount' => $prix*100,
            'currency' => 'eur'
        ]);
        }else {
            // ! à configurer pour éviter d'accéder à la page paiement si pas de transaction de faites
                header('location: /home/test_stripe');
    }

        // echo '<pre>';
        // var_dump($intent);
        // echo '</pre>';

        // die();
    
?>

    <form action="" method="post">
        <!--contriendra les msg d'erreurs de paiements-->
        <div id="errors"></div>
        <input type="text" id="cardholder-name" placeholder="titulaire de la carte">

        <!-- info de la carte elle-même-->
        <div id="card-elements"></div>

        <!-- contiendra les erreurs relatives à la carte-->
        <div id="card-errors" role="alter"></div>

        <!-- data-secret = numéro secret qui est envoyé par stripe qui est l'accumulation de l'id+ clef secrète -->
        <button id="card-button" type="button" data-secret="<?= $intent['client_secret']?>">Procéder au paiement</button>
    </form>


    <script>
        // sert à nous assurer que notre document est totalement chargé
        window.onload = () => {
            //variables
            // la clef publique est mise à l'intérieur de notre instance Stripe
            let stripe = Stripe('pk_test_51OOfxmFInhPlxmzGb5udco2DCaiaaH3PlKcFjVz4GK1xy7qYYFUkzDpkfoyBfhF8e70X3n6x5JpZL616aIrL5VvN0095RLpWkN')

            // déclarer les élèments, numéro de carte, date d'expériation, les 3 chiffres, code postale de la personne
            let elements = stripe.elements();
            //faire une redirection si le paiment est un succès
            let redirect = "/home/test_stripe"

            // objets de la page
            let cardHolderName = document.getElementById("cardholder-name");
            let cardButton = document.getElementById("card-button")
            let clientSecret = cardButton.dataset.secret;

            //créer les élements du formulaire de carte bancaire
            let card = elements.create("card")
            card.mount("#card-elements")


            // on gère la saisie (message d'erreur)
            //écouteur d'évènement, evènement change, à partir du moment où on modifie une valeur, on va s'accrocher à l'évènement change
            card.addEventListener("change", (e) => {
                let displayError = document.getElementById('card-errors')
                if(e.error) {
                    displayError.textContent = e.error.message
                } else {
                    displayError.textContent = "";
                }
            })

            // gérer le paiement
            // on gère ça en JS, ça passe par l'intermédiare d'une promesse donc en asynchrone,élèment en JS qui attend une réponse  et quand on a la réponse on fait une gestion de cette réponse 
            // 1. intercepter le clic sur le bouton
            cardButton.addEventListener("click",() => {
                // lancer la promesse : je vais gérer le paiement
                stripe.handleCardPayment(
                    // pour procéder au paiements il a besoin d'infos : le numéro secret de la transaction, les infos de la carte, nom du client
                    clientSecret, card,  {
                        payment_method_data: {
                            billing_details : { name: cardHolderName.value}
                        }
                    }
                    //comme si une promesse on a then, et on obtient un résultat et on procède
                ).then((result) => {
                    // vérifier si erreur ou pas
                    if(result.error) {
                        document.getElementById("errors").innerText = result.error.message 
                     }else{
                        //redirection
                        document.location.href = redirect
                    }
                    
                })
            })

        }
    </script>

    <script src="https://js.stripe.com/v3/"></script>

<!-- <script src="../public/js/stripe.js"></script> -->

</body>
</html>

