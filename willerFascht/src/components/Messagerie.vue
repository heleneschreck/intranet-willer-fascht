<script>
import { mapState } from "vuex";
import moment from "moment";
export default {
  data() {
    return {
      loading: true,
      mode: false,
      mode: "nodisplay",
      member: Number,
      user: [],
      membres: [],
      conversations: [],
      destinataires: [],
      messages: [],
      membresConversation: [],
      conversationsUser: [],
      generalConversations: [],
      generalConversation: [],
      particpantsConversation: [],
      NomembresIds: [],
      lus: [],
      NBMessageNonlu: [],
      conversation_id: null,
    };
  },
  mounted() {
    setTimeout(() => {
      this.loading = false; 
    }, 10000);
  },

  computed: {
    ...mapState(["userInfos"]),
  },

  async created() {
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    // UTILISATEUR CONNECTER
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    // console.log(this.user.id);

    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    // Module date
    this.moment = moment;

    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    // membres associations
    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    // console.log(this.membres.id);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    // liste conversations où l'utilisateur participe
    let fetched_conversationsUser = await fetch(
      "http://localhost:8000/api/conversation_users/user/" + this.user.id
    );
    this.conversationsUser = await fetched_conversationsUser.json();
    // console.log(this.conversationsUser);

    for (let i = 0; i < this.conversationsUser.length; i++) {
      const conversationUser = this.conversationsUser[i];
      //  console.log(conversationUser);
      await this.fetchLuForCount(conversationUser);
    }
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    // liste de toutes les conversations
    let fetched_generalConversations = await fetch(
      "http://localhost:8000/api/conversations"
    );
    this.generalConversations = await fetched_generalConversations.json();

    console.log(this.generalConversations);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    for (let i = 0; i < this.generalConversations.length; i++) {
      await this.fetchConversationsForCount(this.generalConversations[i]);
      //  console.log(this.generalConversations[i].id);
    }
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    let fetched_destinataires = await fetch(
      "http://localhost:8000/api/conversation_users/"
    );
    this.destinataires = await fetched_destinataires.json();
    // console.log(this.destinataires);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
  },
  methods: {
    // fonction delai
    async makeRequestsWithBackoff(requestCount, delay) {
      for (let i = 0; i < requestCount; i++) {
        try {
          await makeRequest();
        } catch (error) {
          console.error("Erreur lors de la requête :", error);
        }
        await delay(delay); // Introduire le délai après chaque requête
        delay *= 2; // Augmenter le délai pour la prochaine requête (facultatif)
      }
    },

    async fetchLuForCount(conversationUser) {
      let NBMessageNonluURL =
        "http://localhost:8000/api/lu/" +
        this.user.id +
        "/" +
        conversationUser.conversation_id;
      // console.log(NBMessageNonluURL);
      let NBMessageNonlu = await fetch(
        "http://localhost:8000/api/lu/" +
          this.user.id +
          "/" +
          conversationUser.conversation_id
      );
      let NBMessageNonluData = await NBMessageNonlu.json();
      // console.log(this.NBMessageNonlu.count);

      conversationUser.NBMessageNonlu = {
        count: NBMessageNonluData.count || 0,
      };
    },

    async updateElements(conversationUser) {
      await this.fetchLuForCount(conversationUser);
    },
    async updateConversationCount(conversationId) {
      const conversation = this.generalConversations.find(
        (conv) => conv.id === conversationId
      );
      if (conversation) {
        await this.fetchConversationsForCount(conversation);
      }
    },

    // mettre à jour api destinataire
    chargerDestinataires() {
      fetch("http://localhost:8000/api/conversation_users/")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.destinataires = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    chargerMembres() {
      fetch("http://localhost:8000/api/users/")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.membres = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    chargerConversations() {
      fetch("http://localhost:8000/api/conversations")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.generalConversations = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    // mettre à jour Conversations
    chargerConversationsUser() {
      fetch("http://localhost:8000/api/conversation_users/user/" + this.user.id)
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.conversationsUser = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    chargerDisplayDestinataire() {
      fetch("http://localhost:8000/api/conversation_users/")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.destinataires = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },

    chargerDisplayMessages(conversation_id) {
      // console.log(this.conversation_id.id);
      fetch(
        "http://localhost:8000/api/conversation_users/conversation/" +
          this.conversation_id.id
      )
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.membresConversation = data;
          this.mode = "conversations";
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },

    chargerMessages(conversation_id) {
      // console.log(conversation_id);
      fetch(
        "http://localhost:8000/api/messages/conversation/" + conversation_id
      )
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.messages = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
    elementsLu(conversation) {
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
          conversation.id +
          "/0",
        requestOptions
      )
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.display_Messages(conversation);
          this.chargerDisplayDestinataire();
          this.chargerDestinataires();
          this.chargerConversationsUser();
          this.updateConversationCount(this.conversation_id.id);
          this.chargerDisplayMessages(this.conversation_id);
          this.fetchLuForCount(conversationUser);
          this.updateElements(conversationUser);
        })
        .catch((error) => console.log("error", error));
    },

    ajouterconversation: async function () {
      const urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);

      const requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };

      const url = "http://localhost:8000/api/conversations";

      try {
        const response = await fetch(url, requestOptions);
        const result = await response.json(); // Parse the response JSON
        console.log(result);

        // Assuming the API response has an "id" property
        if (result.id !== undefined) {
          // Update the component's result property
          this.result = result;

          // Access the ID
        } else {
          console.log("Response does not contain an ID.");
        }
      } catch (error) {
        console.log("error", error);
      }

      console.log(this.result.id);

      var urlencoded2 = new URLSearchParams();
      urlencoded2.append("users_id", this.user.id);
      urlencoded2.append("conversation_id", this.result.id);

      var requestOptions2 = {
        method: "POST",
        body: urlencoded2,
        redirect: "follow",
      };
      let url2 = "http://localhost:8000/api/conversation_users";
      fetch(url2, requestOptions2)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    // Afficher la conversation selectionné
    display_Messages: async function (conversation) {
      // console.log(conversation.user_id);
      this.mode = "conversations";

      // destinataires
      let fetched_membresConversation = await fetch(
        "http://localhost:8000/api/conversation_users/conversation/" +
          conversation.id
      );
      this.membresConversation = await fetched_membresConversation.json();

      let fetched_participantsConversation = await fetch(
        "http://localhost:8000/api/conversation_users/participants/" +
          conversation.id
      );
      this.particpantsConversation =
        await fetched_participantsConversation.json();

      // console.log(this.particpantsConversation);
      // console.log(this.membres);

      // Convertir les chaînes d'identifiants en nombres
      const participantsIds = this.particpantsConversation.map((participant) =>
        parseInt(participant)
      );

      // Filtrer les membres en excluant ceux qui sont déjà participants
      const membresNonParticipants = this.membres.filter(
        (membre) => !participantsIds.includes(membre.id)
      );

      // Créer un tableau d'objets à partir des membres restants
      const membresObjets = membresNonParticipants.map((membre) => ({
        id: membre.id,
        prenom: membre.prenom,
        // ... (autres propriétés que vous souhaitez inclure)
      }));

      this.NomembresIds = membresObjets;
      // console.log(this.NomembresIds);

      let fetched_messages = await fetch(
        "http://localhost:8000/api/messages/conversation/" + conversation.id
      );
      let messages = await fetched_messages.json();
      messages = messages.sort((a, b) =>
        b.created_at.localeCompare(a.created_at)
      );
      this.messages = messages.reverse(); // Reverse the array to display the oldest messages first

      this.conversation_id = conversation;

      let fetched_Lu = await fetch("http://localhost:8000/api/lu");
      this.lus = await fetched_Lu.json();
      // console.log(this.lus);
      return this.conversation_id;
    },

    // Ajouter des participants à la conversation
    AjoutDestinataire: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("users_id", this.users_id);
      urlencoded.append("conversation_id", this.conversation_id.id);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/conversation_users";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          // console.log(result);
          this.chargerDisplayDestinataire();
          this.chargerDestinataires();
          this.chargerConversationsUser();
          this.updateConversationCount(this.conversation_id.id);
          this.chargerDisplayMessages(this.conversation_id);
        })
        .catch((error) => console.log("error", error));
    },
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    async fetchConversationsForCount(generalConversation) {
      let fetched_count = await fetch(
        "http://localhost:8000/api/conversation_users/conversation/" +
          generalConversation.id
      );
      let ConversationsData = await fetched_count.json();
      // console.log(ConversationsData);
      generalConversation.conversation = {
        count: ConversationsData.count || 0,
      };
      // console.log(generalConversation.conversation.count);
    },

    // Sortir de la conversation
    sortir_Conversation: function (conversationUser) {
      // console.log(conversationUser.conversation_id);
      // console.log(this.user.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };

      fetch(
        "http://localhost:8000/api/conversation_users/" +
          this.user.id +
          "/" +
          conversationUser.conversation_id,
        requestOptions
      )
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerDisplayDestinataire();
          this.chargerDestinataires();
          this.chargerConversationsUser();
          this.updateConversationCount(this.conversation_id.id);
          this.chargerDisplayMessages(this.conversation_id);
        })
        .catch((error) => console.log("error", error));
    },

    // Supprimer les destinataire de la conversation
    delete_destinataire: function (membreConversation) {
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url =
        "http://localhost:8000/api/conversation_users/" + membreConversation.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerDisplayDestinataire();
          this.chargerDestinataires();
          this.chargerConversationsUser();
          this.chargerDisplayMessages(this.conversation_id);
        })
        .catch((error) => console.log("error", error));
    },
    deletemessage: function (message) {
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/messages/" + message.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => {
          console.log(result);
          this.chargerMessages(this.conversation_id.id);
        })
        .catch((error) => console.log("error", error));
    },

    // changer lu
    Lu: async function (message) {
      function delay(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
      }
      console.log(this.conversation_id.id);
      console.log(message);

      // destinataires
      let fetched_membresConversation = await fetch(
        "http://localhost:8000/api/conversation_users/conversation/" +
          this.conversation_id.id
      );
      this.IdmembresConversation = await fetched_membresConversation.json();
      // console.log(this.IdmembresConversation.conversation);
      this.reponse = this.IdmembresConversation.conversation;
      console.table(this.reponse);
      function delay(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
      }
      for (let i = 0; i < this.reponse.length; i++) {
        this.reponses = this.reponse[i];
        console.log(this.reponses.users_id);

        this.touslemondealu = this.reponses.users_id;
        console.log(this.touslemondealu);
      }
    },

    // Ecrire dans la conversation
    ajoutMessage: async function () {
      let fetched_participantsConversation = await fetch(
        "http://localhost:8000/api/conversation_users/participants/" +
          this.conversation_id.id
      );
      this.participantsConversation =
        await fetched_participantsConversation.json();

      const participantsArray = Object.values(this.participantsConversation);

      const membresNonLu = participantsArray
        .filter((participantId) => participantId !== `${this.user.id}`)
        .map((participantId) => participantId);

      console.log(membresNonLu);

      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("conversation_id", this.conversation_id.id);
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
              this.conversation_id.id
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

          this.chargerMessages(this.conversation_id.id);
          this.content = "";
        } else {
          console.log("Response does not contain an ID.");
        }
      } catch (error) {
        console.log("error", error);
      }
    },

    // Afficher
    survol: function () {
      this.mode = "display_delete";
    },
    survolprenom: function () {
      this.mode = "display_deletedestinataire";
    },
    annuleoptions: function () {
      this.mode = "conversations";
    },
    display_ajoutDestinataires: function () {
      this.mode = "ajoutdestinataire";
    },
  },
};
</script>
<template>
   <div 
