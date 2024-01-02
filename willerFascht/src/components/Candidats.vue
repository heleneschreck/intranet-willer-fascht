<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      mode: false,
      user: [],
      candidats: [],
      candidatsSee: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    if (localStorage.getItem("user") != null) {
      this.mode = true;
    }

    let fetched_candidats = await fetch("http://localhost:8000/api/candidats");
    this.candidats = await fetched_candidats.json();
    console.table(this.candidats);

    let fetched_candidatsSee = await fetch(
      "http://localhost:8000/api/candidats/see/0"
    );
    this.candidatsSee = await fetched_candidatsSee.json();
    console.table(this.candidatsSee);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    delete_inscription: function (candidat) {
      console.log(candidat);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/candidats/" + candidat, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerCandidats(); // Appeler une fonction pour recharger les candidats
        })
        .catch((error) => console.log("error", error));
    },

    marquenonlu: function (candidat) {
      // console.log(candidat);
      var urlencoded = new URLSearchParams();
      urlencoded.append("vu", "0")
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/candidats/" + candidat, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerCandidats(); // Appeler une fonction pour recharger les candidats
        })
        .catch((error) => console.log("error", error));
    },
    elementVue: function (candidatsSee) {
      if (candidatsSee.count >= 1) {
        console.log("il y a des elements non lu");
        var myHeaders = new Headers();
        myHeaders.append(
          "Cookie",
          "XSRF-TOKEN=eyJpdiI6IjY3bkloOE41b2trWDhOc0FwbytJYnc9PSIsInZhbHVlIjoiTGk1Tys0V1QvUUl3NTV1NnlYbzh4VkJVWkNWYUp2aXFQNlFSM3FwbitWK3pxMjlaeHZ0UTN1b21iRUdNaEZXK09LUVZPUStzN05sdmhiaVRmODBRQ0ZqVnhOQWtOMVpsd0xHalNjOFlKMG1IeW1EN1JMVmZlcnloMlE3bWZrWTgiLCJtYWMiOiJjNmM2ZTc5Y2ExY2ZlOGNiMWRhY2I4YTlmNjU2NThkMmUxMWMwMjY1NTJmZjI2NjQwYTEwODBiZjI2YzY2MTUzIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6ImlRMi9oYU1OOFFsZnNyTGoyVDFEb3c9PSIsInZhbHVlIjoiaXhtNTFiUWlSQ0taRnpIVjd5L2VleUVCUkpOL3VaVFNwVGtrejh0ZTBOOGpLRU11YTNmWnI2b3J6bUpQbWJTY2QxWnk0dFhQamQ5Y1VLVUlJcngxTWI5MDlYQjBPWEVjcEFteWJ2OVhoZ1JOSmRqekVBNThDa3pLUFhPWGdvaCsiLCJtYWMiOiI0MjhmMzc1NjBlMGZhZTJkMDAyODNiOGJkNDgxMDBlMmM0OTZjODY5ZTcyMDc5Yzk1MjRiZmI0YjczYjJhY2E4IiwidGFnIjoiIn0%3D"
        );
        var requestOptions = {
          method: "PUT",
          headers: myHeaders,
          redirect: "follow",
        };
        fetch(
          "http://localhost:8000/api/candidats/update-by-vu/0?new_vu=1",
          requestOptions
        )
          .then((response) => response.text())
          .then((result) => {
            console.log(result);
            this.chargerCandidats(); // Appeler une fonction pour recharger les candidats
          })
          .catch((error) => console.log("error", error));
      } else {
        console.log("Tous les élèments sont lu");
      }
    },
    chargerCandidats() {
      fetch("http://localhost:8000/api/candidats")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.candidats = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourintra">Retour</button>
  </router-link>
  <h1>Candidats</h1>
  <div class="listecandidats">
    <div
      v-for="candidat in candidats"
      style="margin-bottom: 10%; margin-left: 3%;"
    >
      <!-- {{ this.candidatsSee }} -->
      <div v-if="candidat.vu == 0">
        <div class="flex items-center h-screen w-full justify-center carduser">
          <div class="max-w-xs">
            <div class="bg-white shadow-xl rounded-lg py-3">
              <div class="photo-wrapper p-2" style="overflow: scroll ">
                <!-- <div v-if="profil.user_id == user.id"> -->

                <img
                  @mouseover="elementVue(this.candidatsSee)"
                  src="https://images.pexels.com/photos/927505/pexels-photo-927505.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                  alt=""
                  class="w-40 h-40 rounded-full mx-auto"
                />
                <!-- </div> -->
                <div class="p-2">
                  <h3
                    class="text-center text-xl text-gray-900 font-medium leading-8"
                    style="font-weight: 900 !important"
                  >
                    {{ candidat.nom }} {{ candidat.prenom }}
                  </h3>
                  <!-- <div class="text-center text-gray-400 text-xs font-semibold">
                    <p>Web Developer</p>
                </div> -->
                  <table class="text-xs my-3">
                    <tbody>
                      <tr>
                        <!-- <td class="px-2 py-2 text-gray-500 font-semibold">Mot de passe</td>
                                <td class="px-2 py-2">{{user.password}}</td> -->
                      </tr>
                      <tr>
                        <td class="px-2 py-2 text-gray-500 font-semibold">
                          Télèphone
                        </td>
                        <td class="px-2 py-2">{{ candidat.telephone }}</td>
                      </tr>
                      <tr>
                        <td class="px-2 py-2 text-gray-500 font-semibold">
                          Email
                        </td>
                        <td class="px-2 py-2">{{ candidat.email }}</td>
                      </tr>
                    </tbody>
                    <button
                      @click="delete_inscription(candidat.id)"
                      style="margin-top: 15px !important"
                      class="addpartenaire"
                    >
                      Candidature examinée
                    </button>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="flex items-center h-screen w-full justify-center carduser">
          <div class="max-w-xs">
            <div class="bg-white shadow-xl rounded-lg py-3">
              <div class="photo-wrapper p-2" style="overflow: scroll ">
                <!-- <div v-if="profil.user_id == user.id"> -->
                <img
                  src="https://cdn-icons-png.flaticon.com/128/8303/8303007.png"
                  style="width: 25px"
                  class="menunonlu"
                  title="marqué non lu"
                  @click="marquenonlu(candidat.id)"
                />
                <img
                  @mouseover="elementVue(this.candidatsSee)"
                  src="https://images.pexels.com/photos/927505/pexels-photo-927505.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                  alt=""
                  class="w-40 h-40 rounded-full mx-auto"
                />
                <!-- </div> -->
                <div class="p-2">
                  <h3
                    class="text-center text-xl text-gray-900 font-medium leading-8"
                  >
                    {{ candidat.nom }} {{ candidat.prenom }}
                  </h3>
                  <!-- <div class="text-center text-gray-400 text-xs font-semibold">
            <p>Web Developer</p>
        </div> -->
                  <table class="text-xs my-3">
                    <tbody>
                      <tr>
                        <!-- <td class="px-2 py-2 text-gray-500 font-semibold">Mot de passe</td>
                        <td class="px-2 py-2">{{user.password}}</td> -->
                      </tr>
                      <tr>
                        <td class="px-2 py-2 text-gray-500 font-semibold">
                          Télèphone
                        </td>
                        <td class="px-2 py-2">{{ candidat.telephone }}</td>
                      </tr>
                      <tr>
                        <td class="px-2 py-2 text-gray-500 font-semibold">
                          Email
                        </td>
                        <td class="px-2 py-2">{{ candidat.email }}</td>
                      </tr>
                    </tbody>
                    <button
                      @click="delete_inscription(candidat.id)"
                      style="margin-top: 15px !important"
                      class="addpartenaire"
                    >
                      Candidature examinée
                    </button>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.listecandidats {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 55px !important;
  margin-left: 8%;
}
.menunonlu:hover {
  cursor: pointer;
}
</style>
