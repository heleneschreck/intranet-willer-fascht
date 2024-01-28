<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      user: [],
      membres: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    createMember: function () {
      var urlencoded = new URLSearchParams();
      urlencoded.append("name", this.name);
      urlencoded.append("prenom", this.prenom);
      urlencoded.append("telephone", this.telephone);
      urlencoded.append("email", this.email);
      urlencoded.append("password", this.password);
      urlencoded.append("niveau", this.niveau);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/auth/register/";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));

      // setTimeout(() => {
      //   this.$router.push({ name: "membres" });
      // }, "2000");
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button
      class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste"
    >
      Retour
    </button>
  </router-link>
  <div class="addmembres">
    <h1>Ajouter un membre</h1>
    <div class="question">
      <!-- <label for="name">Nom:</label> -->
      <input v-model="name" placeholder="Nom" class="input" type="text" />
    </div>
    <div class="question">
      <!-- <label for="prenom">Prénom:</label> -->
      <input v-model="prenom" placeholder="Prénom" class="input" type="text" />
    </div>
    <div class="question">
      <!-- <label for="telephone">Télèphone:</label> -->
      <input
        v-model="telephone"
        placeholder="Télèphone"
        class="input"
        type="tel"
      />
    </div>
    <div class="question">
      <!-- <label for="mail">Adresse mail:</label> -->
      <input v-model="email" class="input" placeholder="Email" type="email" />
    </div>
    <div class="question">
      <!-- <label for="password">Password:</label> -->
      <input
        v-model="password"
        class="input"
        placeholder="Mot de passe"
        type="password"
      />
    </div>
    <div class="question">
      <!-- <label for="niveau">Niveau:</label> -->
      <input
        v-model="niveau"
        class="input"
        placeholder="Niveau"
        type="number"
        max="1"
        min="0"
      />
    </div>
    <br />
    <div class="contaigner">

      <button
      @click="createMember()"
      class="button rounded-lg button-disabled connexions"
      >
      Ajouter le membre
    </button>
  </div>
  </div>
</template>
<style scoped>
h1 {
  font-size: large;
}
nav {
  margin-bottom: 1%;
}
.addmembres {
  box-shadow: 4px 1px 20px 0px black;
  border: 1px solid;
  width: 45%;

  margin-left: 31%;

  margin-bottom: 13px; 

}
.contaigner{
    display: flex !important;
  align-items: center;
  justify-content: center;
}
.retourliste {
  margin-bottom: 15px !important;
}
.input {
  width: 90%;
  border-radius: 15px;
  /* height: 55px; */
}
.connexions {
  border-radius: 20px;
  border: 5px solid green;
  background-color: green;
  color: white;
  font-weight: bold;
  font-size: 20px;
  /* margin-left: 38%; */
  margin-bottom: 15px;
 
}
/* .input {
  position: absolute;
} */
</style>