class="loading"
  v-if="loading"
   > 
    <svg xmlns="http://www.w3.org/2000/svg" height="200px" width="200px" viewBox="0 0 200 200" class="pencil">
  	<defs>
		<clipPath id="pencil-eraser">
			<rect height="30" width="30" ry="5" rx="5"></rect>
		</clipPath>
	</defs>
	<circle transform="rotate(-113,100,100)" stroke-linecap="round" stroke-dashoffset="439.82" stroke-dasharray="439.82 439.82" stroke-width="2" stroke="currentColor" fill="none" r="70" class="pencil__stroke"></circle>
	<g transform="translate(100,100)" class="pencil__rotate">
		<g fill="none">
			<circle transform="rotate(-90)" stroke-dashoffset="402" stroke-dasharray="402.12 402.12" stroke-width="30" stroke="hsl(223,90%,50%)" r="64" class="pencil__body1"></circle>
			<circle transform="rotate(-90)" stroke-dashoffset="465" stroke-dasharray="464.96 464.96" stroke-width="10" stroke="hsl(223,90%,60%)" r="74" class="pencil__body2"></circle>
			<circle transform="rotate(-90)" stroke-dashoffset="339" stroke-dasharray="339.29 339.29" stroke-width="10" stroke="hsl(223,90%,40%)" r="54" class="pencil__body3"></circle>
		</g>
		<g transform="rotate(-90) translate(49,0)" class="pencil__eraser">
			<g class="pencil__eraser-skew">
				<rect height="30" width="30" ry="5" rx="5" fill="hsl(223,90%,70%)"></rect>
				<rect clip-path="url(#pencil-eraser)" height="30" width="5" fill="hsl(223,90%,60%)"></rect>
				<rect height="20" width="30" fill="hsl(223,10%,90%)"></rect>
				<rect height="20" width="15" fill="hsl(223,10%,70%)"></rect>
				<rect height="20" width="5" fill="hsl(223,10%,80%)"></rect>
				<rect height="2" width="30" y="6" fill="hsla(223,10%,10%,0.2)"></rect>
				<rect height="2" width="30" y="13" fill="hsla(223,10%,10%,0.2)"></rect>
			</g>
		</g>
		<g transform="rotate(-90) translate(49,-30)" class="pencil__point">
			<polygon points="15 0,30 30,0 30" fill="hsl(33,90%,70%)"></polygon>
			<polygon points="15 0,6 30,0 30" fill="hsl(33,90%,50%)"></polygon>
			<polygon points="15 0,20 10,10 10" fill="hsl(223,10%,10%)"></polygon>
		</g>
	</g>
