<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();

export default {
  data() {
    return {
      route: route,
      fileData: "",
      fileValidate: "",
      illustrations: [],
   
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {

    this.user = JSON.parse(localStorage.getItem("user") || "[]");

    let fetched_illustrations = await fetch("http://127.0.0.1:8000/api/illustrations");
    this.illustrations = await fetched_illustrations.json();
    console.table(this.illustrations);


    
  },
  methods: {
    onFileSelected(event) {
      // Récupérer le fichier sélectionné par l'utilisateur
      const selectedFile = event.target.files[0];

      // Mettre à jour les données ou faire ce que vous voulez avec le fichier
      // par exemple, vous pouvez l'assigner à une variable du modèle
      this.fileData = selectedFile;
      this.fileValidate = "Image téléchargée";
      return this.fileValidate;
    },

    add_support: function () {
      console.log(this.fileData);

      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", this.fileData); // Utilisez directement "selectedFile" ici

      formdata.append("article_id", this.$route.params.id);
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: formdata,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/illustrations", requestOptions)
        .then((response) => response.text())
        .then((result) => {
            console.log(result)
            this.chargerIllustrations();
        })
        .catch((error) => console.log("error", error));
    //   setTimeout(() => {
    //     this.$router.go(this.$router.currentRoute);
    //   }, "2000");
    },

    chargerIllustrations() {
      fetch("http://localhost:8000/api/illustrations")
        .then((response) => response.json())
        .then((data) => {
          // Mettre à jour les données du composant avec les nouvelles données de l'API
          this.illustrations = data;
        })
        .catch((error) =>
          console.error(
            "Erreur lors du chargement des candidats depuis l'API",
            error
          )
        );
    },
  },
};
</script>
<template>
  <h1>Ajouter des photos à notre publication :</h1>
  <div class="ajoutdepartenaire" @mouseover="not_display()">
    <div class="flex items-center justify-center w-full">
      <label
        for="dropzone-file"
        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
      >
        <div
          class="flex flex-col items-center justify-center pt-5 pb-6 inputImage"
        >
          <svg
            class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 20 16"
          >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
            />
          </svg>
          <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-semibold"> Cliquez pour télécharger </span>
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400">
            JPEG, PNG, JPG (MAX. 800x400px)
          </p>
          {{ fileValidate }}
        </div>
        <input
          id="dropzone-file"
          type="file"
          @change="onFileSelected"
          name="image"
          accept=".jpg, .jpeg, .png"
          class="hidden"
        />
      </label>
    </div>

    <br />
    <div class="validerpost">
      <router-link :to="`/accueilintra`">
        <button class="validerpostsansillustrations">
          Article terminé
          <img
            src="https://cdn-icons-png.flaticon.com/128/4009/4009127.png"
            alt=""
            class="imagesortir"
          />
        </button>
      </router-link>
      <button @click="add_support()" class="addillustrations">Ajouter</button>
    </div>

    <hr />
    <br />
    <div class="illustrationArticle">
      <div v-for="illustration in illustrations" class="listeIllustration">
        <div v-show="illustration.article_id == $route.params.id">
          <img v-bind:src="illustration.url" class="illustrationListe" alt="" />
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.validerpost {
  display: inline-flex;
  justify-content: space-between;
  width: 90%;
  margin-left: -3%;
}
.addillustrations {
  border-radius: 20px;
  margin-left: 47%;
  /* width: 150px; */
  border: 5px solid green;
  background-color: green;
  color: white;
  font-weight: bold;
  font-size: 20px;
}
.validerpostsansillustrations {
  border-radius: 20px;
  margin-left: 47%;
  width: 250px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white;
  font-weight: bold;
  font-size: 20px;
}
.imagesortir {
  width: 45px;
  float: right;
}
.illustrationListe {
  width: 315px;
  height: 400px;
  margin-right: 20px;
}
.illustrationArticle{
    display: flex;
}
</style>
