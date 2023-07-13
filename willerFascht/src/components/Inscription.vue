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
      noparticipant: [],
      participantid: [],
      participants: [],

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
    inscription: function (participant) {
      console.log(participant.rendezvous_id);

      var tableparticipants =[this.participantid]
      console.log(tableparticipants);
      var urlencoded = new URLSearchParams();
      urlencoded.append("participants_id", tableparticipants );
      urlencoded.append("noparticipants_id", participant.noparticipants_id + " " + this.noparticipant);
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url =
        "http://127.0.0.1:8000/api/participants/"+participant.id;

      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      // setTimeout(() => {
      //   this.$router.push({ path: "/liste"});
      // }, "2000");
    },
  },
};
</script>
<template>
  <router-link :to="`/liste`">
    <button class="button rounded-lg updateTache">Retour</button>
  </router-link>
  <div v-for="participant in participants">
    <div v-show="$route.params.participant == participant.id">
      <div v-for="evenement in evenements">
        <div v-show="evenement.id == participant.rendezvous_id">
          <h1>{{ evenement.title }}</h1>
        </div>
      </div>
      <!-- <h1>{{ evenement.title }}</h1> -->
      <div class="question">
        <div>
          <fieldset>
            <legend>Je participe à l'évènement ?</legend>

            <div v-for="membre in membres" class="participants">
              <input
                type="checkbox"
                id="particpants_id"
                :value="membre.prenom"
                name="particpantid[]"
                v-model="participantid"
              />

              <label style="margin-left: 5px" for="particpants">{{
                membre.prenom
              }}</label>
            </div>
          </fieldset>
        </div>
        <div>
          <fieldset>
            <legend>Je ne peux pas participer à l'évènement :</legend>

            <div v-for="membre in membres" class="participants">
              <input
                type="checkbox"
                id="noparticpants_id"
                :value="membre.prenom"
                v-model="noparticipant"
              />

              <label style="margin-left: 5px" for="particpants">{{
                membre.prenom
              }}</label>
            </div>
          </fieldset>
          <button
            class="button rounded-lg"
            style="margin-left: -50%; margin-top: 10% !important"
            @click="inscription(participant)"
          >
            Valider
          </button>
        </div>
      </div>
    </div>
  </div>
  <h4>Les participant sont : {{ participantid }}</h4>
  <h4>Les absents sont : {{ noparticipant }}</h4>
</template>
<style>
.inscription {
  margin-top: 5% !important;
  margin-left: 1%;
}
.participants {
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
  margin-left: 26%;
  margin-right: 22%;
  font-size: 19px;
}
</style>
