<script>
import { ref, reactive } from "vue";
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
      evenements: [],
      participantsnames: [],

      //
    };
  },

  async created() {
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );

    let lListe = await fetched_evenements.json();
    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));
    console.log(lListe);
    //this.evenements = await fetched_evenements.json();
    this.evenements = lListe;

    console.table(this.evenements);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_participants = await fetch(
      "http://127.0.0.1:8000/api/participants"
    );
    this.participants = await fetched_participants.json();
    console.log(this.participants);
  },

  methods: {
    createcompterendu: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("date", this.date);
      urlencoded.append("participants", this.participantsnames);
      urlencoded.append("contenus", this.contenus);
      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/compterendu";

      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.push({ path: "/compterendu" });
      }, "2000");
    },
  },
};
</script>
<template>
  <router-link :to="`/compterendu`">
    <button class="button rounded-lg retourcompterendu">Retour</button>
  </router-link>
  <h1 style="margin-bottom: 50px">
    Ajouter le compte rendu de la dernière réunion
  </h1>

  <div class="inputdate">
    <label for="date">Date:</label>
    <input type="date" v-model="date" name="date" id="date" />
  </div>

  <br />
  <div class="input">
    <label for="contenus">Contenus:</label>
    <textarea v-model="contenus" class="input" type="text" />
  </div>
  <div class="inputmembre">
    <label for="participants">Participants:</label>

    <div class="contenusparticipantsréunion">
      <div v-for="membre in membres" class="participantsreunion">
        <input
          type="checkbox"
          id="particpantsname"
          :value="membre.prenom"
          name="participantsname"
          v-model="participantsnames"
        />

        <label style="margin-left: 5px" for="participants">{{
          membre.prenom
        }}</label>
      </div>
      <button
        class="button rounded-lg createcompterendu"
        @click="createcompterendu()"
      >
        Valider
      </button>
    </div>
  </div>
  <!-- <h4>Les participant sont : {{ participantsnames }}</h4> -->
</template>
<style>
.retourcompterendu{
  margin-left: 15px;
}
/* .addcompterendu {
  margin-top: 8% !important;
  margin-left: 3%;
  width: 95%;
} */
.inputmembre {
  margin-left: 416px;
}
.inputdate {
  display: flex;
  justify-content: space-between;
  margin-left: 422px;
  margin-right: 269px;
}
.participantsreunion {
  display: flex;
  border: 1px solid;
  border-radius: 5%;
  margin-top: 10px !important;
  width: 150px;
  align-items: center;
  padding-left: 5px;
  margin-left: 317px;
}
.contenusparticipantsréunion {
  margin-top: -30px;
}
.createcompterendu {
  margin-left: 206px;
  margin-top: 40px !important;
}
</style>
