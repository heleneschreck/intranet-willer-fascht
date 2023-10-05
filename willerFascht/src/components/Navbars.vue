<!--

  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    delete_membres: function (membre) {
      console.log(membre.id);

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/users/" + membre.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
        setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
  },
};


</script> -->

<script>
import { mapState } from "vuex";
export default {
  // name: "slate-navbar",
  data() {
    return {
      showMenu: false,
      member: Number,
      user: [],
      membres: [],
    };
  },
  mounted() {
    this.$store.dispatch("loadConversations");
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    toggleNavbar: function () {
      this.showMenu = !this.showMenu;
    },
  },
};
</script>
<template>
  <nav
    class="relative flex flex-wrap items-center justify-between px-2 py-3 bg-slate-500 mb-3"
  >
   
    <div
      class="container px-4 mx-auto flex flex-wrap items-center justify-between"
    >
      <div
        class="w-full relative flex justify-between lg:w-auto px-4 lg:static lg:block lg:justify-start"
      >
        <a
          class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-black logonav"
          href=""
        >
        <img src="../assets/logo.jpg" class="h-6 mr-3 sm:h-9" alt="Logo" />
        </a>
        <button
          class="text-black cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
          type="button"
          v-on:click="toggleNavbar()"
        >
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div
        v-bind:class="{ hidden: !showMenu, flex: showMenu }"
        class="lg:flex lg:flex-grow items-center listeonglets"
      >
        <ul class="flex flex-col lg:flex-row list-none ml-auto ongletaccueil">
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
</template>
<style scoped>
nav {
  background-color: white !important;
}
.nav-item {
  color: black !important;
}
.logonav{
    margin-left: -50px ;
  }
  .listeonglets{
    padding-left: -56px !important;
  }
  
.ongletaccueil {
    margin-right: 350px !important;
}
li {
  margin-left: 25px !important; ;
}
</style>