</svg>  
  
  </div>
  
  <div class="interfaceMessagerie" :class="{ blurred: loading }">
    <div class="conversations">
      <div class="enteteFlex">
        <div class="titreConversations">
          <h3>conversations :</h3>
        </div>
        <div class="ajoutConversation" @click="ajouterconversation()">
          <img
            src="https://cdn-icons-png.flaticon.com/128/8954/8954250.png"
            class="nouvelleConversation"
            alt="nouvelleconversation"
          />
        </div>
      </div>
      <hr />
      <br />
      <div class="listeConversations">
        <div v-for="conversationUser in conversationsUser" class="conversation">
          <div
            style="
              word-wrap: break-word !important ;
              display: flex;
              flex-wrap: wrap;
              width: 484px;
            "
          >
            <div v-for="membre in membres">
              <div v-for="conversation in generalConversations">
                <div v-if="conversation.id == conversationUser.conversation_id">
                  <div v-for="destinataire in destinataires">
                    <div v-if="destinataire.conversation_id == conversation.id">
                      <div
                        v-if="
                          membre.id == destinataire.users_id &&
                          conversation.conversation.count == 1
                        "
                         @click="elementsLu(conversation)"
                        class="pasdedestinataire"
                      >
                        Ajoutez un destinataire à la conversation :
                      </div>

                      <div
                        class="conversationFlex"
                        v-else-if="
                          membre.id == destinataire.users_id &&
                          destinataire.users_id != user.id
                        "
                      >
                        <div
                          v-if="
                            conversationUser.NBMessageNonlu &&
                            conversationUser.NBMessageNonlu.count > 0
                          "
                          @click="elementsLu(conversation)"
                          style="font-weight: bold"
                        >
                          {{ membre.prenom }}
                          &nbsp;
                          <!-- {{ conversation }} -->
                        </div>
                        <div @click="display_Messages(conversation)" v-else>
                          {{ membre.prenom }}
                          &nbsp;
                          <!-- {{ conversation }} -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <span
              class="nouveauxmessages"
              v-if="
                conversationUser.NBMessageNonlu &&
                conversationUser.NBMessageNonlu.count > 0
              "
            >
              {{ conversationUser.NBMessageNonlu.count }}
            </span>
          </div>

          <div
            class="quitterconversation"
            style="position: absolute; left: 90%; padding-left: 2px"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/4008/4008990.png"
              class="illustrationQuitterConversation"
              alt="quitter la conversation"
              @click="sortir_Conversation(conversationUser)"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="conversationMessages">
      <span class="selectconversations" v-if="mode == 'nodisplay'">
        Selectionner une conversation 😜
      </span>
      <div
        class="membresconversations"
        v-if="
          mode == 'conversations' ||
          mode == 'display_delete' ||
          mode == 'display_deletedestinataire' ||
          mode == 'ajoutdestinataire'
        "
      >
        <div v-for="membreConversation in membresConversation.conversation">
          <div v-if="membresConversation.count != 1">
            <div v-for="membre in membres" class="listeprenom">
              <div
                v-if="
                  membre.id == membreConversation.users_id &&
                  membre.id != user.id
                "
              >
                <h2
                  @mouseover="survolprenom()"
                  style="font-size: 130% !important"
                >
                  {{ membre.prenom }}
                  <button
                    class="deletedestinataire"
                    v-if="mode == 'display_deletedestinataire'"
                  >
                    <img
                      src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                      alt=""
                      @click="delete_destinataire(membreConversation)"
                    />
                  </button>
                  ,
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div v-if="membresConversation.count == 1">
          <h2 class="pasdedestinataire">
            Ajoutez un destinataire à la conversation :
          </h2>
        </div>
        <select
          v-if="mode == 'ajoutdestinataire'"
          v-model="users_id"
          name="users_id"
          id="selectmembre"
          @change="AjoutDestinataire()"
        >
          <option disabled>Ajouter un membre à la conversation</option>

          <option
            v-for="membre in NomembresIds"
            :key="membre.id"
            :value="membre.id"
          >
            {{ membre.prenom }}
          </option>
        </select>
        <img
          src="https://cdn-icons-png.flaticon.com/128/7812/7812309.png"
          class="ajoutdestinataire"
          alt="ajoutdestinataire"
          @click="display_ajoutDestinataires()"
        />
      </div>

      <div class="corpsMessages">
        <div v-for="message in messages">
          <div
            v-if="user.id == message.user_id"
            class="corpMessageperso"
            @mouseover="survol()"
          >
            <div class="emmetteur">
              <div v-for="membre in membres">
                <div v-if="membre.id == message.user_id">
                  {{ membre.prenom }}
                </div>
              </div>
              <button
                class="deletemessage"
                @click="deletemessage(message)"
                v-if="mode == 'display_delete'"
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                  alt=""
                />
              </button>
            </div>
            <div class="contenu">
              <div>
                {{ message.message }}
              </div>
            </div>
            <div class="datemessage">
              {{ moment(message.created_at).format("DD/MM/YYYY") }}
            </div>
          </div>

          <div v-else class="corpMessage" @mouseover="annuleoptions()">
            <div class="emmetteur">
              <div v-for="membre in membres">
                <div v-if="membre.id == message.user_id">
                  {{ membre.prenom }}
                </div>
              </div>
            </div>
            <div v-for="lu in lus">
              <div v-if="lu.message_id == message.id">
                <div
                  class="contenu"
                  v-if="lu.Lu == 0"
                  style="font-weight: bold; font-size: 20px"
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
      <div
        class="ajoutermessage"
        v-if="
          mode == 'conversations' ||
          mode == 'display_delete' ||
          mode == 'display_deletedestinataire' ||
          mode == 'ajoutdestinataire'
        "
      >
        <textarea v-model="content" class="inputmessages" />
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
</template>
<style scoped>
.retourliste{
  margin-left: 10px;
  /* margin-bottom: 35px !important; */
  border-radius: 20px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  /* color: white !important; */
  font-weight: bold;
  font-size: 20px;
  height: 70px !important;

}
.messagerie {
  /* margin-top: 6% !important; */
  background-color: hsla(0, 0%, 84%, 0.3);
}

