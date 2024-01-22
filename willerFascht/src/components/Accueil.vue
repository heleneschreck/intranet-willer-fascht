<script>
import { mapState } from "vuex";
import { defineComponent } from "vue";
import { Carousel, Pagination, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";
import "vue3-carousel/dist/carousel.css";
export default defineComponent({
  data() {
    return {
      mode: false,
      showMenu: false,
      candidaturevalider: false,
      user: [],
      partenaires: [],
      souvenirs: [],
      mode: "candidaturevalider",
      currentPage: 1,
      articlesPerPage: 5,
      articles: [],
      illustrations: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
    paginatedArticles() {
      const startIndex = (this.currentPage - 1) * this.articlesPerPage;
      const endIndex = startIndex + this.articlesPerPage;
      return this.articles.slice(startIndex, endIndex);
    },
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    if (localStorage.getItem("user") != null) {
      this.mode = true;
    }
    let fetched_partenaires = await fetch(
      "http://localhost:8000/api/partenaires"
    );
    this.partenaires = await fetched_partenaires.json();
    console.table(this.partenaires);

    let fetched_souvenirs = await fetch("http://localhost:8000/api/souvenirs");
    this.souvenirs = await fetched_souvenirs.json();
    console.table(this.souvenirs);

    let fetched_articles = await fetch(
      "http://127.0.0.1:8000/api/articles/" 
    );
    this.articles = await fetched_articles.json();
    console.table(this.articles);

    let fetched_illustrations = await fetch(
      "http://127.0.0.1:8000/api/illustrations/"
    );
    this.illustrations = await fetched_illustrations.json();
    console.table(this.illustrations);
  },
  methods: {
    toggleNavbar: function () {
      this.showMenu = !this.showMenu;
    },
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    add_inscription: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("nom", this.name);
      urlencoded.append("prenom", this.prenom);
      urlencoded.append("telephone", this.telephone);
      urlencoded.append("email", this.email);
      urlencoded.append("vu", "0");
      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };

      let url = "http://localhost:8000/api/candidats";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      this.candidaturevalider = true;

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
    logout: function () {
      this.$store.commit("logout");
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
   
    changePage(step) {
      this.currentPage += step;
    },
    slideTo(val) {
      this.currentSlide = val;
    },
  },

  components: {
    Carousel,
    Slide,
    Carousel,
    Pagination,
    Navigation,
  },
});
</script>
<template>
  <nav
    class="relative flex flex-wrap items-center justify-between px-2 py-3 bg-slate-500 mb-3"
  >
    <div
      class="container px-4 mx-auto flex flex-wrap items-center justify-between"
      style="margin-right: 15px"
    >
      <div
        class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start"
      >
        <router-link :to="`/`">
          <a
            class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-black logonav"
            href=""
          >
            <img src="../assets/logo.jpg" class="h-6 sm:h-9" alt="Logo" />
          </a>
        </router-link>
        <button
          class="text-black cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
          type="button"
          v-on:click="toggleNavbar()"
        >
          <svg
            class="w-6 h-6"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>
      <div
        v-bind:class="{ hidden: !showMenu, flex: showMenu }"
        class="lg:flex lg:flex-grow items-center listeonglets"
      >
        <ul
          class="flex flex-col mt-4 lg:flex-row list-none ml-auto ongletaccueil" style="margin-left: 112px !important;"
        >
        
          <li>

            Notre actualité
          </li>
        
          <li>

            Album souvenir
          </li>
          <li>

            Nos partenaires
          </li>
          <li>

            Vous souhaitez nous rejoindre ?
          </li>
          
          <div v-if="mode == true">
              <li>
          <router-link :to="`/accueilintra`">
       
          Accueil
        </router-link>
        
      </li> </div>
          <div v-if="mode == true">
         
      
      
     
     
          <button
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
          
            @click="logout()"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
              />
            </svg>
            <span class="group-hover:text-gray-700">Deconnexion</span>
          </button>
       
  </div>
    <div v-else>
      <router-link :to="`/connexion`">
        <button
          class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
          style="padding: 4px !important;  "
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
            />
          </svg>
         
          <span class="group-hover:text-gray-700">Connexion</span>
        </button>
      </router-link>
    </div>
    </ul>
      </div>
    </div>
  </nav>




  
  <div class="bod">
    <div class="banniere">
      <img src="../assets/logo.jpg" class="bannierelogo" alt="" />
    </div>
    <div class="actualite">
      <h2>Notre actualité :</h2>
      <div class="postsListes">
        <div v-for="article in paginatedArticles" :key="article.id">
          <div class="post">
            <div class="titlePostAccueil">
              {{ article.title }}
            </div>
            <div class="imagePostAccueil">
              <div v-for="illustration in illustrations">
                <div v-if="illustration.article_id == article.id">
                  <img
                    v-bind:src="illustration.url"
                    class="illustrationPostAccueil"
                    style="margin-top: 5%"
                  />
                </div>
              </div>
            </div>

            <div
              class="contentPostAccueil"
              :style="{
                color: getColor(article),
                'font-size': getSize(article),
                'font-weight': getWeight(article),
                'font-style': getItalique(article),
              }"
            >
              {{ article.content }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="boutonschangementsdepages" style="padding-left: 43%; background-color: hsla(0, 0%, 84%, 0.3);">
        <button @click="changePage(-1)" :disabled="currentPage === 1">
          Précédent
        </button>
        <span>{{ currentPage }}</span>
        <button @click="changePage(1)" :disabled="currentPage === totalPages">
          Suivant
        </button>
      </div>
    </div>

    <div class="albumPhoto">
      <h2>Album souvenirs :</h2>

      <Carousel   :autoplay="2000"  :wrapAround="true">
        <Slide v-for="slide in souvenirs" :key="slide">
          <div class="carousel__item albumsouvenirs">
            <div>

              <img
              class="rounded-t-lg albumsouvenirs"
              style="margin-bottom: 10px;"
              v-bind:src="slide.url"
              alt=""
              />
            </div>
              <!-- <div class="souvenirsTitle ">{{ slide.title }}</div> -->
          </div>
        </Slide>
      </Carousel>
    </div>
    <div class="partenairesaccueil mt-3">
      <h2>Nos partenaires :</h2>
      <br />

      <Carousel
        :itemsToShow="6"
        :autoplay="1500"
        :wrapAround="true"
        :transition="500"
      >
        <Slide v-for="slide in partenaires" :key="slide">
          <div class="carousel__item">
            <img
              class="rounded-t-lg logoPartenaire"
              v-bind:src="slide.url"
              alt=""
            />
            {{ slide.title }}
          </div>
        </Slide>

        ...
      </Carousel>
    </div>
    <div class="nousRejoindre mt-20">
      <hr />
      <br />

      <div
        class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
        role="alert"
        v-if="candidaturevalider == true"
      >
        <div class="flex">
          <div class="py-1">
            <svg
              class="fill-current h-6 w-6 text-teal-500 mr-4"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
            >
              <path
                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"
              />
            </svg>
          </div>
          <div  >
            <p class="font-bold">Nous avons bien reçu votre candidature</p>
            <p class="text-sm">Nous vous recontacterons dès que possible.</p>
          </div>
        </div>
      </div>
      <div v-if="candidaturevalider== false">
        <h2>Vous souhaitez nous rejoindre ?</h2>
        <div class="inputCandidature">
        <input
          v-model="name"
          class="inputCandidaturenom"
          type="text"
          placeholder="Nom"
        />

        <input
          v-model="prenom"
          class="inputCandidatureprenom"
          type="text"
          placeholder="Prénom"
        />
        <br />

        <input
          v-model="telephone"
          class="inputCandidaturetelephone"
          type="tel"
          placeholder="Telephone"
        />

        <input
          v-model="email"
          class="inputCandidatureemail"
          type="email"
          placeholder="Email"
        />
        <button @click="add_inscription()" class="addpartenaire" style="margin-left: 45%;">
          Valider
        </button>
      </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.bod {
  margin-top: 7%;
}
nav {
  background-color: white !important;
  box-shadow: inset 4px 1px 20px 0px black;
}


.nav-item {
  color: black !important;
}
.logonav {
  margin-left: -70px;
}
.listeonglets {
  /* padding-left: -20px !important; */

  font-size: 18px !important;
}

.ongletaccueil {
  margin-right: 27px !important;
}
li {
  margin-right: 24px !important;
}

.bannierelogo {
  box-shadow: 4px 1px 20px 0px black;
  border-radius: 10px;
  width: 99%;
  height: 555px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.carousel__slide {
  padding: 5px;
}

.carousel__viewport {
  perspective: 1000px;
}

.carousel__track {
  transform-style: preserve-3d;
}

.carousel__slide--sliding {
  transition: 0.5s;
}

.carousel__slide {
  opacity: 0.9;
  transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
  transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
  opacity: 1;
  transform: rotateY(-10deg) scale(0.95);
}

.carousel__slide--next {
  opacity: 1;
  transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
  opacity: 1;
  transform: rotateY(0) scale(1.1);
}
.nousRejoindre {
  background-color: hsla(0, 0%, 84%, 0.3);
}

.partenairesaccueil{
  margin-top:30px ;
}
.inputCandidature {
  margin-top: 25px;
  padding-bottom: 25px;
}
.inputCandidaturenom {
  height: 55px;
  margin-left: 30%;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidatureprenom {
  margin-left: 30%;
  margin-right: 30px;
  height: 55px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidaturetelephone {
  margin-left: 30%;
  height: 55px;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidatureemail {
  margin-left: 30%;
  height: 55px;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.titlePostAccueil {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
}
.contentPostAccueil {
  text-align: justify;
  padding: 3px;
}
/* .albumsouvenirs {
  width: 60% !important;
} */

.postsListes {
  /* width: 70%; */
  /* margin-left: 16%; */
  padding-left: 130px !important;
  padding-right: 130px !important;
  background-color: hsla(0, 0%, 84%, 0.3);
  padding: 10px;
}
.imagePostAccueil {
  display: flex;
  background-color: hsla(0, 0%, 84%, 0.3);
}
.illustrationPostAccueil {
  /* background-color: white; */
  padding: 10px;

  display: flex;
  flex-direction: row;
  justify-content: space-around;
  flex-wrap: wrap !important;
  /* margin-left: 15px !important; */
  width: 450px;
}
.post {
  background-color: white;
  padding: 5px;
  display: flex;
  margin-top: 10px;
  flex-direction: column;
  justify-content: space-around;
  border: 1px solid rgba(164, 158, 158, 0.279);
  box-shadow: 2px 1px 15px 0px black;
}
</style>
