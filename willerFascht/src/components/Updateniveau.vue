<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
const route = useRoute();

export default {
  data() {
    return {
      mode: false,

      user: [],

      membres: [],

      //
    };
  },

  async created() {
    // this.user = JSON.parse(localStorage.getItem("user") || "[]");
    // console.log(this.user);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
  },

  methods: {
    updateniveau: function (membre) {
      console.log(membre.niveau);
      console.log(this.$route.params.membre);
      var myHeaders = new Headers();

      var urlencoded = new URLSearchParams();
      urlencoded.append("niveau", membre.niveau);


      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
let url ="http://127.0.0.1:8000/api/users/"+this.$route.params.membre
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
        setTimeout(() => {
        this.$router.push({ path: "/membres" });
      }, "2000");
  
    },
  },
};
</script>
<template>
  <router-link :to="`/membres`">
    <button class="button rounded-lg retourmembres">Retour</button>
  </router-link>
  <div v-for="membre in membres">
    <div v-if="membre.id == $route.params.membre">
      <h1>Modifier le niveau de {{ membre.prenom }} :</h1>

      <!-- <p>Rappel : Le niveau de {{ membre.prenom }} est {{ membre.niveau }}</p> -->
      <div class="modifniveau">
        <label for="niveau">Niveau:</label>
        <input v-model="membre.niveau" class="inputniveau" type="number" max="1" min="0" />
      </div>
      <br />
      <button
        @click="updateniveau(membre)"
        class="button rounded-lg button-disabled updateniveau"
      >
        Modifier le statut de {{ membre.prenom }}
      </button>
    </div>
  </div>
</template>
<style>
  .updateniveau {
    /* margin-top: 8% !important; */
    width: 90%;
    margin: auto;

  }
.modifniveau {
  display: inline-flex;
  /* text-align: center; */
  margin-left: 30%;
  margin-top: 5%;
  margin-bottom: 5%;
  font-size: 45px;
}
.inputniveau{
  margin-left: 10%;
}
.retourmembres {
  margin-top: -600px !important;
}
</style>
