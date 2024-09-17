<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
const route = useRoute();
export default {
  data() {
    return {
      mode: false,
      route: route,
      user: [],
      projects: [],
      statuts: [],
      taches: [],
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

    let fetched_statuts = await fetch("http://127.0.0.1:8000/api/statut");
    this.statuts = await fetched_statuts.json();
    console.table(this.statuts);

    let fetched_taches = await fetch("http://127.0.0.1:8000/api/taches");
    this.taches = await fetched_taches.json();
    console.table(this.taches);

    // console.log(this.user.id);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    updateTache: function (tache, membres) {
     

      var myHeaders = new Headers();

      var urlencoded = new URLSearchParams();
      urlencoded.append("title", tache.title);
      urlencoded.append("end", tache.end);
     

      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/" + this.$route.params.tache;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.push({ path: "/projet/"+this.$route.params.projet });
      }, "2000");
     
    },
  },
};
</script>
<template>
   
     <router-link :to="`/projet/${this.$route.params.projet}`">
<button  class="button rounded-lg Retour">Retour</button>
</router-link>
  <div class="formulaire">
    <h1>Modification</h1>
    <div v-for="tache in taches" v-show="$route.params.tache == tache.id">
      <label for="tache.title">Tache :</label>
      <input v-model="tache.title" class="input" type="text" />
      <!-- <br /> -->
      <label for="tache.end">Date buttoir :</label>
      <input v-model="tache.end" class="input" type="date" />
   

   
      <button
        @click="updateTache(tache)"
        class="button rounded-lg updateTache"
        style=""
      >
        Modifier la tache
      </button>
    </div>
  </div>
</template>
<style scoped>

.updateTache {
  background-color: green;
  color: white;
  font-weight: bold;
  padding: 5px !important;
  margin-bottom: 15px !important;
}
</style>
