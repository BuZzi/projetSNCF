$(document).ready(function()
{
    // Si le navigateur supporte la géolocalisation
    if(navigator.geolocation)
    {
        // L’API est disponible
        // Appel de la fonction qui envoie la position au controller
        navigator.geolocation.getCurrentPosition(maPosition);
    }
    else
    {
        // Pas de support, proposer une alternative ?
    }
});

/**
 * Fonction qui envoie les positions de l'utilisateur au controller
 * @param position
 * @return void
 */
function maPosition(position)
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