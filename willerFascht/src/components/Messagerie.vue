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
      conversation_id: null,
    };
  },
  mounted() {},
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    this.moment = moment;

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_conversations = await fetch(
      "http://localhost:8000/api/conversations/user/" + this.user.id
    );
    this.conversations = await fetched_conversations.json();
    console.table(this.conversations);

    let fetched_conversationsUser = await fetch(
      "http://localhost:8000/api/conversation_users/user/" + this.user.id
    );
    this.conversationsUser = await fetched_conversationsUser.json();
    console.table(this.conversationsUser);

    let fetched_generalConversations = await fetch(
      "http://localhost:8000/api/conversations"
    );
    this.generalConversations = await fetched_generalConversations.json();
    console.table(this.generalConversations);

    let fetched_destinataires = await fetch(
      "http://localhost:8000/api/conversation_users/"
    );
    this.destinataires = await fetched_destinataires.json();
    console.table(this.destinataires);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    display_Messages: async function (conversation) {
      this.mode = "conversations";
      console.log(conversation.user_id);
      // destinataires
      let fetched_membresConversation = await fetch(
        "http://localhost:8000/api/conversation_users/conversation/" +
          conversation.id
      );
      this.membresConversation = await fetched_membresConversation.json();
      console.table(this.membresConversation);

      // messages
      let fetched_messages = await fetch(
        "http://localhost:8000/api/messages/conversation/" + conversation.id
      );
      this.messages = await fetched_messages.json();
      console.table(this.messages);

      this.conversation_id = conversation;
      return this.conversation_id;
    },
   
    delete_destinataire: function (membreConversation) {
      console.log(membreConversation.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url =
        "http://localhost:8000/api/conversation_users/" + membreConversation.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    deletemessage: function (message) {
      console.log(message.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/messages/" + message.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    ajoutMessage: function () {
      console.log(this.user.id);
      console.log(this.content);
      console.log(this.conversation_id.id);

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
      let url = "http://localhost:8000/api/messages";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    AjoutDestinataire: function () {
      console.log(this.users_id);
      console.log(this.conversation_id.id);

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
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    ajouterconversation: function () {
      console.log(this.user.id);

      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/conversations";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
    },
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
        <div
          v-for="conversation in conversations"
          class="conversation"
          @click="display_Messages(conversation)"
        >
          <div v-for="destinataire in destinataires">
            <div v-if="destinataire.conversation_id == conversation.id">
              <div v-for="membre in membres">
                <div v-if="membre.id == destinataire.users_id">
                  {{ membre.prenom }} ,
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-for="conversationUser in conversationsUser" class="conversation" >
          <div v-for="conversation in generalConversations">
            
            <div v-if="conversation.id == conversationUser.conversation_id ">
              <div v-for="membre in membres">
                <div v-if="membre.id == conversation.user_id" @click="display_Messages(conversation)">
                  {{ membre.prenom }} ,
                </div>
              </div>
            </div>
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
        <div v-for="membreConversation in membresConversation">
          <div v-for="membre in membres">
            <div v-if="membre.id == membreConversation.users_id">
              <h2 @mouseover="survolprenom()">
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
        <select
          v-if="mode == 'ajoutdestinataire'"
          v-model="users_id"
          name="users_id"
          id="selectmembre"
          @change="AjoutDestinataire()"
        >
          <option disabled>Ajouter un membre à la conversation</option>

          <option v-for="membre in membres" :key="membre.id" :value="membre.id">
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
              {{ message.message }}
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
            <div class="contenu">
              {{ message.message }}
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
        <div class="imageajoutmessage" @click="ajoutMessage()">
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
  margin-top: 6% !important;
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
  padding: 2px;
  font-weight: bold !important;
  border-radius: 10px;
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