.interfaceMessagerie {
  display: flex;
  background-color: white;
}
.conversations {
  border: 1px solid hsla(0, 0%, 84%, 0.3);
  background-color: hsla(0, 0%, 84%, 0.3);
  width: 30%;
}
.conversation {
  background-color: white;
  display: flex;
  margin-left: 15px;
  padding: 5px;
  margin-right: 15px;
  font-size: 18px;
  margin-bottom: 15px;
  border-radius: 5px;
}
.conversation:hover {
  cursor: pointer;
}
.illustrationQuitterConversation:hover {
  cursor: pointer;
}
.quitterconversation {
  margin-left: 15px;
}
.illustrationQuitterConversation {
  width: 30px;
}
.enteteFlex {
  display: flex;
  justify-content: space-between;
  margin-left: 204px;
  margin-bottom: 5px;
}

.titreConversations {
  font-size: 25px;
  text-align: center;
  font-weight: bolder;
}
.ajoutConversation {
  background-color: white;
  border-radius: 53%;
  padding: 1%;
  margin: 5px;
}
.ajoutConversation:hover {
  cursor: pointer;
}
.nouvelleConversation {
  width: 40px;
}
.conversationMessages {
  border: 1px solid hsla(0, 0%, 84%, 0.3);
  background-color: hsla(0, 0%, 84%, 0.3);

  width: 70%;
}
.membresconversations {
  display: flex;
  background-color: white;
  margin-left: 15px;
  margin-right: 15px;
  margin-top: 5px;
  height: 55px;
  padding: 2px;
  font-weight: bold !important;
  border-radius: 10px;
}
.pasdedestinataire {
  color: #c0c0c0;
}
.deletedestinataire {
  width: 30px;
  border: none;
}
#selectmembre {
  /* margin-left: 30px; */
  border: none;
}

