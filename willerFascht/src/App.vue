<script>
import { mapState } from "vuex";
import { RouterLink, RouterView } from "vue-router";
import Planning from "./components/Planning.vue";
import navbars from "./components/Navbars.vue";

export default {
  name: "app",
  components: {
    Planning,
  },
  data() {
    return {
      mode: false,
      user: [],
      currentPage: "", // Gardera le nom de la page actuelle
      showMenu: false,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  watch: {
    "$route.name"(newRouteName) {
      this.currentPage = newRouteName;
    },
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
  components: {
    // planning
    navbars,
  },
};
</script>

<template>
  <nav
    class="bg-white px-2 sm:px-4 py-2.5 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600"
  >
    <div class="container flex flex-wrap items-center justify-between mx-auto">
      <img src="./assets/logo.jpg" class="h-6 mr-3 sm:h-9" alt="Logo" />

      <div class="flex md:order-2">
        <!-- <button
          type="button"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        
        >
          Deconnexion
        </button> -->
        <div class="px-6 -mx-6 pt-4 flex justify-between items-center border-t">
          <button
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            style="padding: 4px !important; margin-bottom: 7px !important"
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

        <button
          data-collapse-toggle="navbar-sticky"
          type="button"
          @click="showMenu = !showMenu"
          class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-sticky"
          aria-expanded="false"
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
        class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
        id="navbar-sticky"
      >
        <ul
          :class="showMenu ? 'flex' : 'hidden'"
          class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
        >
          <li>
            <router-link
              :to="`/accueilintra`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'accueilintra',
                }"
                href="#"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                aria-current="page"
                >Accueil</a
              >
            </router-link>
          </li>

          <li>
            <router-link
              :to="`/mesinfos`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'mesinfos',
                }"
                href="#"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Mes informations</a
              >
            </router-link>
          </li>

          <li>
            <router-link
              :to="`/planning`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'planning',
                }"
                href="#"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Planning</a
              ></router-link
            >
          </li>
          <li>
            <router-link
              :to="`/membres`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'membres',
                }"
                href="#"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Membres
              </a>
            </router-link>
          </li>
          <li v-show="user.niveau == '1'">
            <router-link
              :to="`/createmembres`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'createmembres',
                }"
                href="#"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Ajouter un membre à l'équipe</a
              >
            </router-link>
          </li>
          <li>
            <router-link
              :to="`/messagerie`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                href="#"
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'messagerie',
                }"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Messagerie</a
              >
            </router-link>
          </li>
          <li>
            <router-link
              :to="`/todo`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                href="#"
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'todo',
                }"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Projets</a
              >
            </router-link>
          </li>
          <li>
            <router-link
              :to="`/affiches`"
              exact
              @update:current-page="currentPage = $event"
            >
              <a
                href="#"
                :class="{
                  'block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white':
                    currentPage === 'affiches',
                }"
                class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                >Supports publicitaires</a
              >
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <RouterView />
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  padding-bottom: -1rem;
}
.flex {
  display: flex;
  flex-wrap: nowrap !important;
}

.bg-white {
  text-align: left;
  margin-left: 1rem;
  font-size: 1rem;
  padding: 0rem 0;
  /* margin-top: -1rem; */
  padding-right: 63px;
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;
    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
