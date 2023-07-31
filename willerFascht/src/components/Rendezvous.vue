<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import moment from "moment/min/moment-with-locales";

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
      mode: "nodisplay",
      tache: "",
      evenements: [],
      participants: [],
      membres: [],
      creation: "",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
    formatDate() {
      return this.formatDate(this.tache.end);
    },
  },
  async created() {
    this.moment = moment;
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );

    this.evenements = await fetched_evenements.json();
    console.table(this.evenements);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_participants = await fetch(
      "http://127.0.0.1:8000/api/participants"
    );
    this.participants = await fetched_participants.json();
    console.table(this.participants);

    //  if (this.participants.rendezvous_id != this.evenements.id) {
    //    result = "Personne n'est encore inscrit à cet évènement. Voules vous vous inscrire ?"
    //  } return result;
    // let creation = this.participants.rendezvous_id != this.evenements.id

    // console.log(this.user.id);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
};
</script>
<template>
  <router-link :to="`/liste`">
    <button class="button rounded-lg">Retour</button>
  </router-link>
  <div v-for="evenement in evenements">
    <div v-if="evenement.id == $route.params.evenement">
      <div class="date">{{ moment(evenement.start).format("DD/MM/YYYY") }}</div>
      <h1>{{ evenement.title }} :</h1>
      <div class="presentationevenement">
        <div class="description">
          Objet du rendez-vous :
          <br />
          <li class="list">{{ evenement.description }}</li>
        </div>
        <hr />
        <div class="participantsrendezvous">
          <!-- <div v-for="participant in participants"> -->
            <!-- </div> -->
            
            <div v-show="participants.length === 0">
              <p class="personneinscrits">
                Personne n'est encore inscrit à l'évènement :
                <router-link :to="`/inscription/${$route.params.evenement}`">
                  <button class="button rounded-lg">
                    <img
                    src="https://cdn-icons-png.flaticon.com/128/2620/2620163.png"
                    style="width: 60px"
                    alt=""
                    />
                  </button>
                </router-link>
              </p>
            </div>
            <div v-if="participants.length > 0">
              Les membres de l'association présents à l'évènement "{{
                evenement.title
              }}" :
            <ul class="participantsaurendezvous">
              <div
              v-for="participant in participants"
              :key="participant.id"
                class="list"
              >
                <div
                  v-if="
                    participant.rendezvous_id == $route.params.evenement &&
                    participant.participants_id == '1'
                  "
                >
                <li>
                  <div v-for="membre in membres" :key="membre.id">
                    <div v-if="membre.id == participant.user_id">
                      {{ membre.prenom }}: "{{ participant.complement }}"
                    </div>
                  </div>
               
                   
                
                </li>
              </div>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<style>
h1 {
  font-weight: bolder !important;
}
.rendezvous {
  margin-top: 8% !important;
  width: 90%;
  margin: auto;
  font-size: large;
}
.personnel {
  margin-left: 570px !important;
}
.personneinscrits {
  font-size: 35px;
  padding-top: 18px;
  margin-left: 270px;
}
.date {
  border: 1px solid;
  border-radius: 10px;
  padding: 4px;
  width: 128px;
  /* size: b4; */
  font-size: 24px;
  text-align: center;
  margin-left: 90%;
}

.list {
  list-style-type: "\1F44D";
  margin-left: 680px;
  margin-top: 15px !important;
}
span {
  font-weight: bold;
}
.participantsrendezvous {
  /* border: 1px solid; */
  border-radius: 10px;
  padding: 3px;
  padding-left: 15px;
  display: flex;
}
/* .participantsalareunion {
  /* display: flex; 
} */

.description {
  border: 1px solid;
  border-radius: 10px;
  padding: 3px;
  margin-bottom: 13px;
  padding-left: 15px;
  display: flex;
}
</style>
