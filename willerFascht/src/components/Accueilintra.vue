<script>
import { mapState } from "vuex";
import moment from "moment";

export default {
  data() {
    return {
      mode: false,
      showAside: false,
      mode: "vue3",
      user: [],
      profils: [],
      affiches: [],
      projects: [],
      statuts: [],
      evenements: [],
      candidats: [],
      taches: [],
      newMessages: [],
      listeNewMessagesLu: [],
      messages: [],
      messagesConversation: [],
      membres: [],
      OpenConversation: false,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.moment = moment;
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

    let fetched_profils = await fetch("http://127.0.0.1:8000/api/profils");
    this.profils = await fetched_profils.json();
    // console.table(this.profils);

    // messages

    let fetched_NewMessages = await fetch(
      "http://localhost:8000/api/lus/count/" + this.user.id
    );

    this.newMessages = await fetched_NewMessages.json();
    console.log(this.newMessages);

    let fetched_listeNewMessages = await fetch("http://localhost:8000/api/lu");
    this.listeNewMessagesLu = await fetched_listeNewMessages.json();
    console.log(this.listeNewMessagesLu);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();

    let fetched_messages = await fetch("http://localhost:8000/api/messages");
    this.messages = await fetched_messages.json();
    console.log(this.messages);

    // planning
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    let lListe = await fetched_evenements.json();
    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));
    this.evenements = lListe;
    // console.table(this.evenements);

    lListe = lListe.sort((a, b) => b.start.localeCompare(a.start));

    let fetched_projects = await fetch("http://127.0.0.1:8000/api/project");
    this.projects = await fetched_projects.json();
    // console.table(this.projects);

    let fetched_candidats = await fetch(
      "http://localhost:8000/api/candidats/see/0"
    );
    this.candidats = await fetched_candidats.json();
    console.table(this.candidats);
    let fetched_statuts = await fetch("http://127.0.0.1:8000/api/statut");
    this.statuts = await fetched_statuts.json();
    // console.table(this.statuts);
    let fetched_taches = await fetch("http://127.0.0.1:8000/api/taches");
    this.taches = await fetched_taches.json();
    // console.table(this.taches);

    let fetched_affiches = await fetch("http://localhost:8000/api/image");
    let affiches = await fetched_affiches.json();
    affiches = affiches.sort((a, b) =>
      b.created_at.localeCompare(a.created_at)
    );
    this.affiches = affiches.slice(0, 4);
  },
  methods: {
    OpenConversations: async function (message) {
      console.log(message);
      this.OpenConversation = true;
      let fetched_messages = await fetch(
        "http://localhost:8000/api/messages/conversation/" +
          message.conversation_id
      );
      let messages = await fetched_messages.json();
      messages = messages.sort((a, b) =>
        b.created_at.localeCompare(a.created_at)
      );
      this.messagesConversation = messages.reverse();
      console.log(this.messagesConversation);
    },
    sortir: function () {
      this.OpenConversation = false;
    },
    chargerCountMessages() {
      // console.log(this.conversation_id.id);
      fetch("http://localhost:8000/api/lus/count/" + this.user.id)
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.newMessages = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    chargerAffichage() {
      // console.log(this.conversation_id.id);
      fetch("http://localhost:8000/api/lu")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.listeNewMessagesLu = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    chargerDisplayMessages(lu) {

      fetch(
        "http://localhost:8000/api/messages/conversation/" + this.messagesConversation[0].conversation_id
      )
        .then((response) => response.json())
        .then((data) => {
          this.messagesConversation = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    elementVue: function (lu) {
      console.log(lu.id);
      var urlencoded = new URLSearchParams();
      urlencoded.append("Lu", "1");
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/lu/" + lu.id, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerDisplayMessages(this.messagesConversation[0].conversation_id);
          this.chargerCountMessages();
          this.chargerAffichage();
        })
        .catch((error) => console.log("error", error));
    },

    async fetchedcandidats() {},
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
    toogleAside: function () {
      console.log(document.querySelector("aside"));
      this.showAside = !this.showAside;
    },
    ajoutMessage: async function () {
      console.log(this.messagesConversation);
      console.log(this.messagesConversation[0].conversation_id);
      let fetched_participantsConversation = await fetch(
        "http://localhost:8000/api/conversation_users/participants/" +
          this.messagesConversation[0].conversation_id
      );
      this.participantsConversation =
        await fetched_participantsConversation.json();
      console.log(this.participantsConversation);

      const participantsArray = Object.values(this.participantsConversation);

      const membresNonLu = participantsArray
        .filter((participantId) => participantId !== `${this.user.id}`)
        .map((participantId) => participantId);

      console.log(membresNonLu);

      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("conversation_id", this.messagesConversation[0].conversation_id);
      urlencoded.append("message", this.content);
      urlencoded.append("Lu", "0");

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };

      const url = "http://localhost:8000/api/messages";

      try {
        const response = await fetch(url, requestOptions);
        const result = await response.json();
        if (result.id !== undefined) {
          this.result = result;

          for (const participantId of membresNonLu) {
            var participantParams = new URLSearchParams();
            participantParams.append("participants_id", participantId);
            participantParams.append("message_id", this.result.id);
            participantParams.append(
              "conversation_id",
              this.messagesConversation[0].conversation_id
            );
            participantParams.append("Lu", "0");

            var participantRequestOptions = {
              method: "POST",
              body: participantParams,
              redirect: "follow",
            };

            let participantUrl = "http://localhost:8000/api/lu";
            const participantResponse = await fetch(
              participantUrl,
              participantRequestOptions
            );
            const participantResult = await participantResponse.json();
            console.log(participantResult);
          }

       
          this.content = "";
        } else {
          console.log("Response does not contain an ID.");
        }
      } catch (error) {
        console.log("error", error);
      }
      var myHeaders = new Headers();
      myHeaders.append(
        "Cookie",
        "XSRF-TOKEN=eyJpdiI6IjY3bkloOE41b2trWDhOc0FwbytJYnc9PSIsInZhbHVlIjoiTGk1Tys0V1QvUUl3NTV1NnlYbzh4VkJVWkNWYUp2aXFQNlFSM3FwbitWK3pxMjlaeHZ0UTN1b21iRUdNaEZXK09LUVZPUStzN05sdmhiaVRmODBRQ0ZqVnhOQWtOMVpsd0xHalNjOFlKMG1IeW1EN1JMVmZlcnloMlE3bWZrWTgiLCJtYWMiOiJjNmM2ZTc5Y2ExY2ZlOGNiMWRhY2I4YTlmNjU2NThkMmUxMWMwMjY1NTJmZjI2NjQwYTEwODBiZjI2YzY2MTUzIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6ImlRMi9oYU1OOFFsZnNyTGoyVDFEb3c9PSIsInZhbHVlIjoiaXhtNTFiUWlSQ0taRnpIVjd5L2VleUVCUkpOL3VaVFNwVGtrejh0ZTBOOGpLRU11YTNmWnI2b3J6bUpQbWJTY2QxWnk0dFhQamQ5Y1VLVUlJcngxTWI5MDlYQjBPWEVjcEFteWJ2OVhoZ1JOSmRqekVBNThDa3pLUFhPWGdvaCsiLCJtYWMiOiI0MjhmMzc1NjBlMGZhZTJkMDAyODNiOGJkNDgxMDBlMmM0OTZjODY5ZTcyMDc5Yzk1MjRiZmI0YjczYjJhY2E4IiwidGFnIjoiIn0%3D"
      );
      var urlencoded = new URLSearchParams();
      urlencoded.append("Lu", "1");
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      fetch(
        "http://localhost:8000/api/lus/update/" +
          this.user.id +
          "/" +
          this.messagesConversation[0].conversation_id +
          "/0",
        requestOptions
      )
      .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerDisplayMessages(this.messagesConversation[0].conversation_id);
          this.chargerCountMessages();
          this.chargerAffichage();
        })
        .catch((error) => console.log("error", error));
    },
  },
};
</script>
<template>
  <aside
    class="fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen border-r bg-white lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]"
    style="overflow: scroll"
    v-bind:class="{ notVisibility: !showAside, asideResponsive: showAside }"
  >
    <!-- <img src="https://www.flaticon.com/fr/icone-gratuite/fleche-vers-le-bas-vers-la-gauche_20856" class="fermerAside" alt=""> -->
    <div>
      <div class="-mx-6 px-6 py-4"></div>

      <div class="mt-8 text-center" v-for="profil in profils">
        <div v-if="profil.user_id == user.id">
          <img
            v-bind:src="profil.url"
            alt=""
            class="m-auto rounded-full object-cover w-40 h-40 z-1"
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

        <router-link :to="`/candidats`" v-if="user.niveau == 1">
          <a
            href="#"
            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/6373/6373828.png"
              style="width: 30px; height: 30px; margin-right: 18%"
              alt=""
            />
            <span class="group-hover:text-gray-700"
              >Examiner les candidats</span
            >
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
  <div
    class="container mx-auto mt-12 conversationSelectionne"
    style="left: 40%; top: 150px; position: absolute; z-index: 1 !important"
    v-if="OpenConversation == true"
  >
    <img
      src="https://cdn-icons-png.flaticon.com/128/45/45320.png"
      class="croixSortir"
      style="width: 15px; position: absolute; left: 30%; top: 10px; z-index: 1"
      @click="sortir()"
      alt=""
    />

    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
      <div
        class="w-full px-4 py-5 bg-white rounded-lg shadow"
        style="background-color: hsla(0, 0%, 84%, 0.3)"
      >
        <div class="messs">
          <div v-for="message in messagesConversation">
            <div v-if="user.id == message.user_id">
              <div class="mess">
                <div
                  v-for="membre in membres"
                  style="color: gray; margin-bottom: 5px"
                >
                  <div v-if="membre.id == message.user_id">
                    {{ membre.prenom }}
                  </div>
                </div>
                {{ message.message }}

                <div class="datemessage">
                  {{ moment(message.created_at).format("DD/MM/YYYY") }}
                </div>
              </div>
            </div>
            <div v-else>
              <div class="mess" style="margin-left: 27%">
                <div
                  v-for="membre in membres"
                  style="color: gray; margin-bottom: 5px"
                >
                  <div v-if="membre.id == message.user_id">
                    {{ membre.prenom }}
                  </div>
                </div>
                <div v-for="lu in listeNewMessagesLu">
                  <div v-if="lu.message_id == message.id">
                    <div
                      v-if="lu.Lu == 0"
                      style="font-weight: bold"
                      @mouseover="elementVue(lu)"
                    >
                      {{ message.message }}
                    </div>
                    <div v-else>
                      {{ message.message }}
                    </div>
                  </div>
                </div>

                <div class="datemessage">
                  {{ moment(message.created_at).format("DD/MM/YYYY") }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="ajoutermessage">
          <textarea
            v-model="content"
            class="inputmessages"
            style="margin-right: 10px !important"
          />
          <div
            class="imageajoutmessage"
            @keydown.enter.prevent="ajoutMessage()"
            @click="ajoutMessage()"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/2161/2161491.png"
              class="addmessage"
              alt=""
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  <div
    class="ml-auto mb-6 lg:w-[75%] xl:w-[80%] 2xl:w-[85%]"
    :class="{ blurred: OpenConversation }"
  >
    <div
      class="sticky z-10 top-0 h-16 border-b bg-white lg:py-2.5"
      v-bind:class="{ barreasideResponsive: showAside }"
    >
      <div
        class="px-6 flex items-center justify-between space-x-1 2xl:container"
      >
        <h5 hidden class="text-3xl text-gray-600 font-medium lg:block">
          Les actualités de l'association
        </h5>
        <button
          class="w-12 h-16 -mr-2 border-r lg:hidden"
          @click="toogleAside()"
        >
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
          <!-- Candidats -->
          <router-link :to="`candidats`">
            <button
              aria-label="chat"
              class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200"
              v-if="user.niveau == 1 && candidats.count >= 1"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/128/10754/10754151.png"
                alt=""
              />
              <span class="nouveauxcandidats"> {{ candidats.count }} </span>
            </button>
          </router-link>
          <router-link :to="`messagerie`">
            <button
              aria-label="chat"
              class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200"
              style="padding: 3px !important"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/128/2594/2594044.png"
                alt=""
              />
              <div v-if="newMessages.count > 0">
                <span class="nouveauxmessagesaccueil">
                  {{ newMessages.count }}
                </span>
              </div>
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
              />
            </button>
          </router-link>
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
              <router-link :to="`todo`"> Projets en cours : </router-link>
            </h5>
            <div v-for="project in projects">
              <router-link :to="`projet/${project.id}`">
                <div class="titreprojectsencours">
                  {{ project.title }} -
                  {{ moment(project.end).format("DD/MM/YYYY") }} :
                </div>
              </router-link>
              <div v-for="tache in taches">
                <div
                  v-if="
                    tache.status_id == '2' && tache.project_id == project.id
                  "
                >
                  <div v-if="tache.user_id == user.id">
                    <div class="tacheafaire">
                      {{ tache.title }}
                    </div>
                  </div>
                </div>
                <div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="onglet">
          <div
            class="h-full py-8 px-6 rounded-xl border border-gray-200 bg-white"
          >
            <router-link :to="`liste`">
              <h5 class="text-xl text-gray-700 rubrique">
                Prochains rendez-vous:
              </h5>
            </router-link>
            <div class="EvenementTitleAccueilIntra">
              <div v-for="evenement in evenements">
                <!-- <div > -->
                <li v-if="moment(evenement.end) > moment()">
                  <router-link :to="`/rendezvous/${evenement.id}`">
                    {{ evenement.title }} - Du
                    {{
                      moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")
                    }}
                    au
                    {{
                      moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")
                    }}
                  </router-link>
                </li>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>
        <div class="onglet">
          <div
            class="h-full py-6 px-6 rounded-xl border border-gray-200 bg-white onglets"
          >
            <div>
              <div class="titreRubrique">
                <h5 class="text-xl text-gray-700 rubrique">
                  Nouveaux messages :
                </h5>
              </div>
              <div class="pasdeMessages" v-if="newMessages.count == 0">
                Tu n'as pas de nouveaux messages 😜
              </div>
              <div class="listesdesnouveauxmessages" v-else>
                <div v-for="MessagesNonLu in listeNewMessagesLu">
                  <!-- {{ MessagesNonLu.Lu }} -->
                  <!--  {{ this.user.id }} -->
                  <div
                    v-if="
                      MessagesNonLu.participants_id == this.user.id &&
                      MessagesNonLu.Lu == '0'
                    "
                  >
                    <div v-for="message in messages">
                      <div v-if="message.id == MessagesNonLu.message_id">
                        <div v-for="membre in membres">
                          <div v-if="membre.id == message.user_id">
                            <li>
                              <!-- <router-link
                                :to="`messagerieConversation`" -->
                              <span
                                class="lienMessagerie"
                                @click="OpenConversations(message)"
                              >
                                {{ membre.prenom }}
                              </span>
                              <!-- </router-link> -->
                              t'a envoyé un message
                            </li>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div></div>
      </div>
      <div
        class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white listderniereaffiches"
      >
        <router-link :to="`affiches`">
          <h5 class="text-xl text-gray-700 rubrique">Dernières affiches :</h5>
        </router-link>
        <div class="dernieresAffiche">
          <div v-for="affiche in affiches">
            <div class="derniereAffiche">
              <div class="titleDerniereaffiches">{{ affiche.title }} :</div>
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
      <div
        class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white listderniereaffiches"
      >
        <h5 class="text-xl text-gray-700 rubrique">
          Nouveaux membres de l'association :
        </h5>

        <div class="dernieresAffiche">
          <div class="derniereAffiche">
            <div class="titleDerniereaffiches">:</div>
            <div class="imageDerniereAffiche">
              <img
                src="https://cdn-icons-png.flaticon.com/128/6373/6373828.png"
                style="
                  width: 400px;
                  height: 460px;
                  margin-left: auto;
                  margin-right: auto;
                  margin-bottom: 15px;
                "
              />
            </div>
            <div class="creea"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mode responsive -->
    <div class="OngletsResponsives">
      <div class="ongletResponsive">
        <div
          class="h-full py-6 px-6 space-y-6 rounded-xl border border-gray-200 bg-white"
        >
          <div>
            <h5 class="text-xl text-gray-600 text-center rubrique">
              <router-link :to="`todo`"> Projets en cours : </router-link>
            </h5>
            <div v-for="project in projects">
              <router-link :to="`projet/${project.id}`">
                <div class="titreprojectsencours">
                  {{ project.title }} -
                  {{ moment(project.end).format("DD/MM/YYYY") }} :
                </div>
              </router-link>
              <div v-for="tache in taches">
                <div
                  v-if="
                    tache.status_id == '2' && tache.project_id == project.id
                  "
                >
                  <div v-if="tache.user_id == user.id">
                    <div class="tacheafaire">
                      {{ tache.title }}
                    </div>
                  </div>
                </div>
                <div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="ongletResponsive">
          <div
            class="h-full py-6 px-6 space-y-6 rounded-xl border border-gray-200 bg-white"
            style="width: 110.5%; margin-left: -5%"
          >
            <router-link :to="`liste`">
              <h5 class="text-xl text-gray-700 rubrique">
                Prochains rendez-vous:
              </h5>
            </router-link>
            <div class="EvenementTitleAccueilIntra">
              <div v-for="evenement in evenements">
                <!-- <div > -->
                <li v-if="moment(evenement.end) > moment()">
                  <router-link :to="`/rendezvous/${evenement.id}`">
                    {{ evenement.title }} - Du
                    {{
                      moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")
                    }}
                    au
                    {{
                      moment(evenement.start).format("DD/MM/YYYY [à] HH[h]mm")
                    }}
                  </router-link>
                </li>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>
        <div class="ongletResponsive">
          <div
            class="h-full py-6 px-6 rounded-xl border border-gray-200 bg-white"
            style="width: 110.5%; margin-left: -5%"
          >
            <div>
              <div class="titreRubrique">
                <h5 class="text-xl text-gray-700 rubrique">
                  Nouveaux messages :
                </h5>
              </div>
              <div class="pasdeMessages" v-if="newMessages.count == 0">
                Tu n'as pas de messages 😜
              </div>
              <div class="listesdesnouveauxmessages" v-else>
                <div v-for="MessagesNonLu in listeNewMessagesLu">
                  <div
                    v-if="
                      MessagesNonLu.participants_id == this.user.id &&
                      MessagesNonLu.Lu == '0'
                    "
                  >
                    <div v-for="message in messages">
                      <div v-if="message.id == MessagesNonLu.message_id">
                        <div v-for="membre in membres">
                          <div v-if="membre.id == message.user_id">
                            <li>
                              <router-link
                                :to="`messagerieConversation`"
                                class="lienMessagerie"
                                >{{ membre.prenom }}
                              </router-link>
                              t'a envoyé un message
                            </li>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div></div>
      </div>
      <div
        class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white listderniereaffichesResponsive"
      >
        <router-link :to="`affiches`">
          <h5 class="text-xl text-gray-700 rubrique">Dernières affiches :</h5>
        </router-link>
        <div class="dernieresAffiche">
          <div v-for="(affiche, index) in affiches.slice(0, 3)">
            <div class="derniereAffiche">
              <div class="titleDerniereaffiches">{{ affiche.title }} :</div>
              <div class="imageDerniereAffiche">
                <img
                  v-bind:src="affiche.url"
                  style="
                    width: 400px;
                    height: 300px;
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
      <div
        class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white listNouveauxMembres"
      >
        <h5 class="text-xl text-gray-700 rubrique">
          Nouveaux membres de l'association :
        </h5>

        <div class="dernieresAffiche">
          <div class="derniereAffiche">
            <div class="titleDerniereaffiches">:</div>
            <div class="imageDerniereAffiche">
              <img
                src="https://cdn-icons-png.flaticon.com/128/6373/6373828.png"
                style="
                  width: 400px;
                  height: 460px;
                  margin-left: auto;
                  margin-right: auto;
                  margin-bottom: 15px;
                "
              />
            </div>
            <div class="creea"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.OngletsResponsives {
  display: none;
}
.asideResponsive {
  width: 300px !important;
  /* margin-top: 90px; */
  z-index: 1 !important;
}
/* .fermerAside {
  position: absolute;
  top: 63px;
  z-index: 1;
  left: 243px;
} */
.nouveauxcandidats {
  position: absolute;
  left: 25px;
  top: 26px;
  border: 1px solid;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  background-color: green;
  font-weight: 900;
  color: white;
  text-align: center;
}
.nouveauxmessagesaccueil {
  position: absolute;
  left: 25px;
  top: -10px;
  border: 1px solid;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  background-color: green;
  font-weight: 900;
  color: white;
  text-align: center;
}
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
.titleDerniereaffiches {
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
.pasdeMessages {
  font-size: 25px;
}
.lienMessagerie {
  color: rgb(70, 137, 226);
}
.lienMessagerie:hover {
  cursor: pointer;
  font-style: italic;
}
.messs {
  margin-top: 15px;
  overflow: scroll;
  height: 500px;
}
.mess {
  background-color: white;
  margin-bottom: 5px;
  padding: 5px;
  border-radius: 5px;
  width: 70%;
}

@media screen and (max-width: 800px) {
  /* .px-6 {
display: none;
 }  */
  .OngletsResponsives {
    display: block !important;
  }
  .onglets {
    display: none;
  }
  .listderniereaffiches {
    display: none;
  }
  .ongletResponsive {
    width: 90%;
    margin-left: 4.5%;
  }
  .listderniereaffichesResponsive {
    width: 90%;
    margin-left: 4.5%;
  }
  .listNouveauxMembres {
    width: 90%;
    margin-left: 4.5%;
  }
}
@media screen and (max-width: 1022px) {
  .barreasideResponsive {
    margin-left: 301px !important;
  }
  .notVisibility {
    display: none !important;
  }
  .conversationSelectionne {
    left: 5% !important;
    width: 80% !important;
  }
}
</style>
