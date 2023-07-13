<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      user: [],
      projects: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_projects = await fetch("http://127.0.0.1:8000/api/project");
    this.projects = await fetched_projects.json();
    console.table(this.projects);

    // console.log(this.user.id);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    createProject: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("title", this.title);
      urlencoded.append("user.id", this.user.id)
    
      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
       
      };
let url= "http://127.0.0.1:8000/api/project/"
      fetch(
        url, requestOptions
      )
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
        setTimeout(() => {
        this.$router.push({ name: "todo" });
      }, "2000");
    },
  },
};
</script>
<template>
  <h1>Nouveau projet :</h1>
  <div class="flex">
    <div class="flex flex-col h-screen p-3 bg-white shadow w-60">
      <div class="space-y-3">
        <div class="flex items-center">
          <h2 class="text-xl font-bold">Projets</h2>
        </div>
        <div class="flex-1">
          <ul class="pt-2 pb-4 space-y-1 text-sm">
            <li class="rounded-sm">
              <a href="#" class="flex items-center p-2 space-x-3 rounded-md">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                />

                <router-link :to="`createproject`">
                  <span>créer un projet</span>
                </router-link>
              </a>
            </li>
          </ul>
          <ul class="pt-2 pb-4 space-y-1 text-sm" v-for="project in projects">
            <li class="rounded-sm">
              <a href="#" class="flex items-center p-2 space-x-3 rounded-md">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                />

                <span>{{ project.title }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container mx-auto mt-12">
        
      <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
          <div class="text-sm font-medium text-gray-500 truncate"></div>
          <label for="title">Nom du projet :</label>
          <input v-model="title" class="input" type="text" />
          <br />
          <button
            @click="createProject()"
            class="button rounded-lg button-disabled connexions"
          >
            Créer le projet
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.createproject {
  margin-top: 8% !important;
  margin-left: 1%;
}
</style>