.ajoutdestinataire {
  width: 59px;

  position: absolute;
  right: 5px;
  bottom: -2px;
}
.ajoutdestinataire:hover {
  cursor: pointer;
}
.corpMessage {
  margin-top: 5px;
  background-color: white;
  width: 20%;
  font-size: 17px;
  margin-left: 15px;
  margin-right: 15px;
  border-radius: 15px;
}
.corpMessageperso {
  margin-top: 5px;
  background-color: white;
  width: 20%;
  font-size: 17px;
  margin-left: 997px;
  border-radius: 15px;
}
.contenu {
  text-align: center;
  padding-top: 15px;
  padding-bottom: 15px;
}
.datemessage {
  margin-top: 5px;
  text-align: end;
  padding-right: 9px;
}
.ajoutermessage {
  display: flex;
}
.inputmessages {
  margin-left: 15px;
  border: none;
  background-color: white !important;
  border-radius: 10px !important ;
  /* height: 30px !important; */
  margin-top: 15px;
  margin-bottom: 10px;
  width: 93%;
}
.imageajoutmessage {
  background-color: white;
  margin: auto;
  padding: 3px;
  border-radius: 20px !important;
  width: 44px;
}
.imageajoutmessage:hover {
  cursor: pointer;
}
.addmessage {
  width: 33px;
  height: 30px;
}
.emmetteur {
  color: grey;
  font-size: 15px;
  margin-left: 5px;
  display: flex;
}
.deletemessage {
  width: 25px;
  border: none;
  margin-left: 190px;
}
.selectconversations {
  font-size: 28px;

  margin-left: 30%;
}
.nouveauxmessages {
  position: absolute;
  left: 90%;
  border: 1px solid;

  border-radius: 50%;
  width: 30px;
  height: 30px;
  background-color: green;
  font-weight: 900;
  color: white;
  text-align: center;
  /* padding-bottom: 10px; */
}
.pencil {
  display: block;
  width: 40em;
  height: 40em;
}
.loading{
  left: 40%;
position: absolute;
  z-index: 1;
  top: 45%;
}

