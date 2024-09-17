<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import moment from "moment/min/moment-with-locales";

const route = useRoute();

export default {
  data() {
    return {
      mode: false,
      start: null,
      end: null,
      toutevenement: null,
      resultparticipation: null,
      user: [],
      participants: [],
      evenements: [],
      membres: [],

      //
    };
  },

  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    this.moment = moment;

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );

    this.evenements = await fetched_evenements.json();
    console.table(this.evenements);

    let fetched_participants = await fetch(
      "http://127.0.0.1:8000/api/participants"
    );
    this.participants = await fetched_participants.json();
    console.table(this.participants);
  },

  methods: {
    displaydisponibilite() {
      this.mode = "displayhoraire";
      this.resultparticipation =
        "Je serais disponible de " + this.start + " à " + this.end;
      return this.resultparticipation;
    },
    validatehoraire() {
      this.resultparticipation =
        "Je serais disponible de " + this.start + " à " + this.end;
      return this.resultparticipation;
    },
    pasinscrit() {
      this.resultparticipation = "RAS ";
      return this.resultparticipation;
    },
    dispo() {
      this.mode = "disponible";
    },
    displaydisponibilitetoutelajournee() {
      this.mode = "disponible";
      this.resultparticipation = "Je serais disponible toute la journée";
      return this.resultparticipation;
    },
    inscription: function (participant, evenement) {
      console.log(this.user.id);
      console.log(evenement.id);
      console.log(participant.participants_id);
      console.log(this.resultparticipation);

      var urlencoded = new URLSearchParams();
      urlencoded.append("participants_id", participant.participants_id);
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("rendezvous_id", evenement.id);
      urlencoded.append("complement", this.resultparticipation);

      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/participants/"+this.$route.params.participant;

      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.push({ path: "/rendezvous/" + evenement.id });
      }, "2000");
    },
  },
};
</script>

<template>
  <div v-for="participant in participants">
    <div v-if="participant.id == $route.params.participant">
      <div v-for="evenement in evenements">
        <div v-if="evenement.id ==participant.rendezvous_id">
          <router-link :to="`/rendezvous/${evenement.id}`">
            <button class="button rounded-lg">Retour</button>
          </router-link>

        <h1>Modifier ma participation pour l'évènement {{ evenement.title }}</h1>
        <h2>Du {{ moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm ")  }} au {{ moment(evenement.end).format("DD/MM/YYYY [à] HH[h]mm ") }}</h2>
        

        <div class="questionnaireinscription">
        <input
          type="radio"
          id="Choice2"
          v-model="participant.participants_id"
          @click="dispo()"
          name="participants"
          value="1"
        />
        <label for="Choice2">Je m'inscris</label>

        <input
          type="radio"
          id="Choice3"
          v-model="participant.participants_id"
          name="participants"
          value="0"
          @click="pasinscrit()"
        />
        <label for="Choice3">Je ne suis pas libre</label>
      </div>

      <div
        class="heuredispo"
        v-if="mode == 'disponible' || mode == 'displayhoraire'"
      >
        <input
          type="radio"
          id="disponibilite1"
          @click="displaydisponibilitetoutelajournee()"
          name="disponibilte"
          v-model="toutevenement"
          value=" durant tout l'évènement"
        />
        <label for="disponibilite"
          >Je serais disponible durant tout l'évènement .</label
        >
      </div>

      <div
        class="heuredispo"
        v-if="mode == 'disponible' || mode == 'displayhoraire'"
      >
        <input
          type="radio"
          id="disponibilite1"
          @click="displaydisponibilite()"
          name="disponibilte"
        />
        <label for="disponibilite"
          >Je ne suis pas disponible durant tout l'évènement :</label
        >
      </div>

      <div class="heuredispo" v-if="mode == 'displayhoraire'">
        <label for="appt-time">Je pourrais être dispo de :</label>
        <input
          class="inputtime"
          type="time"
          v-model="start"
          :min="evenement.start"
        />
        à
        <input
          type="time"
          class="inputtime"
          name="end"
          v-model="end"
          :max="evenement.end"
        />
        <img
          src="https://cdn-icons-png.flaticon.com/128/7444/7444392.png"
          class="validationhoraire"
          @click="validatehoraire()"
          alt=""
        />
      </div>

      <div class="validateinscription">
        <button
          @click="inscription(participant, evenement)"
          class="button rounded-lg button-disabled"
        >
          Valider
        </button>
      </div>
      </div>
    </div>
    </div>
  </div>
</template>
<style scoped>
.updateinscription {
  /* margin-top: 8% !important; */
  width: 90%;
  margin: auto;
  font-size: large;
}
.validatehoraire:hover {
  cursor: pointer;
}
</style>
