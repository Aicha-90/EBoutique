/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
const $ = require('jquery');
require('bootstrap');

// Ajouter ou diminuer la quantité d'un produit (AJAX )

$(".qte").click( function(){

    console.log("test");  
    var id=$("span").attr("id");
       
    $.ajax({
        url: "panier/ajouter/".id,
        success: function(r){
            
            console.log("envoyé");   
              
        }
    });//fin ajax
});

// Affichage du bloc mode de livraison

$(".modeLivraison").hide();
$(".paie").hide();

$(".paiement").click( function(){
  
    $(".paie").show();
    $(this).hide();
    
});

$(".commander").click( function(){
  
    $(".modeLivraison").show();
    $(this).hide();
;
});


//Mode de livraison: choix de l'adresse

$(".adresseLiv").show();
$(".adresseRetrait").hide();

$("input").click(function(){
    var modeLiv=$('input:checked').attr("id");

    if( modeLiv === "livDom"){
        $(".adresseRetrait").hide();
        $(".adresseLiv").show();
    }

    if( modeLiv === "relais"){
        $(".adresseLiv").hide();
        $(".adresseRetrait").show();
    }

})

//Validation du panier
$(".validerPanier").hide();

$(".paiement").click( function(){
  
    $(".validerPanier").show();
    $(this).hide();
;
});

