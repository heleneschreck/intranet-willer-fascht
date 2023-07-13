<script>
import { mapState } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      mode: "login",
      value: 0,
      name: "",
      prenom:"",
      email: "",
      telephone:"",
      password: "",
      errors: "",
      loading: false,
    };
  },
  computed: {
    ...mapState(["status"]),
  },
  async beforeMount() {

  },

  methods: {
    switchToCreateAccount: function () {
      this.mode = "create";
    },
    switchToLogin: function () {
      this.mode = "login";
    },
    connect: function () {
      const self = this;
      this.loading = true;
      this.$store
        .dispatch("connect", {
          email: this.email,
          password: this.password,
        })
        .then(
          function (response) {
            self.$router.push("/accueilintra");
          },
          function (error) {
            console.log(error);
          }
        );
    },
    createAccount: function () {
    //  console.log(this.name,this.prenom,this.telephone,this.email,this.password); 
     const self = this;
      this.$store
        .dispatch("createAccount", {
          name: this.name,
          prenom:this.prenom,
          telephone: this.telephone,
          email: this.email,
          password: this.password,
          niveau: "0"
          
        })
        .then(
          function (response) {
            self.connect();
          },
          function (error) {
            console.log(error);
          }
        );
    },
  },
};
</script>

<template>
  <div class="card">
    <!-- titre login -->
    <h1 class="card_title" v-if="mode == 'login'">Connexion</h1>
    <!-- titre register -->
    <h1 class="card_title" v-if="mode == 'create'">Insciption</h1>

    <div class="form-row" v-if="mode == 'create'">
      <label for="name">Nom:</label>
      <input v-model="name" class="input" type="text" />
    </div>

    <div class="form-row" v-if="mode == 'create'">
      <label for="name">Prénom:</label>
      <input v-model="prenom" class="input" type="text" />
    </div>

    <div class="form-row" v-if="mode == 'create'">
      <label for="telephone">Télèphone:</label>
      <input v-model="telephone" class="input" type="tel" />
    </div>

    <div class="form-row" >
      <label for="title">Adresse mail:</label>
      <input v-model="email" class="input" type="email" />
    </div>

    <div class="form-row">
      <label for="title">Mot de passe:</label>
      <input v-model="password" class="input" type="password" />
    </div>
    <div class="form-row" v-if="mode == 'login' && status == 'error_login'">
      Adresse mail et/ou mot de passe invalide
    </div>
    <div class="form-row" v-if="mode == 'create' && status == 'error_create'">
      Adresse mail deja utilisée
    </div>
    <!-- boutons validation login -->
    <div v-if="mode == 'login'">
      <button
        @click="connect()"
        class="button rounded-lg button-disabled connexion"
      >
        <span v-if="status == 'loading'">Connexion en cours...</span>
        <span v-else>Se connecter</span>
      </button>
    </div>
    <!-- bontons register -->
    <div class="form-row" v-else>
      <button
        @click="createAccount()"
        class="button rounded-lg button-disabled connexions"
      >
        <span v-if="status == 'loading'">Connexion en cours...</span>
        <span v-else> S'inscrire </span>
      </button>
    </div>

  </div>
</template>
<style>
.card {
    border: 1px solid;
    width: 40%;
    text-align: center;
    padding: 1%;
    margin-left: 31%;
    margin-top: 168px;
}
.card_title {
    font-size: 29px;

}
input {
    border: 1px solid;
}
label
{
    display: block;
}
button {
    border:1px solid;
    padding: 1% !important;
    margin-bottom: 1% !important;
    margin-top: 1% !important;
    }
</style>