.pencil__body1,
.pencil__body2,
.pencil__body3,
.pencil__eraser,
.pencil__eraser-skew,
.pencil__point,
.pencil__rotate,
.pencil__stroke {
  animation-duration: 3s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}

.pencil__body1,
.pencil__body2,
.pencil__body3 {
  transform: rotate(-90deg);
}

.pencil__body1 {
  animation-name: pencilBody1;
}

.pencil__body2 {
  animation-name: pencilBody2;
}

.pencil__body3 {
  animation-name: pencilBody3;
}

.pencil__eraser {
  animation-name: pencilEraser;
  transform: rotate(-90deg) translate(49px,0);
}

.pencil__eraser-skew {
  animation-name: pencilEraserSkew;
  animation-timing-function: ease-in-out;
}

.pencil__point {
  animation-name: pencilPoint;
  transform: rotate(-90deg) translate(49px,-30px);
}

.pencil__rotate {
  animation-name: pencilRotate;
}

.pencil__stroke {
  animation-name: pencilStroke;
  transform: translate(100px,100px) rotate(-113deg);
}

/* Animations */
@keyframes pencilBody1 {
  from,
	to {
    stroke-dashoffset: 351.86;
    transform: rotate(-90deg);
  }

  50% {
    stroke-dashoffset: 150.8;
 /* 3/8 of diameter */
    transform: rotate(-225deg);
  }
}

