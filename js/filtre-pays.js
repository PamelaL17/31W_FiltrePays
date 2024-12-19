document.addEventListener("DOMContentLoaded", function () {
  // Récupère tous les boutons de pays
  var boutonsPays = document.querySelectorAll(".filtrepays__bouton");

  // Ajoute un écouteur d'événements pour chaque bouton
  boutonsPays.forEach(function (button) {
    button.addEventListener("click", function () {
      var paysNom = this.getAttribute("data-pays"); // Récupère le nom du pays

      // Modifie l'URL sans recharger la page
      var url = new URL(window.location.href);
      url.searchParams.set("pays", paysNom); // Ajoute ou met à jour le paramètre 'pays' dans l'URL

      // Recharger la page avec le pays sélectionné dans l'URL
      window.location.href = url.toString();
    });
  });
});
