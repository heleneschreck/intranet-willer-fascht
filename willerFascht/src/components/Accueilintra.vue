<script>
import { mapState } from "vuex";
import moment from "moment";

export default {
  data() {
    return {
      mode: false,
      user: [],
      profils: [],
      affiches: [],
      projects: [],
      statuts: [],
      evenements: [],

      taches: [],
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

    let fetched_profils = await fetch("http://127.0.0.1:8000/api/profils");
    this.profils = await fetched_profils.json();
    console.table(this.profils);

    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    let lListe = await fetched_evenements.json();
    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));
    this.evenements = lListe;
    console.table(this.evenements);

    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));

    let fetched_projects = await fetch("http://127.0.0.1:8000/api/project");
    this.projects = await fetched_projects.json();
    console.table(this.projects);

    let fetched_statuts = await fetch("http://127.0.0.1:8000/api/statut");
    this.statuts = await fetched_statuts.json();
    console.table(this.statuts);
    let fetched_taches = await fetch("http://127.0.0.1:8000/api/taches");
    this.taches = await fetched_taches.json();
    console.table(this.taches);

    let fetched_affiches = await fetch("http://localhost:8000/api/image");
    this.affiches = await fetched_affiches.json();
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },

    deleteimage: function (profil) {
      console.log(profil.id);

      console.log(profil.id);
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,
        redirect: "follow",
      };

      let url = "http://localhost:8000/api/profils/" + profil.id;
      fetch(url, requestOptions)
        .then((response) => response.json())
        .then((result) => {
          console.log(result);
          this.profils = this.profils.filter((p) => p.id !== profil.id);
        })
        .catch((error) => console.log("error", error));
      this.mode = "uploadphoto";
    },
    onFileSelected(event) {
      // Récupérer le fichier sélectionné par l'utilisateur
      const selectedFile = event.target.files[0];

      // Mettre à jour les données ou faire ce que vous voulez avec le fichier
      // par exemple, vous pouvez l'assigner à une variable du modèle
      this.fileData = selectedFile;

      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", selectedFile); // Utilisez directement "selectedFile" ici
      formdata.append("user_id", this.user.id);
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: formdata,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/profils", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
  },
};
</script>
<template>
  <aside
    class="ml-[-100%] fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen border-r bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]"
  >
    <div>
      <div class="-mx-6 px-6 py-4">
      
      </div>

      <div class="mt-8 text-center" v-for="profil in profils">
        <div v-if="profil.user_id == user.id">
          <img
            v-bind:src="profil.url"
            alt=""
            class="m-auto rounded-full object-cover lg:w-40 lg:h-40"
          />
          <div class="deletephotoprofil">
            <img
              src="https://cdn-icons-png.flaticon.com/128/9925/9925691.png"
              class="updateimage"
              @click="deleteimage(profil)"
              alt=""
            />
          </div>
          <h5 class="hidden mt-4 text-xl font-semibold text-gray-600 lg:block">
            {{ user.prenom }} {{ user.name }}
          </h5>
          <span class="hidden text-gray-400 lg:block"></span>
        </div>
      </div>

      <div class="uploadphoto profil" v-if="mode == 'uploadphoto'">
        <input type="file" @change="onFileSelected" name="image" id="" />
      </div>
      <ul class="space-y-2 tracking-wide mt-8">
        <li>
          <router-link :to="`mesinfos`">
            <a
              href="#"
              aria-label="dashboard"
              class="relative px-4 py-3 flex items-center space-x-4 rounded-xl text-white bg-gradient-to-r from-sky-600 to-cyan-400"
            >
              <svg class="-ml-1 h-6 w-6" viewBox="0 0 24 24" fill="none">
                <path
                  d="M6 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8ZM6 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-1Z"
                  class="fill-current text-cyan-400 dark:fill-slate-600"
                ></path>
                <path
                  d="M13 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2V8Z"
                  class="fill-current text-cyan-200 group-hover:text-cyan-300"
                ></path>
                <path
                  d="M13 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1Z"
                  class="fill-current group-hover:text-sky-300"
                ></path>
              </svg>

              <span class="-mr-1 font-medium">Mes infos</span>
            </a></router-link
          >
        </li>
        <li>
          <router-link :to="`todo`">
            <a
              href="#"
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
              <path
                class="fill-current text-gray-300 group-hover:text-cyan-300"
                fill-rule="evenodd"
                d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                clip-rule="evenodd"
              />
              <path
                class="fill-current text-gray-600 group-hover:text-cyan-600"
                d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z"
              />

              <span
                class="group-hover:text-gray-700 compte"
                style="margin-left: -15px"
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/128/3286/3286693.png"
                  style="width: 30px; height: 30px; margin-right: 18%"
                  alt="compte rendu"
                />

                Projets</span
              >
            </a>
          </router-link>
        </li>
        <router-link :to="`compterendu`">
          <li>
            <a
              href="#"
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
              <span class="group-hover:text-gray-700 compte">
                <img
                  src="https://cdn-icons-png.flaticon.com/128/7322/7322825.png"
                  style="width: 30px; height: 30px; margin-right: 18%"
                  alt="compte rendu"
                />
                Compte rendu des reunions
              </span>
            </a>
          </li>
        </router-link>
        <router-link :to="`affiches`">
          <li>
            <a
              href="#"
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
              <span class="group-hover:text-gray-700 compte">
                <img
                  src="https://cdn-icons-png.flaticon.com/128/902/902723.png"
                  style="width: 30px; height: 30px; margin-right: 18%"
                  alt=""
                />
                Supports publicitaires</span
                >
              </a>
            </li>
        </router-link>
        <li>
          <hr />
          <router-link :to="`partenaires`">
            <a
            href="#"
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
            <img
              src="https://cdn-icons-png.flaticon.com/128/5434/5434169.png"
              style="width: 30px; height: 30px; margin-right: 18%"
              alt=""
            />
            
            <span class="group-hover:text-gray-700">Partenaires</span>
            </a>
          </router-link>
          <router-link :to="`miseenligne`">
            <a
              href="#"
              class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
              >
              <img
                src="https://cdn-icons-png.flaticon.com/128/6329/6329568.png"
                style="width: 30px; height: 30px; margin-right: 18%"
                alt=""
              />
              
              <span class="group-hover:text-gray-700">Mise en ligne </span>
            </a>
          </router-link>
        </li>
        
        <router-link :to="`/equipe`">
          <a
            href="#"
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
            
            <img
              src="https://cdn-icons-png.flaticon.com/128/3718/3718057.png"
              style="width: 30px; height: 30px; margin-right: 18%"
              alt=""
            />
            <span class="group-hover:text-gray-700">L'équipe</span>
          </a>
        </router-link>
        <router-link :to="`/`">
          <a
            href="#"
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
            >
            
            <img
              src="https://cdn-icons-png.flaticon.com/128/3518/3518229.png"
              style="width: 30px; height: 30px; margin-right: 18%"
              alt=""
            />
            <span class="group-hover:text-gray-700">Site</span>
          </a>
        </router-link>
        <!-- <li>
                <a href="#" class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path class="fill-current text-gray-300 group-hover:text-cyan-300" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path class="fill-current text-gray-600 group-hover:text-cyan-600" fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                    </svg>
                    <span class="group-hover:text-gray-700">Finance</span>
                </a>
            </li> -->
      </ul>
    </div>
  </aside>
  <div class="ml-auto mb-6 lg:w-[75%] xl:w-[80%] 2xl:w-[85%]">
    <div class="sticky z-10 top-0 h-16 border-b bg-white lg:py-2.5">
      <div
        class="px-6 flex items-center justify-between space-x-1 2xl:container"
      >
        <h5 hidden class="text-3xl text-gray-600 font-medium lg:block">
          Les actualités de l'association
        </h5>
        <button class="w-12 h-16 -mr-2 border-r lg:hidden">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 my-auto"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>
        <div class="flex space-x-4">
          <!--search bar -->
          <div hidden class="md:block"></div>
          <!--/search bar -->
          <button
            aria-label="search"
            class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200 md:hidden"
          >
            <svg
              xmlns="http://ww50w3.org/2000/svg"
              class="w-4 mx-auto fill-current text-gray-600"
              viewBox="0 0 35.997 36.004"
            >
              <path
                id="Icon_awesome-search"
                data-name="search"
                d="M35.508,31.127l-7.01-7.01a1.686,1.686,0,0,0-1.2-.492H26.156a14.618,14.618,0,1,0-2.531,2.531V27.3a1.686,1.686,0,0,0,.492,1.2l7.01,7.01a1.681,1.681,0,0,0,2.384,0l1.99-1.99a1.7,1.7,0,0,0,.007-2.391Zm-20.883-7.5a9,9,0,1,1,9-9A8.995,8.995,0,0,1,14.625,23.625Z"
              ></path>
            </svg>
          </button>
          <button
            aria-label="chat"
            class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 m-auto text-gray-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
              />
            </svg>
          </button>
          <button
            aria-label="notification"
            class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 m-auto text-gray-600"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="px-6 pt-6 3xl:container">
      <div class="onglets">
        
          <div
            class="h-full py-8 px-6 space-y-6 rounded-xl border border-gray-200 bg-white onglet"
          >
            <div>
              <h5 class="text-xl text-gray-600 text-center rubrique">
                Projets en cours :
              </h5>
              <div v-for="project in projects">
                <div class="titreprojectsencours">
                  {{ project.title }} -
                  {{ moment(project.end).format("DD/MM/YYYY") }} :
                </div>
                <div v-for="tache in taches">
                  <div
                    v-if ="
                      tache.status_id == '2' 
                      && tache.project_id == project.id
                    "
                  >
                    <div v-if="tache.user_id == user.id">
                     
                        <div class="tacheafaire">
                          {{ tache.title }}
                        </div>
                      
                    </div>
                  </div>
              <div>
              </div>
         
            </div>
              </div>
             
            </div>
          </div>
       
        <div class="onglet">
          <div
            class="h-full py-6 px-6 rounded-xl border border-gray-200 bg-white "
          >
            <h5 class="text-xl text-gray-700 rubrique">
              Prochains rendez-vous :
            </h5>
            <div class="EvenementTitleAccueilIntra">

              <div v-for="evenement in evenements">
             <li>{{ evenement.title }} - Du {{ moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")}} au {{ moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")  }} </li>
              </div>
            
            </div>
          </div>
        </div>
        <div class="onglet">
          <div
            class="h-full py-6 px-6 rounded-xl border border-gray-200 bg-white onglets"
          >
            <h5 class="text-xl text-gray-700 rubrique">
           Nouveaux membres de l'association  :
            </h5>
          </div>
        </div>
        <div></div>
      </div>
      <div
        class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white listderniereaffiches"
      >
        <h5 class="text-xl text-gray-700 rubrique">Dernières affiches :</h5>
        <div class="dernieresAffiche">
          <div v-for="affiche in affiches">
            <div class="derniereAffiche">
              <div class="titleDerniereaffiches">
                {{ affiche.title }} :
              </div>
              <div class="imageDerniereAffiche">
                <img
                  v-bind:src="affiche.url"
                  style="
                    width: 400px;
                    height: 460px;
                    margin-left: auto;
                    margin-right: auto;
                    margin-bottom: 15px;
                  "
                />
              </div>
              <div class="creea">
                {{ moment(affiche.created_at).format("DD/MM/YYYY") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.accueilintra {
  /* margin-top: 4% !important; */
  background-color: hsla(0, 0%, 84%, 0.3);
}
.compte {
  display: flex;
}
.deletephotoprofil {
  background-color: #ccd0d5;
  padding: 3px;
  width: 40px;
  border-radius: 19px;
  margin-left: 176px;
  margin-top: -19px;
}
.deletephotoprofil:hover {
  cursor: pointer;
}
.updateimage {
  width: 25px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.rubrique {
  font-size: 30px !important;
  margin-bottom: 20px;
}
.uploadphoto {
  background: white;
}
.titreprojectsencours {
  font-weight: bold;
  text-align: center;
  margin-bottom: 15px;
  font-size: 20px;
}
.dernieresAffiche {
  display: flex !important;
  flex-direction: row !important;
  justify-content: flex-start;
}
.derniereAffiche {
  margin-right: 3%;
}
.listderniereaffiches {
  /* width: 123% !important; */
}
.titleDerniereaffiches{
  text-align: center;
  margin-bottom: 10px;
}
.onglets {
 
  display: flex;
  margin-bottom: 7px;
}
.onglet {
    width: 40%;
    margin-right: 7px;
}


</style>
