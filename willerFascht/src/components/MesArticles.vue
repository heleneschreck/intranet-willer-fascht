<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();

export default {
  data() {
    return {
      mode: false,
      route: route,
      // mode:"OpenMenu",
      isMenuOpen: false,
      illustrations: [],
      articles: [],
      illustrationsReduits: [],
      CountIllustrations: [],
      article: "",
      selectedArticle: null,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
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
    this.illustrationsReduits = this.illustrations.slice(0, 4);

    for (let i = 0; i < this.articles.length; i++) {
      const article = this.articles[i];

      let countIllustrationsResponse = await fetch(
        "http://localhost:8000/api/illustrations/article/" + article.id
      );

      article.countIllustrations = await countIllustrationsResponse.json();
      console.table(article.countIllustrations);
    }
  },
  methods: {
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
            .then((result) => console.log(result))
            .catch((error) => console.log("error", error));
        })
        .catch((error) => console.log("error", error));
    },
    sortir() {
      console.log("coucou");
      this.isMenuOpen = false;
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourcompterendu">Retour</button>
  </router-link>
  <h1>Liste des articles dont je suis l'auteur</h1>

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

  <div class="listedemesarticles" :class="{ blurred: isMenuOpen }">
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
          <div v-for="illustration in illustrationsReduits">
            <div v-show="illustration.article_id == article.id">
              <!-- {{ illustration.length }} -->

              <img v-bind:src="illustration.url" class="illustrationarticle" />
            </div>
            <!-- <div v-if="illustration.lenght > 3"> -->
            <!-- </div> -->
          </div>

          <div
            class="resteimage"
            v-if="
              article.countIllustrations && article.countIllustrations.count > 3
            "
            style="background-color: rgba(17, 10, 10, 0.219)"
            @click="afficherLesImages()"
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
  margin-left: 10px;
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
  background-color: rgba(17, 10, 10, 0.219);
  position: absolute;
  top: 50%;
  left: 40%;
  font-weight: bolder !important;
}
</style>
