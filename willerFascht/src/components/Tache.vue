<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
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
      soustaches: [],
      // mode: "nodisplay",
      tache: "",
      pasencoredetaches: true,
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

    for (let i = 0; i < this.soustaches.length; i++) {
      const soustache = this.soustaches[i];
      console.log(soustache.tache_id);
      console.log(this.$route.params.tache);
      if (soustache.tache_id == this.$route.params.tache) {
        console.log("pasencoredetaches");
        this.pasencoredetaches = false;
      }
    }
  },
  methods: {
    generateCheckboxId(soustache) {
      return `check1-${soustache.id}`;
    },
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    formatDate(dateString) {
      const date = dayjs(dateString);
      // Then specify how you want your dates to be formatted
      return date.format("dddd MMMM D, YYYY");
    },
    chargerPasencoredetache(){
      for (let i = 0; i < this.soustaches.length; i++) {
      const soustache = this.soustaches[i];
      console.log(soustache.tache_id);
      console.log(this.$route.params.tache);
      if (soustache.tache_id == this.$route.params.tache) {
        console.log("pasencoredetaches");
        this.pasencoredetaches = false;
      }
    }
    },

    chargersousTaches() {
      fetch("http://localhost:8000/api/soustaches")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.soustaches = data;
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
          this.chargersousTaches();
          this.chargerPasencoredetache();
        })
        .catch((error) => console.log("error", error));
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
        .then((result) => {
          console.log(result);
          this.chargersousTaches();
          this.chargerPasencoredetache();
          document.getElementsByClassName("pasencoredetaches")[0].style.display = "none";
          
          this.title = "";
        })
        .catch((error) => console.log("error", error));
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
        .then((result) => {
          console.log(result);
          this.chargersousTaches();
        })
        .catch((error) => console.log("error", error));
    },
    notvalidate: function (soustache) {
      console.log(soustache.id);

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
        .then((result) => {
          console.log(result);
          this.chargersousTaches();
        })
        .catch((error) => console.log("error", error));
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
        <button
          class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste"
        >
          Retour
        </button>
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
        <div v-if="pasencoredetaches == true" class="pasencoredetaches">Ajouter une tache 😜</div>
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
              <div class="checkbox-wrapper">
                <input
                  type="checkbox"
                  @click="notvalidate(soustache)"
                  class="check"
                  :id="generateCheckboxId(soustache)"
                  checked
                />
                <label :for="generateCheckboxId(soustache)" class="label">
                  <svg width="45" height="45" viewBox="0 0 95 95">
                    <rect
                      x="30"
                      y="20"
                      width="50"
                      height="50"
                      stroke="black"
                      fill="none"
                    ></rect>
                    <g transform="translate(0,-952.36222)">
                      <path
                        d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4"
                        stroke="black"
                        stroke-width="3"
                        fill="none"
                        class="path1"
                      ></path>
                    </g>
                  </svg>
                  <button class="validate">{{ soustache.title }}</button>
                </label>
              </div>

              <div class="deletesoustache" v-if="mode == 'display'">
                <button
                  @click="deletesoustache(soustache)"
                  style="border: none !important"
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
              <div class="checkbox-wrapper">
                <input
                  @click="validate(soustache)"
                  type="checkbox"
                  class="check"
                  :id="generateCheckboxId(soustache)"
                />
                <label :for="generateCheckboxId(soustache)" class="label">
                  <svg width="45" height="45" viewBox="0 0 95 95">
                    <rect
                      x="30"
                      y="20"
                      width="50"
                      height="50"
                      stroke="black"
                      fill="none"
                    ></rect>
                    <g transform="translate(0,-952.36222)">
                      <path
                        d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4"
                        stroke="black"
                        stroke-width="3"
                        fill="none"
                        class="path1"
                      ></path>
                    </g>
                  </svg>

                  <button @click="update" class="soustacheavalider">
                    {{ soustache.title }}
                  </button>
                </label>
              </div>

              <div v-if="mode == 'display'" class="deletesoustache">
                <button
                  style="border: none !important"
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
          <input
            type="text"
            v-model="title"
            class="inputsoustache"
            @keyup.enter="validersoustache()"
          />
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
<style scoped>
.checkbox-wrapper input[type="checkbox"] {
  visibility: hidden;
  display: none;
}

.checkbox-wrapper *,
.checkbox-wrapper ::after,
.checkbox-wrapper ::before {
  box-sizing: border-box;
  user-select: none;
}

.checkbox-wrapper {
  position: relative;
  display: block;
  overflow: hidden;
}

.checkbox-wrapper .label {
  cursor: pointer;
}

.checkbox-wrapper .check {
  width: 50px;
  height: 50px;
  position: absolute;
  opacity: 0;
}

.checkbox-wrapper .label svg {
  vertical-align: middle;
}

.checkbox-wrapper .path1 {
  stroke-dasharray: 400;
  stroke-dashoffset: 400;
  transition: 0.5s stroke-dashoffset;
  opacity: 0;
}

.checkbox-wrapper .check:checked + label svg g path {
  stroke-dashoffset: 0;
  opacity: 1;
}
.validate {
  text-decoration: line-through;
  font-weight: lighter;
  color: red !important;
  margin-left: 15px;
  font-size: 76%;
  border: none;
}
.tache {
  /* margin-top: 8% !important; */
  margin-left: 1%;
  /* background-color:  hsla(0, 0%, 84%, 0.3); */
}
.tachesdetail {
  border: 1px solid black;
  background-color: white;
  box-shadow: 10px 12px 15px black;
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
.label {
  
  display: flex !important;
}
.pasencoredetaches {
  text-align: center;
  font-size: 25px  !important;
  
}
</style>
