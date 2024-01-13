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
      membres: [],
    };
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

    let fetched_projects = await fetch("http://127.0.0.1:8000/api/project");
    this.projects = await fetched_projects.json();
    console.table(this.projects);

    let fetched_statuts = await fetch("http://127.0.0.1:8000/api/statut");
    this.statuts = await fetched_statuts.json();
    console.table(this.statuts);

    let fetched_taches = await fetch("http://127.0.0.1:8000/api/taches");
    this.taches = await fetched_taches.json();
    console.table(this.taches);

    console.log(this.$route.params.statut);
    console.log(this.$route.params.projet);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    createTache: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("title", this.title);
      urlencoded.append("end", this.end);
      urlencoded.append("status_id", this.$route.params.statut);
      urlencoded.append("user_id", this.user_id);
      urlencoded.append("project_id", this.$route.params.projet);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.push({ path: "/projet/" + this.$route.params.projet });
      }, "2000");
    },
  },
};
</script>
<template>
  <router-link :to="`/projet/${this.$route.params.projet}`">
    <button class="button rounded-lg  Retour">Retour</button>
  </router-link>
  <div class="formulaire">
    <h1>Tache à ajouter</h1>

    <label for="title">Tache :</label>
    <input v-model="title" class="inputTitle" type="text" @keyup.enter="createTache()" />

    <label for="end">Date buttoir :</label>
    <input v-model="end" class="inputDate" type="date" @keyup.enter="createTache()"/>

    <label for="user_id">Je confie cette tache à :</label>
    <select v-model="user_id" name="user_id" id="user_id">
      <option v-for="membre in membres" :value="membre.id" @keyup.enter="createTache()">
        {{ membre.prenom }}
      </option>
    </select>
    <br />
    <button
      @click="createTache()"
      class="button rounded-lg button-disabled AjoutTache"
    >
      Ajouter la tache
    </button>
  </div>
</template>
<style>
body {
  background-color:hsla(0, 0%, 84%, 0.3) !important;
}
.ajouttache {
  /* background-color: hsla(0, 0%, 84%, 0.3); */
 height: 100% !important;
}
.formulaire {
  text-align: center;
  border: 1px solid;
  width: 27%;
  margin-left: 35%;
  border-radius: 10px;
  background-color: white;
  box-shadow: 10px 12px 15px black;
 
}

.AjoutTache{
  background-color: green;
  color: white;
  font-weight: bold;
  padding: 5px !important;
}
.Retour{
  border-radius: 20px;
  margin-left: 3%;
  border: 5px solid rgb(9, 59, 125);
  background-color: rgb(13, 64, 131);
  color: white;
  font-weight: bold;
  /* font-size: 20px; */
}
</style>
