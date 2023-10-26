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
    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
    
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    update_user: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
};
</script>
<template>
  <h1>Confirmation</h1>
  <div v-for="membre in membres">
    <div v-show="membre.id == user.id">
      <p>
        Nom: {{ membre.name }}
        <br />
        Prenom: {{ membre.prenom }}
        <br />
        Télèphone: {{ membre.telephone }}
        <br />
        Email: {{ membre.email }}
        <br />
        Mot de passe: {{ membre.password }}
        <br />
        niveau: {{ membre.niveau }}
        <br />
        <button @click="update_user">Confirmer</button>
      </p>
    </div>
  </div>
</template>
<style>
.confirmeupdate {
  /* margin-top: 7%; */
  text-align: center;
  border: 1px solid;
}
</style>
