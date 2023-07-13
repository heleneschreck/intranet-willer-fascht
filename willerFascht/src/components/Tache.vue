<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
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
      soustaches: [],
      mode: "nodisplay",
      tache: "",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    let fetched_projects = await fetch("http://127.0.0.1:8000/api/project");
    this.projects = await fetched_projects.json();
    console.table(this.projects);

    let fetched_taches = await fetch("http://127.0.0.1:8000/api/taches");
    this.taches = await fetched_taches.json();
    console.table(this.taches);

    let fetched_soustaches = await fetch(
      "http://127.0.0.1:8000/api/soustaches"
    );
    this.soustaches = await fetched_soustaches.json();
    console.table(this.soustaches);
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

    survol: function (soustache) {
      this.mode = "display";
    },

    sortie: function () {
      this.mode = "nodisplay";
    },
    ajoutersoustache: function () {
      this.mode = "displayinputsoustache";
    },
    validersoustache: function () {
      this.mode = "displayupdatesoustache";
    },
    validersoustache: function () {
      console.log(this.title);
      var urlencoded = new URLSearchParams();
      urlencoded.append("title", this.title);
      urlencoded.append("isvalidate", "0");
      urlencoded.append("tache_id", this.$route.params.tache);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/soustaches";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },
    validate: function (soustache) {
      console.log(soustache.id);

      var urlencoded = new URLSearchParams();
      urlencoded.append("isvalidate", "1");
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/soustaches/" + soustache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },
    notvalidate: function (soustache) {
      // console.log(soustache.id);

      var urlencoded = new URLSearchParams();
      urlencoded.append("isvalidate", "0");
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/soustaches/" + soustache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },
    deletesoustache: function (soustache) {
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/soustaches/" + soustache.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },
  },
};
</script>
<template>
  <div v-for="tache in taches">
    <div v-if="tache.id == $route.params.tache">
      <router-link :to="`/projet/${tache.project_id}`">
        <button class="button rounded-lg retourtodo">Retour</button>
      </router-link>
    </div>
  </div>

  <div class="tachesdetail">
    <div v-for="tache in taches">
      <div v-if="tache.id == $route.params.tache">
        <h2>{{ tache.title }} :</h2>
        <hr />
        <div class="displaysoustache" @mouseover="sortie()">
          <img
            src="https://cdn-icons-png.flaticon.com/128/3360/3360765.png"
            class="montrer"
          />
          Check-List :
        </div>
        <div v-for="soustache in soustaches">
          <div
            v-if="soustache.tache_id == $route.params.tache"
            class="titlesoustache"
          >
            <div
              v-if="soustache.isvalidate == '1'"
              @mouseover="survol(soustache)"
              style="margin-top: 10px"
            >
              <input type="checkbox" @click="notvalidate(soustache)" checked />
              <button class="validate">{{ soustache.title }}</button>
              <div class="deletesoustache" v-if="mode == 'display'">
                <button
                  @click="deletesoustache(soustache)"
                  title="Bon à effacer?"
                >
                  <img
                    src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                    alt=""
                  />
                </button>
              </div>
            </div>
            <div
              v-if="soustache.isvalidate == '0'"
              @mouseover="survol(soustache)"
              style="margin-top: 10px"
            >
              <input type="checkbox" @click="validate(soustache)" />

              <button @click="update" class="soustacheavalider">
                {{ soustache.title }}
              </button>

              <div v-if="mode == 'display'" class="deletesoustache">
                <button
                  @click="deletesoustache(soustache)"
                  title="Bon à effacer?"
                >
                  <img
                    src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                    alt=""
                  />
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="validatesoustache" v-if="mode == 'displayinputsoustache'">
          <input type="checkbox" @click="validate()" />
          <input type="text" v-model="title" class="inputsoustache" />
          <button class="validersoustache" @click="validersoustache()">
            <img
              src="https://cdn-icons-png.flaticon.com/128/6669/6669498.png"
              class="illustrationvalidatesoustache"
              alt=""
            />
          </button>
        </div>
      </div>
      <div></div>
    </div>
    <button
      class="addsoustache"
      @mouseover="sortie()"
      @click="ajoutersoustache()"
    >
      <img
        src="https://cdn-icons-png.flaticon.com/128/6669/6669498.png"
        class="illustrationaddsoustache"
        alt=""
      />
      Ajouter un élèment pour compléter la tache
    </button>
  </div>
</template>
<style>
.validate {
  text-decoration: line-through;
  font-weight: lighter;
  color: red !important;
  margin-left: 15px;
  font-size: 76%;
  border: none;
}
.tache {
  margin-top: 8% !important;
  margin-left: 1%;
}
.tachesdetail {
  border: 1px solid black;
  border-radius: 10px;
  font-size: 20px;
  width: 60% !important;
  margin-left: 20%;
  padding-left: 10px;
  padding-right: 10px;
  padding: 5px;
}
.soustacheavalider {
  margin-left: 15px;
  border: none;
  font-size: 76%;
}

h2 {
  text-align: center;
  font-size: 30px !important;
  font-weight: bolder;
}
.displaysoustache {
  /* border: none; */
  display: flex;
  justify-content: space-between;
  width: 190px;
  margin-top: 17px;
}
.montrer {
  width: 35px;
  margin-left: 30px;
}
.titlesoustache {
  margin-left: 130px;
}
.deletesoustache {
  width: 30px;
  position: absolute;
  right: 40px;
  top: 10px;
}

.addsoustache {
  border: none;
}
.illustrationaddsoustache {
  width: 30px;
}
.addsoustache {
  display: flex;
}
.validatesoustache {
  margin-left: 130px !important ;
}
.inputsoustache {
  margin-left: 6px;
  border: none;
  background-color: rgba(132, 132, 132, 0.169) !important;
  border-radius: 10px !important ;
  height: 30px !important;
}
.validersoustache {
  align-content: center;
  border: none;
}
.illustrationvalidatesoustache {
  width: 30px;
  margin-bottom: -10px !important;
}
</style>
