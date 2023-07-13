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
      noparticipant: [],
      participant:[],
      evenements: [],

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
  },

  methods: {
    
    
    
    
    createInscription: function () {
// let membre_id = document.querySelector('input');
// console.log(membre_id);
//       if (membre_id.checked == true){
//         console.log(this.membre_id);
  // membre_id.value == this.membres.id
  var urlencoded = new URLSearchParams();
  urlencoded.append("participants_id", this.participant);
  urlencoded.append("noparticipants_id", this.noparticipant);
  urlencoded.append("rendezvous_id", this.$route.params.evenement);
  var requestOptions = {
    method: "POST",
    body: urlencoded,
    redirect: "follow",
  };

  let url =
    "http://127.0.0.1:8000/api/participants";
  fetch(url, requestOptions)
    .then((response) => response.text())
    .then((result) => console.log(result))
    .catch((error) => console.log("error", error));

  setTimeout(() => {
    this.$router.push({ path: "/liste"});
      }, "2000");
},
      }


};
</script>
<template>
  <br>
  <br>
  <br>
  <br>
  <!-- {{ $route.params.response }} -->
  <div
    v-for="evenement in evenements"
    v-show="$route.params.evenement == evenement.id"
  >
    <router-link :to="`/liste`">
      <button class="button rounded-lg updateTache">Retour</button>
    </router-link>
    <h1>{{ evenement.title }}</h1>
    <div class="question">
      <div>
        <fieldset>
          <legend>Je participe à l'évènement ?</legend>

          <div v-for="membre in membres" class="membre_id">
          
            <input
              type="checkbox"
              v-model="participant"
              :value="membre.prenom"
              id="participant_id"
           
            />

            <label style="margin-left: 5px" for="participants">{{
              membre.prenom
            }}</label>
          </div>
        </fieldset>
      </div>
      <div>
        <fieldset>
          <legend>Je ne peux pas participer à l'évènement :</legend>

          <div v-for="membre in membres" class="membre_id">
            <input
            type="checkbox" 
            v-model="noparticipant"   
            :value="membre.prenom"
            id="noparticipants_id"
            />
            
            <label style="margin-left: 5px" for="participants">  
            {{  membre.prenom}} 
            </label>
          </div>
        </fieldset>
        <button
          class="button rounded-lg"
          style="margin-left: -50%; margin-top: 10% !important"
          @click="createInscription()"
        >
          Valider
        </button>
      </div>
    </div>
  </div>
  <h4>Les participant sont : {{ participant }}</h4>
  <h4>Les absents sont : {{ noparticipant }}</h4>
</template>
<style>
.createinscriptions {
  margin-top: 4% !important;
  margin-left: 1%;
}
/* .participants {
  display: flex;
  border: 1px solid;
  border-radius: 5%;
  margin-top: 10px !important;
  width: 150px;
  align-items: center;
  padding-left: 5px;
}
.question {
  margin-top: 31px;
  display: flex;
  justify-content: space-between;
  /* width: 37%; */
  /* margin-left: 26%;
  margin-right: 22%;
  font-size: 19px; */
/* }  */
</style>