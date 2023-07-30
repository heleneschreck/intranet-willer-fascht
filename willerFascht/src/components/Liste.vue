<script>
import { mapState } from "vuex";
// import { ref, reactive } from "vue";
import { useLocalStorage, useMouse, usePreferredDark } from "@vueuse/core";
// import { formatDate } from "@fullcalendar/core";
import moment from "moment";

export default {
  data() {
    return {
      mode: false,

      user: [],
      projects: [],
      statuts: [],
      taches: [],
      membres: [],
      evenements: [],
      participants: [],
      mode: "nodisplay",

      //
    };
  },
  computed: {
    ...mapState(["userInfos"]),
    // eventBackgroundColor() {
    //   return (evenement) => evenement.backgroundcolor;
    // },
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    this.moment = moment;

    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    let lListe = await fetched_evenements.json();
    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));
    this.evenements = lListe;
    console.table(this.evenements);

    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));

    let fetched_participants = await fetch(
      "http://127.0.0.1:8000/api/participants"
    );
    this.participants = await fetched_participants.json();
    console.table(this.participants);
  },
  computed: {
    createdparticipants: function () {
      return this.participants.filter(function (participants, evenements) {
        return this.participants.rendezvous_id == this.evenements.id;
      });
    },
  },
  // async beforeMount(){
  //   await this.participants.forEach(rendezvous_id => {
  //     this.final[i] = [];
  //    this.evenements.forEach()
  //   })

  // },

  methods: {
    getBackgroundColor(evenement) {
    return evenement.backgroundcolor;
  },
    delete_rendezvous: function (evenement, participant) {
      console.log(evenement.id);

      console.log(participant.rendezvous_id);
      console.log(participant.id);

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/rendezvous/" + evenement.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url2 = "http://127.0.0.1:8000/api/participants/" + participant.id;
      fetch(url2, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    display() {
      this.mode = "displaymodif";
    },
    sortir() {
      this.mode = "nodisplay";
    },
  },
};
</script>
<template>
  <h1>Liste des rendez-vous organisés</h1>
  <div class="action">
    <router-link :to="`/planning`">
      <button class="button rounded-lg" style="padding: 13.5px !important">
        Planning
      </button>
    </router-link>

    <router-link :to="`/addEvent`">
      <button
        href="#"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
      >
        Ajouter un évènement
      </button></router-link
    >
  </div>
  <div class="listDeRendezVous">
    <table>
      <thead>
        <th>Début</th>
        <th>Fin</th>
        <th>Evènement</th>
        <th>Description</th>
        <!-- <th>Supprimer</th> 
         <th >Inscription</th> -->
      </thead>
      <tbody>
        <tr
        v-for="evenement in evenements"
        v-show="moment(evenement.end) > moment() "
        :style="{ color: getBackgroundColor(evenement) }"
        >
      
          <!--    v-bind:src="affiche.url" -->
          <td>
            {{ moment(evenement.start).format(" DD/MM/YYYY [à] HH[h]mm ") }}
          </td>
          
          <td @mouseover="sortir()">
            {{ moment(evenement.end).format("DD/MM/YYYY [à] HH[h]mm ") }}
          </td>
          
          <td @mouseover="display()">
            <router-link :to="`/rendezvous/${evenement.id}`">
              {{ evenement.title }}
              <div v-show="user.niveau == '1'">
                <div
                class="modiferevenement"
                v-if="mode == 'displaymodif'"
                title="Modifier le rendez-vous"
                >
                <router-link
                :to="`/updaterendezvous/${evenement.id}`"
                style="border: none"
                >
                  <img
                  src="https://cdn-icons-png.flaticon.com/128/8497/8497914.png"
                  alt=""
                  />
                  </router-link>
                </div>
              </div>
            </router-link>
          </td>
          
          <td @mouseover="sortir()" style="width: 30% !important">
            {{ evenement.description.substring(0, 50) }}
            <!-- <div v-if= "evenement.description.substring > (0,50) " >
              exeterat
              
              
            </div>
          --></td>
          
          <div v-for="participant in participants">
            <div v-if="evenement.id == participant.rendezvous_id">
              <td>
                <button
                style="border: none"
                @click="delete_rendezvous(evenement, participant)"
                title="Bon à effacer?"
                >
                <img
                src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                style="width: 39px; margin: auto"
                alt=""
                />
              </button>
            </td>
            <td>
              <router-link :to="`/inscription/${participant.id}`">
                <img
                src="https://cdn-icons-png.flaticon.com/512/1634/1634406.png"
                    style="width: 39px; margin: auto"
                    alt=""
                    />
                  </router-link>
              </td>
            </div>
          </div>
   
        </tr>
      </tbody>
    </table>
  </div>
</template>
<style>
.liste {
  margin-top: 8% !important;
  width: 90%;
  margin: auto;
}
td {
  text-align: center;
  align-content: center;
  width: 15% !important;
  /* padding: -15px !important; */
}
.modiferevenement {
  width: 11%;
}
</style>
