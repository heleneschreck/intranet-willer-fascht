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
      participantsrendezvous: [],
      evenements: [],
      membres: [],
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
    console.table(this.membres);

    let fetched_participantsrendezvous = await fetch(
      "http://127.0.0.1:8000/api/participants/rendezvous/" +
        this.$route.params.evenement
    );
    this.participantsrendezvous = await fetched_participantsrendezvous.json();
    console.table(this.participantsrendezvous);
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
      <h1>{{ evenement.title }} :</h1>
      <div v-if="participantsrendezvous.length == 0" class="personneinscrit">
        <br />
        Personne n'est encore inscrit
      </div>
      <div v-else>
        <table class="listeparticipant">
          <thead>
            <th class="enteteparticipants">Membre participant à l'evenement</th>
            <th class="enteteparticipants">
              Membre ne pouvant pas participer à l'évènement
            </th>
          </thead>
          <tbody>
            <tr>
              <td>
                <div v-for="membre in membres">
                  <div
                    v-for="participantrendezvous in participantsrendezvous"
                    v-show="participantrendezvous.participants_id == '1'"
                  >
                    <div v-if="membre.id == participantrendezvous.user_id">
                      <li class="listparticipants">
                        {{ membre.prenom }} <br />
                        {{ participantrendezvous.complement }}
                      </li>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div v-for="membre in membres">
                  <div
                    v-for="participantrendezvous in participantsrendezvous"
                    v-show="participantrendezvous.participants_id == '0'"
                  >
                    <div v-if="membre.id == participantrendezvous.user_id">
                      <li class="listparticipants">
                        {{ membre.prenom }} <br />
                      </li>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
 <br>


 <ul>
  <li v-for="membre in membres" :key="membre.id">
    <div class="participantsrendezvous">
      {{ membre.prenom }}
      <div v-if="membre.id == userInfos.id"> ok</div>
    </div>
  </li>
</ul>

</template>
<style scoped>
.participant {
  /* margin-top: 8% !important; */
  width: 90%;
  margin: auto;
}
.listeparticipant {
  font-size: 20px;
}
.listparticipants {
  list-style-type: "\1F44D";
}
.personneinscrit {
  text-align: center;
  font-size: 20px;
}
.enteteparticipants {
  font-weight: bold !important;
}
</style>
