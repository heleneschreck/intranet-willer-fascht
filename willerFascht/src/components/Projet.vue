<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import moment from "moment";
import { VueDraggableNext } from "vue-draggable-next";
const route = useRoute();

export default {
  data() {
    return {
      enabled: true,
      dragging: false,
      mode: false,
      route: route,
      user: [],
      projects: [],
      statuts: [],
      taches: [],
      mode: "nodisplay",
      tache: "",
      dragtache: null,
      changementutilisateur: null,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.moment = moment;

    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }
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

    this.taches.sort((a, b) => a.end.localeCompare(b.end));

    console.table(this.taches);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    formatDate(dateString) {
      const date = dayjs(dateString);
      // Then specify how you want your dates to be formatted
      return date.format("dddd MMMM D, YYYY");
    },

    chargerTaches() {
      fetch("http://localhost:8000/api/taches")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.taches = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    deleteTache: function (tache) {
      console.log(tache.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/" + tache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerTaches();
        })
        .catch((error) => console.log("error", error));
    },
    updateStatut1: function (tache) {
      console.log(tache.status_id - "1");
      console.log(tache.id);
      var myHeaders = new Headers();

      var urlencoded = new URLSearchParams();
      urlencoded.append("status_id", tache.status_id - "1");

      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/" + tache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerTaches();
        })
        .catch((error) => console.log("error", error));
    },
    updateStatut: function (tache) {
      let increment = tache.status_id;
      console.log(increment);
      increment++;
      console.log(increment);

      console.log(tache.status_id - "1");
      console.log(tache.id);
      var myHeaders = new Headers();

      var urlencoded = new URLSearchParams();
      urlencoded.append("status_id", increment++);

      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/" + tache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerTaches();
        })
        .catch((error) => console.log("error", error));
    },
    onDragStart: function (statut, tache) {
      // Store the data being dragged (e.g., task ID) in the dataTransfer object
      event.dataTransfer.setData("text/plain", tache.id);
    },
    onDragOver: function () {
      // Prevent default behavior to allow dropping
      window.event.preventDefault();
    },
    onDrop: function (statut, tache) {
      // Get the data being dragged (e.g., task ID) from the dataTransfer object
      const taskId = event.dataTransfer.getData("text/plain");

      // Assuming you have a method to update the status of a task based on its ID
      this.updateTaskStatus(taskId, statut.id);
    },
    updateTaskStatus: function (taskId, newStatusId) {
      // Make a PUT request to your API to update the status of the task
      // Example code for updating the status using fetch:
      var url = `http://127.0.0.1:8000/api/taches/${taskId}`;
      var myHeaders = new Headers();
      var urlencoded = new URLSearchParams();
      urlencoded.append("status_id", newStatusId);
      var requestOptions = {
        method: "PUT",
        headers: myHeaders,
        body: urlencoded,
        redirect: "follow",
      };
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerTaches();
        })
        .catch((error) => console.log("error", error));
    },

    display: function () {
      this.mode = "display";
    },
    nodisplay: function () {
      this.mode = "nodisplay";
    },
    reacffectation: function (tache) {
      // console.log(tache.id);
      this.changementutilisateur = tache.id;
      console.log(this.changementutilisateur);
      this.mode = "reacffectation";
      // return changementutilisateur
    },
    updateTache: function () {
      console.log(this.changementutilisateur);
      var myHeaders = new Headers();

      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user_id);

      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url =
        "http://127.0.0.1:8000/api/taches/" + this.changementutilisateur;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, 2000);
    },
  },
};
</script>
<template>
  <router-link :to="`/todo`">
    <button
      class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste"
    >
      Retour
    </button>
  </router-link>

  <br />
  <!-- La tache a changé est {{ dragtache }} -->
  <div v-for="project in projects" v-show="$route.params.projet == project.id">
    <h1 style="background-color: transparent">{{ project.title }}</h1>

    <div class="tableau">
      <div v-for="statut in statuts" class="statut">
        {{ statut.statut }}

        <router-link :to="`/ajoutTache/${$route.params.projet}/${statut.id}`">
          <button style="width: 30px; border: none">
            <img
              src="https://cdn-icons-png.flaticon.com/512/189/189689.png"
              class="ajout"
              alt="ajout"
            />
          </button>
        </router-link>
        <div
          v-if="mode == 'nodisplay'"
          class="taches"
          v-for="tache in taches"
          v-show="
            statut.id == tache.status_id &&
            tache.user_id == user.id &&
            tache.project_id == project.id
          "
          :draggable="true"
          @dragstart="onDragStart(statut, tache)"
          @dragover="onDragOver"
          @drop="onDrop(statut, tache)"
        >
          <div
            v-if="tache.status_id == 3"
            style="
              text-decoration: line-through !important;
              color: red;
              font-size: 15px;
            "
            class="rounded-lg shadow termine"
          >
            <router-link :to="`/tache/${tache.id}`">
              {{ tache.title }}
            </router-link>
            <hr />
            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}
            <br />
            <div class="decremente">
              <button @click="updateStatut1(tache)" title="Changer le statut">
                <img
                  class="boutondecremente"
                  src="https://cdn-icons-png.flaticon.com/128/2252/2252536.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
            </div>
            <div class="suppmod">
              <button @click="deleteTache(tache)" title="Supprimer">
                <img
                  src="https://cdn-icons-png.flaticon.com/512/1027/1027522.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
              <button>
                <router-link
                  :to="`/updateTache/${$route.params.projet}/${tache.id}`"
                  title="Modifier"
                >
                  <img
                    src="https://cdn-icons-png.flaticon.com/512/1634/1634406.png"
                    style="width: 30px"
                    alt=""
                  />
                </router-link>
              </button>
            </div>
          </div>
          <div
            v-else-if="tache.status_id == 2"
            style="color: green"
            class="encours rounded-lg shadow"
            draggable="true"
          >
            <router-link :to="`/tache/${tache.id}`">
              <div
                v-show="moment() > moment(tache.end)"
                style="
                  display: flex;
                  justify-content: space-around;
                  margin-left: 15%;
                  margin-right: 15%;
                "
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/128/10099/10099006.png"
                  alt="attention"
                  class="urgence"
                />
                <span class="attention">
                  Attention à finir de toute urgence</span
                >
              </div>
              {{ tache.title }}
            </router-link>
            <hr />
            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}
            <div class="decremente">
              <button @click="updateStatut1(tache)" title="Changer le statut">
                <img
                  class="boutondecremente"
                  src="https://cdn-icons-png.flaticon.com/128/2252/2252536.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
            </div>
            <div class="incremente">
              <button @click="updateStatut(tache)" title="Changer le statut">
                <img
                  class="boutonincremente"
                  src="https://cdn-icons-png.flaticon.com/128/2252/2252536.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
            </div>
            <br />
            <div class="suppmod">
              <button @click="deleteTache(tache)" title="Supprimer">
                <img
                  src="https://cdn-icons-png.flaticon.com/512/1027/1027522.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
              <button>
                <router-link
                  :to="`/updateTache/${$route.params.projet}/${tache.id}`"
                  title="Modifier"
                >
                  <img
                    src="https://cdn-icons-png.flaticon.com/512/1634/1634406.png"
                    style="width: 30px"
                    alt=""
                  />
                </router-link>
              </button>
            </div>
          </div>
          <div
            v-else
            style="color: goldenrod"
            class="enattente rounded-lg shadow"
          >
            <router-link :to="`/tache/${tache.id}`">
              <div
                v-show="moment() > moment(tache.end)"
                style="
                  display: flex;
                  justify-content: space-around;
                  margin-left: 15%;
                  margin-right: 15%;
                "
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/128/10099/10099006.png"
                  alt="attention"
                  class="urgence"
                />
                <span class="attention">
                  Attention à finir de toute urgence</span
                >
              </div>
              {{ tache.title }}
            </router-link>
            <hr />
            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}
            <div class="incremente">
              <button @click="updateStatut(tache)" title="Changer le statut">
                <img
                  class="boutonincremente"
                  src="https://cdn-icons-png.flaticon.com/128/2252/2252536.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
            </div>

            <br />
            <div class="suppmod">
              <button @click="deleteTache(tache)" title="Supprimer">
                <img
                  src="https://cdn-icons-png.flaticon.com/512/1027/1027522.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
              <button>
                <router-link
                  :to="`/updateTache/${$route.params.projet}/${tache.id}`"
                  title="Modifier"
                >
                  <img
                    src="https://cdn-icons-png.flaticon.com/512/1634/1634406.png"
                    style="width: 30px"
                    alt=""
                  />
                </router-link>
              </button>
            </div>
          </div>
        </div>

        <hr />

        <div
          v-if="mode == 'display'"
          class="taches"
          v-for="tache in taches"
          v-show="
            statut.id == tache.status_id &&
            tache.user_id != user.id &&
            user.niveau == 1 &&
            tache.project_id == project.id
          "
        >
          <div
            v-if="tache.status_id == 3"
            style="
              text-decoration: line-through !important;
              color: red;
              font-size: 15px;
            "
            class="termine"
          >
            <router-link :to="`/tache/${tache.id}`">
              {{ tache.title }}
            </router-link>
            <hr />

            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}
            <hr />
            <div
              class="prenom"
              v-for="membre in membres"
              v-show="membre.id == tache.user_id"
            >
              <span @click="reacffectation(tache)" class="reaffectation">
                {{ membre.prenom }}</span
              >
            </div>
          </div>
          <div
            v-else-if="tache.status_id == 2"
            style="color: green"
            class="encours"
          >
            <router-link :to="`/tache/${tache.id}`">
              {{ tache.title }}
            </router-link>
            <hr />

            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}

            <hr />
            <div
              class="prenom"
              v-for="membre in membres"
              v-show="membre.id == tache.user_id"
            >
              <span @click="reacffectation(tache)" class="reaffectation">{{
                membre.prenom
              }}</span>
            </div>
          </div>
          <div v-else style="color: goldenrod" class="enattente">
            <router-link :to="`/tache/${tache.id}`">
              {{ tache.title }}
            </router-link>
            <hr />
            A faire avant le: <br />
            {{ moment(tache.end).format("DD/MM/YYYY") }}
            <hr />
            <div
              style=""
              v-for="membre in membres"
              v-show="membre.id == tache.user_id"
            >
              <span @click="reacffectation(tache)" class="reaffectation">{{
                membre.prenom
              }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="changementutilisateur" v-if="mode == 'reacffectation'">
        <img
          src="https://cdn-icons-png.flaticon.com/128/45/45320.png"
          class="croixContent"
          style="
            width: 15px;
            position: absolute;
            left: 93%;
            z-index: 1;
            top: 20px;
          "
          @click="sortir()"
          alt=""
        />
        <label for="user_id">Je confie cette tache à :</label>
        <select v-model="user_id" name="user_id" id="user_id">
          <option v-for="membre in membres" :value="membre.id">
            {{ membre.prenom }}
          </option>
        </select>
        <br />
        <br />
        <button
          @click="updateTache(tache)"
          class="block py-2 pl-3 pr-4 changement"
        >
          Ajouter la tache
        </button>
      </div>
    </div>
  </div>
  <br />
  <div v-show="user.niveau == 1">
    <div class="display" v-if="mode == 'nodisplay'">
      <button
        @click="display()"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste affichagetache"
      >
        Afficher l'avancé des taches assigné aux autres membres
      </button>
    </div>
    <div class="arriere" v-if="mode == 'display'">
      <button
        @click="nodisplay()"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste affichagetache"
      >
        Revenir sur mes taches
      </button>
    </div>
  </div>
</template>
<style scoped>
.projet {
  background-color: hsla(0, 0%, 84%, 0.3) !important;
}
.changementutilisateur {
  border: 1px solid;
  border-radius: 10px;
  padding: 15px;
  position: absolute;
  left: 641px;
  top: 116px;
  background-color: white;
  box-shadow: 10px 12px 15px black;
}
.changement {
  border-radius: 20px;
  margin-left: 26%;
  border: 5px solid green;
  background-color: green;
  color: white;
  font-weight: bold;
  font-size: 20px;
}
.reaffectation:hover {
  cursor: pointer;
}
.urgence {
  width: 25px;
}
.attention {
  color: red;
  font-size: 15px;
}
.display {
  margin-left: 40%;
}
.arriere {
  margin-left: 45%;
}
.boutondecremente {
  transform: rotate(-0.5turn);
}
.decremente {
  /* margin-left: -339px; */
  margin-bottom: 3px !important;
  margin-top: -44px !important;
  width: 30px;
}
.incremente {
  margin-left: 360px;
  margin-bottom: 3px !important;
  margin-top: -44px !important;
  width: 30px;
}
.projet {
  background-color: hsla(0, 0%, 84%, 0.3);
}
.retourtodo {
  margin-bottom: 1px !important;
  margin-left: 5%;
  background-color: white;
  box-shadow: 12px 15px 25px black;
}

h1 {
  font-size: 53px !important;
  text-align: center;
  margin-bottom: 3%;
}
.tableau {
  display: flex;
  justify-content: space-between;
  padding-left: 5%;
  padding-right: 5%;
  text-align: center;
}
.statut {
  font-size: 20px;
  margin-right: 10px;
}
.ajout {
  width: 110px;
}
.taches {
  background-color: white;
  border: 1px solid;
  padding: 1%;
  border-radius: 10px;
  margin-top: 8%;
  width: 400px;
  font-family: Arial Narrow Bold !important;
  box-shadow: 10px 12px 15px black;
}
.suppmod {
  display: flex;
  justify-content: space-between;
  /* width: 280px; */
  padding-left: 20%;
  padding-right: 20%;
}
.affichagetache {
  box-shadow: 10px 12px 15px black;
  width: 40% !important;
  margin-bottom: 25px !important;
}
.prenom {
  font-weight: bolder !important;
  color: black !important;
}
</style>
