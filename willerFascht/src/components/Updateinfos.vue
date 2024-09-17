<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
const route = useRoute();
export default {
  data() {
    return {
      route: route,
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
    update_user: function () {
      console.log(this.user);
      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

      var urlencoded = new URLSearchParams();
      urlencoded.append("name", this.user.name);
      urlencoded.append("prenom", this.user.prenom);
      urlencoded.append("telephone", this.user.telephone);
      urlencoded.append("email", this.user.email);
      urlencoded.append("niveau", this.user.niveau);

      var requestOptions = {
        method: "PUT",
        headers: myHeaders,
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/users/" + this.$route.params.user;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
        this.$router.push("/confirmeupdate");
    },
  },
};
</script>
<template>
  <div class="updateinfo">
    <h1>Modification de mes informations :</h1>
    <br />

    <input v-model="user.email" /><br />

    <input v-model="user.telephone" /><br />

    <input
      pattern="(?=^.{4,15}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*\W.*$"
      title="le mot de passe doit contenir entre 4 et 15 caractères et avoir
au moins un chiffre, une lettre majuscule, une lettre minuscule
et un caractère d'un autre type (ponctuation, contrôle...)" type ="password"
      v-model="user.password"
    /><br />

    <button @click="update_user">Mise à jour</button>
  </div>
</template>
<style scoped>
h1 {
  font-size: large;
}
nav {
  margin-bottom: 1%;
}
.updateinfos {
  /* margin-top: 15%; */
  margin-left: 1%;
  margin-right: 1%;
}
.updateinfo {
  border: 1px solid black;
  width: 50%;
  text-align: center;
  display: block;
  margin-left: 25%;
}

input {
  width: 350px;
  text-align: center;
  align-content: center;
  /* align-items: center; */
}
</style>
