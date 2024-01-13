<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import { defineComponent } from "vue";
import { Carousel, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

const route = useRoute();
export default defineComponent({
  data() {
    return {
      currentSlide: 0,
      mode: false,
      route: route,
      // mode:"OpenMenu",
      isMenuOpen: false,
      isContentOpen: false,
      DetailImage: false,
      illustrations: [],
      articles: [],
      illustrationsReduits: [],
      CountIllustrations: [],
      article: "",
      selectedArticle: null,
      ContentArticle: null,
      DetailArticle: null,
      illustrationsReduit: [],
      articles: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");

    let fetched_articles = await fetch(
      "http://127.0.0.1:8000/api/articles/user/" + this.user.id
    );
    this.articles = await fetched_articles.json();
    console.table(this.articles);

    let fetched_illustrations = await fetch(
      "http://127.0.0.1:8000/api/illustrations/"
    );
    this.illustrations = await fetched_illustrations.json();
    console.table(this.illustrations);

    for (let i = 0; i < this.articles.length; i++) {
      const article = this.articles[i];

      let countIllustrationsResponse = await fetch(
        "http://localhost:8000/api/illustrations/article/" + article.id
      );

      article.countIllustrations = await countIllustrationsResponse.json();
      console.table(article.countIllustrations);

      const fetched_illustrationsReduits = await fetch(
    "http://localhost:8000/api/illustrations/articlereduit/" + article.id
  );
  article.illustrationsReduit = await fetched_illustrationsReduits.json();
  console.log(article.illustrationsReduit);

  article.illustrationsReduit = article.illustrationsReduit.slice(0, 3);
  console.table(article.illustrationsReduit);
    }
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    getColor(article) {
      return article.color;
    },
    getSize(article) {
      return article.size;
    },
    getWeight(article) {
      return article.weight;
    },
    getItalique(article) {
      return article.italique;
    },
    Open(article) {
      console.log(article);
      this.selectedArticle = article; // Ajoutez cette ligne pour stocker l'article sélectionné
      this.isMenuOpen = true;
    },
    delete_article(article) {
      console.log(article);

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/articles/" + article;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          var requestOptions = {
            method: "DELETE",
            redirect: "follow",
          };

          fetch(
            "http://localhost:8000/api/illustrations/article/" + article,
            requestOptions
          )
            .then((response) => response.text())
            .then((result) => {
              console.log(result);
            })
            .catch((error) => console.log("error", error));
        })
        .catch((error) => console.log("error", error));

      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    chargerArticles() {
      fetch("http://localhost:8000/api/articles")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.illustrations = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    sortir() {
      this.isMenuOpen = false;
      this.isContentOpen = false;
      this.DetailImage = false;
    },
    voirPlus(article) {
      console.log(article);
      this.ContentArticle = article;
      this.isContentOpen = true;
    },
    afficherLesImages(article) {
      console.log(article.content);
      this.DetailArticle = article;
      this.DetailImage = true;
    },
  },
});
</script>
<template>
  <div class="navigation">
    <router-link :to="`/accueilintra`">
      <button class="button rounded-lg Retour">Retour</button>
    </router-link>
    <router-link :to="`/miseenligne`">
      <button class="button rounded-lg Retour">Mise en ligne</button>
    </router-link>
  </div>

  <h1>Liste des articles dont je suis l'auteur</h1>
  <!-- menu article -->
  <div class="MenuArticle" v-if="isMenuOpen == true">
    <img
      src="https://cdn-icons-png.flaticon.com/128/45/45320.png"
      class="croix"
      style="width: 15px; position: absolute; left: 90%; z-index: 1"
      @click="sortir()"
      alt=""
    />

    <ul>
      <router-link
        :to="`/updatearticle/${selectedArticle}`"
        style="border: none"
      >
        <li class="OptionArticle">Modifier</li>
      </router-link>
      <li class="OptionArticle" @click="delete_article(selectedArticle)">
        Supprimer
      </li>

      <router-link
        :to="`/illustrations/${selectedArticle}`"
        style="border: none"
      >
        <li class="OptionArticle">Ajouter des photos</li>
      </router-link>
    </ul>
  </div>
  <!-- Detail content -->
  <div class="DetailContent" v-if="isContentOpen == true">
    <img
      src="https://cdn-icons-png.flaticon.com/128/45/45320.png"
      class="croixContent"
      style="width: 15px; position: absolute; left: 95%; z-index: 1; top: 19px"
      @click="sortir()"
      alt=""
    />

    {{ ContentArticle }}
  </div>
  <!-- photos en plus -->
  <div class="ToutesLesPhotosEnPlus" v-if="DetailImage == true">
    <div class="photosenplus">
      <img
        src="https://cdn-icons-png.flaticon.com/128/45/45320.png"
        class="croixIllustration"
        style="width: 15px; left: 95%; z-index: 1"
        @click="sortir()"
        alt=""
      />
      <div v-for="illustration in illustrations">
        <div v-if="illustration.article_id == DetailArticle.id">
          <img v-bind:src="illustration.url" class="PhotosDetail" />
        </div>
      </div>
    </div>
    <div class="detailArticlesPhotos">
      <div class="detailArticlesPhotosTitle">
        {{ DetailArticle.title }}
      </div>
      <div
        class="detailArticleContent"
        :style="{
          color: getColor(DetailArticle),
          'font-size': getSize(DetailArticle),
          'font-weight': getWeight(DetailArticle),
          'font-style': getItalique(DetailArticle),
        }"
      >
        {{ DetailArticle.content }}
      </div>
    </div>
  </div>
  <div
    class="listedemesarticles"
    :class="{ blurred: isMenuOpen || isContentOpen || DetailImage }"
  >
    <div v-for="article in articles">
      <img
        src="https://cdn-icons-png.flaticon.com/128/11164/11164452.png"
        class="open"
        style="
          width: 25px;
          position: absolute;
          top: 8px;
          right: 15px;
          z-index: 1;
        "
        @click="Open(article.id)"
        alt=""
      />
      <div class="ContenuArticle">
        <div class="mesarticle">
          <div class="articleCard">
            <div class="articleCardTitle">
              {{ article.title }}
            </div>
            <div
              class="articleCardContent"
              :style="{
                color: getColor(article),
                'font-size': getSize(article),
                'font-weight': getWeight(article),
                'font-style': getItalique(article),
              }"
            >
              <div v-if="mode == 'nodisplay'">
                {{ article.content }}
              </div>
              <div v-else>
                {{ article.content.substring(0, 100) }}
              </div>
            </div>
          </div>
          <span
            v-if="article.content.length > 100"
            style="
              margin-left: 10px;
              font-style: italic;
              font-size: 15px;
              font-weight: lighter;
            "
            @click="voirPlus(article.content)"
            >Voir plus ...
          </span>
        </div>

        <!-- {{ article }} -->

        <div class="articleIllustration">
          <div v-for="illustration in article.illustrationsReduit">
            <!-- <div v-if="illustration.article_id == article.id"> -->
              <img v-bind:src="illustration.url" class="illustrationarticle" />
            </div>
          <!-- </div> -->

          <div
            class="resteimage"
            v-if="
              article.countIllustrations && article.countIllustrations.count > 3
            "
            style="background-color: rgba(17, 10, 10, 0.219)"
            @click="afficherLesImages(article)"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/8105/8105882.png"
              alt=""
            />
            <span class="restedephotos">
              {{ article.countIllustrations.count - 3 }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.open:hover {
  cursor: pointer;
  z-index: 1 !important;
}

.OptionArticle:hover {
  cursor: pointer;
  font-size: 25px !important;
  font-weight: bold;
}
.mesarticle {
  height: 176px;
}
.ContenuArticle {
  border: 1px solid black;
  height: 300px;
  margin-bottom: 5px;
  margin-left: 10px;
  width: 400px;
}
.listedemesarticles {
  display: flex;
  flex-wrap: wrap;
  margin-left:100px;
  margin-right: 10px;
  transition: filter 0.3s ease-in-out;
}
.blurred {
  filter: blur(6px);
}
.articleIllustration {
  display: flex;
  margin-left: 1px;
  margin-right: 1px;
}
.illustrationarticle {
  width: 140px;
  height: 120px;
}
.articleCardTitle {
  text-align: center;
  font-size: 25px;
  font-weight: bold;
}
.articleCardContent {
  text-align: justify;
  overflow: hidden;
  padding: 1px;
  width: 96%;
  margin-left: 8px;
  /* height: 157px; */
}
span:hover {
  cursor: pointer;
  font-weight: bolder !important;
  font-size: 16px !important;
}
.croix:hover {
  cursor: pointer;
  width: 20px !important;
  left: 190px !important;
}
.croixContent:hover {
  cursor: pointer;
  width: 20px !important;
  left: 94% !important;
}
.restedephotos {
  position: absolute;
  left: 65%;
  top: 55px;
  font-size: 35px;
}
.resteimage:hover {
  cursor: pointer;
}

.MenuArticle {
  z-index: 2 !important;
  border: 1px solid;
  padding: 5px;
  font-size: 25px;
  background-color: whitesmoke;
  box-shadow:  4px 1px 20px 0px black;
  position: absolute;
  top: 50%;
  left: 44%;
  font-weight: bolder !important;
}
.DetailContent {
  border: 1px solid;
  margin-left: 5%;
  margin-right: 5%;
  background-color: whitesmoke;
  box-shadow: inset 4px 1px 20px 0px black;
  padding: 3%;
  padding-top: 4%;
  text-align: justify;
}

.photosenplus {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  width: 80%;

  padding: 15px;
}
.PhotosDetail {
  width: 350px;
}
.ToutesLesPhotosEnPlus {
  position: absolute;
  /* background-color: whitesmoke !important; */
  z-index: 1;
  display: flex;
}
.detailArticlesPhotos {
  width: 20%;
  padding: 15px;
}
.detailArticleContent {
  text-align: justify;
}
.detailArticlesPhotosTitle {
  text-align: center;
  font-weight: bold;
  font-size: 20px;
}
.croixIllustration {
  width: 29px;
  z-index: 1;
  left: 120% !important;
  top: -2%;
  height: 2Opx !important;
  height: 30px;
  position: absolute;
}
.croixIllustration:hover {
  cursor: pointer;
  width: 32px !important;
  left: 119% !important;
}
</style>
