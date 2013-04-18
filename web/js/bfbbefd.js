$(document).ready(function()
{
    // Lors du clic sur le bouton "Trouvez les gares à proximité de ma position"
    $('#findMe').click(function(){
        // Si le navigateur supporte la géolocalisation
        if(navigator.geolocation){
            // L’API géolocalisation est disponible
            // Appel de la fonction qui envoie la position en x et y de l'utilisateur au controller
            // Fonctions de succès ou d'erreur appelés en callback
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }
        else{
            // Pas de support de la géolocalisation HTML5
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
    // affiche une gif de chargement lors de l'appel ajax
    var contentLoading = '<div class="text-center">'+
                            '<p><img class="loading-image" src='+window.loadingImg+' alt="loading" /></p>'+
                            '<h6>Recherche en cours...</h6>'+
                         '</div>';
    document.getElementById("infosposition").innerHTML = contentLoading;

    // appel ajax vers le controlleur
    $.ajax({
        // url
        url: Routing.generate('lpdw_position', true),
        type: "POST",
        // envoie les données
        data:{
            "lat" : position.coords.latitude,
            "lng" : position.coords.longitude
        },
        success: function(data){
            // change l'état du bouton 'trouve moi'
            $('#findMe').addClass('active');
            // Affiche les données recus du controlleur
            document.getElementById("infosposition").innerHTML = data;
        }
    });
}

/**
 * Fonction qui envoie une notification d'erreur au controller
 * @param error
 * @return void
 */
function errorCallback(error)
{
    var errorTxt;
    switch(error.code){
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
        data:{
            "errorTxt" : errorTxt
        },
        success: function(data){
            // affiche les données recus du controller
            document.getElementById("infosposition").innerHTML = data;
        }
    });
}