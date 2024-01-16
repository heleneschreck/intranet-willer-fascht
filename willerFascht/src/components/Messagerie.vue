<script>
import { mapState } from "vuex";
import moment from "moment";
export default {
  data() {
    return {
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
      conversation_id: null,
    };
  },
  mounted() {},
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
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    // liste de toutes les conversations
    let fetched_generalConversations = await fetch(
      "http://localhost:8000/api/conversations"
    );
    this.generalConversations = await fetched_generalConversations.json();
    // console.log(this.generalConversations);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    for (let i = 0; i < this.generalConversations.length; i++) {
      await this.fetchConversationsForCount(this.generalConversations[i]);
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
      console.log(this.conversation_id.id);
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
      console.log(conversation_id);
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

      console.log(this.particpantsConversation);
      console.log(this.membres);

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
          console.log(this.NomembresIds);

      let fetched_messages = await fetch(
        "http://localhost:8000/api/messages/conversation/" + conversation.id
      );
      let messages = await fetched_messages.json();
      messages = messages.sort(
        (a, b) =>  b.created_at.localeCompare(a.created_at)
        );
        this.messages = messages.reverse(); // Reverse the array to display the oldest messages first
        
        this.conversation_id = conversation;



        let fetched_Lu = await fetch("http://localhost:8000/api/lu")
              this.lus = await fetched_Lu.json();
              console.log(this.lus);
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
          console.log(result);
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
      console.log(ConversationsData);
      generalConversation.conversation = {
        count: ConversationsData.count || 0,
      };
      console.log(generalConversation.conversation.count);
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
            participantParams.append("conversation_id", this.conversation_id.id);
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
  <div class="interfaceMessagerie">
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
          <div v-for="membre in membres" style="display: flex">
            <div v-for="conversation in generalConversations">
              <div v-if="conversation.id == conversationUser.conversation_id">
                <div v-for="destinataire in destinataires">
                  <div v-if="destinataire.conversation_id == conversation.id">
                    <div
                      v-if="
                        membre.id == destinataire.users_id &&
                        conversation.conversation.count == 1
                      "
                      @click="display_Messages(conversation)"
                      class="pasdedestinataire"
                    >
                      Ajoutez un destinataire à la conversation :
                    </div>

                    <div
                      v-else-if="
                        membre.id == destinataire.users_id &&
                        destinataire.users_id != user.id
                      "
                      @click="display_Messages(conversation)"
                    >
                      {{ membre.prenom }},
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="quitterconversation">
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
<div v-if="lu.message_id== message.id">
{{ lu }}
  <div class="contenu" v-if="lu.Lu == 0" style="font-weight: bold;font-size: 20px;">

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
<style>
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
</style>
