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
      participantsrendezvous: [],
      participantsusers: [],
      membres: [],
      creation: "",
      notparticipant: "",
      pasparticiper: "",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    this.moment = moment;

    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    this.evenements = await fetched_evenements.json();
    // console.table(this.evenements);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    // console.table(this.membres);

    let fetched_participants = await fetch(
      "http://127.0.0.1:8000/api/participants"
    );
    this.participants = await fetched_participants.json();
    console.table(this.participants);

    let fetched_participantsrendezvous = await fetch(
      "http://127.0.0.1:8000/api/participants/rendezvous/"+this.$route.params.evenement
    );
    this.participantsrendezvous = await fetched_participantsrendezvous.json();
    console.table(this.participantsrendezvous);


    let fetched_participantsusers = await fetch(
      "http://127.0.0.1:8000/api/participants/user/"+this.user.id
    );
    this.participantsusers= await fetched_participantsusers.json();
    console.table(this.participantsusers);
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
      <h1 class="titreevenement">{{ evenement.title }} :</h1>
      <div class="presentationevenement">
        <div class="description">
          <p class="titreobject">Objet du rendez-vous :</p>
          <br />
          <li class="listdescription">{{ evenement.description }}</li>
        </div>
        <hr />
        <div class="participantsrendezvous">
          <div v-if="participants.length > 0">
            Les membres de l'association présents à l'évènement "{{
              evenement.title
            }}" :
            <ul class="participantsaurendezvous" style="margin-top: -42px">
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
                        <div v-if="participant.user_id == user.id">
                          <router-link
                            :to="`/updateinscription/${participant.id}`"
                            style="border: none; width: 20px"
                          >
                            <img
                              src="https://cdn-icons-png.flaticon.com/128/8497/8497914.png"
                              style="width: 20px"
                              alt=""
                              @click="DisplayUpdate()"
                            />
                          </router-link>
                        </div>
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
  <hr />

  <div v-for="participantsuser in participantsusers">
    <div
      v-if="
        participantsuser.rendezvous_id == this.$route.params.evenement 
      "
    >
    <div v-show="participantsuser.participants_id === '0'">
      <!-- {{ participantsuser }} -->
      <div class="pasinscritchangementavis">
          je souhaite m'inscrire :
          <br />
          
          <router-link
            :to="`/updateinscription/${participantsuser.id}`"
            style="border: none"
          >
            <button class="button rounded-lg button-disabled">
              Je m'inscris
            </button>
          </router-link>
        </div>
    </div>
    
    </div>
  </div>
<br>
<div v-if="participantsrendezvous.length===0">
Personne n'est encore inscrit 
  </div>

<div v-for="participantrendezvous in participantsrendezvous">
<div v-show="participantrendezvous.user_id == user.id">
<div v-if="participantrendezvous.length === 0">
  
  coucou
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
.listdescription {
  list-style-type: "\1F44D";
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

.titreobject {
  width: 85% !important;
  margin-right: 150px;
}
.description {
  border: 1px solid;
  border-radius: 10px;
  padding: 3px;
  margin-bottom: 13px;
  padding-left: 15px;
  display: flex;
}
.pasinscritchangementavis {
  text-align: center;
  margin-top: 25px;
  font-size: 20px;
  width: 100%;
}
</style>
