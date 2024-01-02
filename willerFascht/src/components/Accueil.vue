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
      user: [],
      partenaires: [],
      souvenirs: [],
      mode: "candidaturevalider",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
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
  },
  methods: {
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

let url= "http://localhost:8000/api/candidats";
      fetch(
       url,
        requestOptions
      )
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      this.mode = "candidaturevalider";
      // setTimeout(() => {
      //   this.$router.go(this.$router.currentRoute);
      // }, "2000");
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
    class="bg-white px-2 sm:px-4 py-2.5 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600"
  >
    <div class="container flex flex-wrap items-center justify-between mx-auto">
      <a href="https://flowbite.com/" class="flex items-center">
        <img src="../assets/logo.jpg" class="h-6 sm:h-9" alt="Logo" />
      
      </a>
      <div class="flex md:order-2">
        <div v-if="mode">
          <router-link :to="`/accueilintra`">
            <button
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
              style="padding: 4px !important; margin-bottom: 7px !important"
            >
              <span class="group-hover:text-gray-700">{{ user.prenom }}</span>
            </button>
          </router-link>
        </div>
        <div v-else>
          <router-link :to="`/connexion`">
            <button
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
              style="padding: 4px !important; margin-bottom: 7px !important"
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
              <img
                src="https://cdn-icons-png.flaticon.com/512/8379/8379802.png"
                style="width: 5%"
                alt=""
              />
              <span class="group-hover:text-gray-700">Connexion</span>
            </button>
          </router-link>
        </div>
        <button
          data-collapse-toggle="navbar-sticky"
          type="button"
          class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-sticky"
          aria-expanded="false"
        >
          <span class="sr-only">Open main menu</span>
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
        class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
        id="navbar-sticky"
      >
        <ul
          class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
        >
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white"
              aria-current="page"
              >Notre actualité</a
            >
          </li>
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >Album souvenir</a
            >
          </li>
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >Nos partenaires</a
            >
          </li>
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >Vous souhaitez nous rejoindre ?</a
            >
          </li>
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
    </div>
    <div class="albumPhoto">
      <h2>Album souvenirs :</h2>

      <Carousel :autoplay="1500" :wrapAround="true">
        <Slide v-for="slide in souvenirs" :key="slide">
          <div class="carousel__item albumsouvenirs">
            <img
              class="rounded-t-lg albumsouvenirs"
              v-bind:src="slide.url"
              alt=""
            />
            <div class="souvenirsTitle pt-3">{{ slide.title }}</div>
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
            />{{ slide.title }}
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
        v-if="mode == 'candidaturevalider'"
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
          <div>
            <p class="font-bold">Nous avons bien reçu votre candidature</p>
            <p class="text-sm">Nous vous recontacterons dès que possible.</p>
          </div>
        </div>
      </div>
      <div v-if="mode == 'false'"></div>
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
        <button @click="add_inscription()" class="addpartenaire">
          Valider
        </button>
      </div>
    </div>
  </div>
</template>
<style scoped>
.bod {
  margin-top: 7%;
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

.inputCandidature {
  margin-top: 25px;
  padding-bottom: 25px;
}
.inputCandidaturenom {
  height: 55px;
  margin-left: 15%;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidatureprenom {
  margin-right: 30px;
  height: 55px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidaturetelephone {
  margin-left: 15%;
  height: 55px;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
.inputCandidatureemail {
  height: 55px;
  margin-right: 30px;
  margin-bottom: 15px;
  width: 700px;
  border-radius: 15px;
}
/* .albumsouvenirs {
  width: 60% !important;
} */
</style>
