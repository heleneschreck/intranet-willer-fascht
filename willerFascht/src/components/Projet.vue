<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import moment from "moment";
import { VueDraggableNext } from "vue-draggable-next";
const route = useRoute();
import { Draggable } from "@fullcalendar/interaction";
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

    // Vue.filter("formatDate", function (taches) {
    //   const dateObj = new Date(taches.end);
    //   const formattedDate =`${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;
    //   return formattedDate;
    // });
    // console.log(this.user.id);
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
    deleteTache: function (tache) {
      console.log(tache.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/taches/" + tache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
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
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
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
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },

    display: function () {
      this.mode = "display";
    },
    nodisplay: function () {
      this.mode = "nodisplay";
    },
  },
};
</script>
<template>
  <router-link :to="`/todo`">
    <button class="button rounded-lg retourtodo">Retour</button>
  </router-link>

  <div v-for="project in projects" v-show="$route.params.projet == project.id">
    <h1>{{ project.title }}</h1>

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
          >
            <router-link :to="`/tache/${tache.id}`">
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
              {{ membre.prenom }}
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
            <div class="incremente">
              <button @click="updateStatut1(tache)" title="Changer le statut">
                <img
                  class="boutonincremente"
                  src="https://cdn-icons-png.flaticon.com/128/2252/2252536.png"
                  style="width: 30px"
                  alt=""
                />
              </button>
            </div>
            <hr />
            <div
              class="prenom"
              v-for="membre in membres"
              v-show="membre.id == tache.user_id"
            >
              {{ membre.prenom }}
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
              {{ membre.prenom }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br />
  <div v-show="user.niveau == 1">
    <div class="display" v-if="mode == 'nodisplay'">
      <button @click="display()" class="button rounded-lg button-disabled">
        Afficher l'avancé des taches assigné aux autres membres
      </button>
    </div>
    <div class="arriere" v-if="mode == 'display'">
      <button @click="nodisplay()" class="button rounded-lg button-disabled">
        Revenir sur mes taches
      </button>
    </div>
  </div>
</template>
<style>
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
  margin-top: 6% !important;
  margin-left: 1%;
}
.retourtodo {
  margin-bottom: 1px !important;
  margin-left: 5%;
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
}
.ajout {
  width: 110px;
}
.taches {
  border: 1px solid;
  padding: 1%;
  border-radius: 10px;
  margin-top: 8%;
  width: 400px;
  font-family: Arial Narrow Bold !important;
}
.suppmod {
  display: flex;
  justify-content: space-between;
  /* width: 280px; */
  padding-left: 20%;
  padding-right: 20%;
}
.prenom {
  font-weight: bolder !important;
  color: black !important;
}
</style>
