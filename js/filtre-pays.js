document.addEventListener("DOMContentLoaded", function () {
  const filtreBoutons = document.querySelectorAll(".filtrepays__bouton");

  function handleButtonClick(event) {
    const paysNom = event.target.dataset.pays;
    console.log("Pays sélectionné:", paysNom);

    // Requête vers l'API REST pour récupérer les articles du pays
    fetch(`/wp-json/wp/v2/posts?search=${paysNom}&per_page=30`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Articles récupérés:", data);
        afficherArticles(data);
      })
      .catch((error) =>
        console.error("Erreur lors de l'extraction des destinations:", error)
      );
  }

  function afficherArticles(articles) {
    const container = document.querySelector("#filtre-resultats");
    container.innerHTML = "";

    articles.forEach((article) => {
      const articleElement = document.createElement("article");
      articleElement.classList.add("principal__article");

      const titleElement = document.createElement("h5");
      titleElement.textContent = article.title.rendered;
      articleElement.appendChild(titleElement);

      const excerptElement = document.createElement("p");
      excerptElement.innerHTML = article.excerpt.rendered;
      articleElement.appendChild(excerptElement);

      // Ajouter l'image à la une si elle est disponible
      const imageElement = document.createElement("div");
      if (article.featured_media) {
        const imageURL = article.featured_media_url;
        imageElement.innerHTML = `<img src="${imageURL}" alt="Article Image" />`;
      } else {
        imageElement.innerHTML = `<img src="default-image.jpg" alt="No Image" />`;
      }
      articleElement.appendChild(imageElement);

      container.appendChild(articleElement);
    });
  }

  filtreBoutons.forEach((button) => {
    button.addEventListener("click", handleButtonClick);
  });
});
