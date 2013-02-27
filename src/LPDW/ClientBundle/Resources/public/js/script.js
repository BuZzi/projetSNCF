$(document).ready(function()
{
    // Lors du clic sur le bouton "trouve moi"
    $('#findMe').click(function(){
        // Si le navigateur supporte la géolocalisation
        if(navigator.geolocation)
        {
            // L’API est disponible
            // Appel de la fonction qui envoie la position au controller
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }
        else
        {
            // Pas de support de l'HTML
            alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
        }
    });
});

/**
 * Fonction qui envoie les positions de l'utilisateur au controller
 * @param position
 * @return void
 */
function successCallback(position)
{
    // envoie au controller les données
    $.ajax({
        // url
        url: Routing.generate('lpdw_position', true),
        type: "POST",
        data:
        {
            "lat" : position.coords.latitude,
            "lng" : position.coords.longitude
        },
        success: function(data)
        {
            document.getElementById("infosposition").innerHTML = data;
        }
    });
}

/**
 * Fonction qui envoie une notification d'erreur au controller
 * @param void
 * @return void
 */
function errorCallback(error)
{
    var errorTxt;
    switch(error.code)
    {
        case error.PERMISSION_DENIED:
            errorTxt = "Vous n'avez pas autorisé l'accès à votre position ";
            break;
        case error.POSITION_UNAVAILABLE:
            errorTxt = "Votre emplacement n'a pas pu être déterminé";
            break;
        case error.TIMEOUT:
            errorTxt = "Le service n'a pas répondu à temps";
            break;
    }
    // envoie au controller les données
    $.ajax({
        // url
        url: Routing.generate('lpdw_error', true),
        type: "POST",
        data:
        {
            "errorTxt" : errorTxt
        },
        success: function(data)
        {
            // affiche les données recus du controller
            document.getElementById("infosposition").innerHTML = data;
        }
    });
}