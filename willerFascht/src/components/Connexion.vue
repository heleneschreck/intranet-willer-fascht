<script>
import { mapState } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      mode: "login",
      value: 0,
      name: "",
      prenom: "",
      email: "",
      telephone: "",
      password: "",
      errors: "",
      loading: false,
    };
  },
  computed: {
    ...mapState(["status"]),
  },
  async beforeMount() {},

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
          prenom: this.prenom,
          telephone: this.telephone,
          email: this.email,
          password: this.password,
          niveau: "0",
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
  <router-link :to="`/`">
    <button class="button rounded-lg retouraccueil">Retour</button>
  </router-link>
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

    <div class="form-row">
      <label for="title">Adresse mail:</label>
      <input v-model="email" class="input" type="email" />
    </div>

    <div class="form-row">
      <label for="title">Mot de passe:</label>
      <input v-model="password" class="input" type="password" />
    </div>
    <div class="form-row" v-if="mode == 'login' && status == 'error_login'">
      

<div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
  <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info </span>
  <div>
    &nbsp;  Adresse mail et/ou mot de passe invalide
  </div>




 

</div>

     
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
        <span v-if="status == 'loading'"><svg xmlns="http://www.w3.org/2000/svg" height="200px" width="200px" viewBox="0 0 200 200" class="pencil">
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
</svg></span>
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
<style scoped>
.retouraccueil {
  border-radius: 20px;
  margin-left: 100px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white;
  font-weight: bold;
  font-size: 20px;
}
.card {
  box-shadow: 4px 1px 20px 0px black;
  border: 1px solid;
  width: 670px;
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
  margin-left: 23%;
}
label {
  display: block;
}
button {
  border: 1px solid;
  padding: 1% !important;
  margin-bottom: 1% !important;
  margin-top: 1% !important;
}
.connexion {
  border-radius: 20px;
  border: 5px solid green;
  background-color: green;
  color: white;
  font-weight: bold;
  font-size: 20px;
}
.pencil {
  display: block;
  width: 10em;
  height: 10em;
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
