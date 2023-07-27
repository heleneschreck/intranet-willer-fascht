<script>
import { mapState } from "vuex";
import moment from "moment";

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
    this.moment = moment;

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
    this.projects.sort((a, b) => a.end.localeCompare(b.end));

    console.table(this.projects);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    deleteProject: function (project) {
      console.log(project.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/project/" + project.id;
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
</script>
<template>
  <div class="flex">
    <div class="flex flex-col h-screen p-3 bg-white shadow w-60">
      <div class="space-y-3">
        <div class="flex items-center projectrelative">
          <h2 class="text-xl font-bold indexproject">Projets</h2>
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
                  <span class="ajoutproject">
                    <img
                      src="https://cdn-icons-png.flaticon.com/512/3803/3803910.png"
                      alt="logoAjouter"
                      class="ajouter"
                    />
                    créer un projet</span
                  >
                </router-link>
              </a>
              <hr />
            </li>
          </ul>
          <ul class="pt-2 pb-4 space-y-1 text-sm" v-for="project in projects">
            <li class="rounded-sm">
              <div class="flex items-center p-2 space-x-1 rounded-md">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                />

                <span>{{ project.title }}</span>
                <button @click="deleteProject(project)" class="deleteproject">
                  <img
                    class="delete"
                    src="https://cdn-icons-png.flaticon.com/512/3182/3182136.png"
                    alt=""
                  />
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="projets">
      <div class="container mx-auto mt-5" v-for="project in projects">
        <router-link :to="`projet/${project.id}`">
          <!-- <div class="grid grid-cols-1 gap-3 mb-3 lg:grid-cols-3 "> -->
          <div class="w-full px-9 py-3 bg-white rounded-lg shadow titleproject">
            <div class="text-sm font-medium text-gray-500 truncate">
              {{ project.title }} -
              {{ moment(project.end).format("DD/MM/YYYY") }}
            </div>
            <!-- </div> -->
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>
<style>
.Todo {
  margin-top: 8% !important;
  margin-left: 1%;
}
.projets {
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
}
.projectrelative {
  position: relative;
  margin-bottom: -40px !important;
  padding-left: 30%;
}
.deleteproject {
  position: absolute;
  left: 172px;
}
.delete {
  width: 25px;
}
.ajouter {
  width: 25px;
  margin-right: 14%;
  height: 29px;
}
.ajoutproject {
  display: flex;
  width: 219px !important;
  margin-top: 14%;
}
.indexproject {
  text-align: center;
}
.titleproject {
  margin-bottom: -5px !important;
}
</style>
