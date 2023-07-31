<script>
import { ref, reactive } from "vue";
import { useRoute } from "vue-router";
const route = useRoute();
import moment from "moment";

export default {
  data() {
    return {
      mode: false,
      route: route,
      start: null,
      end: null,
      toutevenement: null,
      resultparticipation: null,
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
    this.moment = moment;
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
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
    inscription: function () {
      console.log(this.user.id);
      console.log(this.$route.params.evenement);
      console.log(this.participantid);
      console.log(this.resultparticipation);

      var urlencoded = new URLSearchParams();
      urlencoded.append("participants_id", this.participantid);
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("rendezvous_id", this.$route.params.evenement);
      urlencoded.append("complement", this.resultparticipation);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/participants";

      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.push({ path: "/liste"});
      }, "2000");
    },
  },
};
</script>
<template>
  <router-link :to="`/liste`">
    <button class="button rounded-lg updateTache">Retour</button>
  </router-link>

  <div v-for="rendezvous in evenements">
    <div v-if="rendezvous.id == $route.params.evenement">
      <h1>Inscription à l'évènement {{ rendezvous.title }}</h1>
      <h2>
        Du {{ moment(rendezvous.start).format(" DD/MM/YYYY [à] HH[h]mm ") }} au
        {{ moment(rendezvous.end).format(" DD/MM/YYYY [à] HH[h]mm ") }} :
      </h2>

      <p class="descriptionrendezvousinscription">
        {{ rendezvous.description }}
      </p>
      <hr />

      <div class="questionnaireinscription">
        <input
          type="radio"
          id="Choice2"
          v-model="participantid"
          @click="dispo()"
          name="participants"
          value="1"
        />
        <label for="Choice2">Je m'inscris</label>

        <input
          type="radio"
          id="Choice3"
          v-model="participantid"
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
          :min="rendezvous.start"
        />
        à
        <input
          type="time"
          class="inputtime"
          name="end"
          v-model="end"
          :max="rendezvous.end"
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
          @click="inscription()"
          class="button rounded-lg button-disabled"
        >
          Valider
        </button>
      </div>
    </div>
  </div>
  {{ resultparticipation }}
</template>
<style>
.validateinscription {
  margin-left: 45%;
}

.descriptionrendezvousinscription {
  text-align: justify;
  margin-top: 25px;
  padding-left: 13px;
  padding-right: 13px;
  margin-bottom: 4%;
}
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
.inuputinscription {
  margin-left: 15px;
  margin-top: 15px;
  display: flex;
  justify-content: space-between;
  width: 134px;
}
.inuputjeneparticipepas {
  margin-left: 15px;
  margin-top: 15px;
  display: flex;
  justify-content: space-between;
  width: 195px;
}
.validationhoraire {
  width: 40px;
}
.validatehoraire :hover {
  cursor: pointer;
}
.questionnaireinscription {
  font-size: 20px;
  display: flex;
  justify-content: space-between;
  margin-left: 30%;
  margin-right: 30%;
  margin-top: 65px;
  /* border: 1px solid; */
  /* border-radius: 15px; */
  padding: 15px;
}
.heuredispo {
  display: flex;
  justify-content: space-between;
  margin-left: 31%;
  margin-top: 4px;
  margin-bottom: 2%;
  width: 560px;
  font-size: 19px;
}
.inputtime {
  width: 112px;
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