@keyframes pencilBody2 {
  from,
	to {
    stroke-dashoffset: 406.84;
    transform: rotate(-90deg);
  }

  50% {
    stroke-dashoffset: 174.36;
    transform: rotate(-225deg);
  }
}

@keyframes pencilBody3 {
  from,
	to {
    stroke-dashoffset: 296.88;
    transform: rotate(-90deg);
  }

  50% {
    stroke-dashoffset: 127.23;
    transform: rotate(-225deg);
  }
}

@keyframes pencilEraser {
  from,
	to {
    transform: rotate(-45deg) translate(49px,0);
  }

  50% {
    transform: rotate(0deg) translate(49px,0);
  }
}

@keyframes pencilEraserSkew {
  from,
	32.5%,
	67.5%,
	to {
    transform: skewX(0);
  }

  35%,
	65% {
    transform: skewX(-4deg);
  }

  37.5%, 
	62.5% {
    transform: skewX(8deg);
  }

  40%,
	45%,
	50%,
	55%,
	60% {
    transform: skewX(-15deg);
  }

  42.5%,
	47.5%,
	52.5%,
	57.5% {
    transform: skewX(15deg);
  }
}

@keyframes pencilPoint {
  from,
	to {
    transform: rotate(-90deg) translate(49px,-30px);
  }

  50% {
    transform: rotate(-225deg) translate(49px,-30px);
  }
}

@keyframes pencilRotate {
  from {
    transform: translate(100px,100px) rotate(0);
  }

  to {
    transform: translate(100px,100px) rotate(720deg);
  }
}

@keyframes pencilStroke {
  from {
    stroke-dashoffset: 439.82;
    transform: translate(100px,100px) rotate(-113deg);
  }

  50% {
    stroke-dashoffset: 164.93;
    transform: translate(100px,100px) rotate(-113deg);
  }

  75%,
	to {
    stroke-dashoffset: 439.82;
    transform: translate(100px,100px) rotate(112deg);
  }
}
</style>
