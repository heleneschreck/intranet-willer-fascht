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
  <div class="addmembres">
    <h1>Ajouter un membre</h1>
    <label for="name">Nom:</label>
    <input v-model="name" class="input" type="text" />
    <label for="prenom">Prénom:</label>
    <input v-model="prenom" class="input" type="text" />
    <label for="telephone">Télèphone:</label>
    <input v-model="telephone" class="input" type="tel" />
    <label for="mail">Adresse mail:</label>
    <input v-model="email" class="input" type="email" />
    <label for="password">Password:</label>
    <input v-model="password" class="input" type="password" />
    <label for="niveau">Niveau:</label>
    <input v-model="niveau" class="input" type="number" max="1" min="0" />
    <br />
    <button
      @click="createMember()"
      class="button rounded-lg button-disabled connexions"
    >
      Ajouter le membre
    </button>
  </div>
</template>
<style>
h1 {
  font-size: large;
}
nav {
  margin-bottom: 1%;
}
.addmembres {
  margin-top: 7%;
  margin-left: 1%;
  margin-right: 1%;
}
</style>
