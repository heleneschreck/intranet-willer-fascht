<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      souvenirs: [],
      fileData: "",
      fileValidate: "",
      mode: "nodisplay",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    let fetched_souvenirs = await fetch("http://localhost:8000/api/souvenirs");
    this.souvenirs = await fetched_souvenirs.json();
    console.table(this.souvenirs);
  },
  methods: {
    onFileSelected(event) {
      // Récupérer le fichier sélectionné par l'utilisateur
      const selectedFile = event.target.files[0];

      // Mettre à jour les données ou faire ce que vous voulez avec le fichier
      // par exemple, vous pouvez l'assigner à une variable du modèle
      this.fileData = selectedFile;
      this.fileValidate = "Image téléchargée";
      this.mode = "displayajoutpartenaire";
      return this.fileValidate;
    },
    add_support: function () {
      console.log(this.fileData);
      console.log(this.title);
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", this.fileData); // Utilisez directement "selectedFile" ici

      formdata.append("title", this.title);
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: formdata,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/souvenirs", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },

    delete_partenaire: function (souvenir) {
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,

        redirect: "follow",
      };
      let url = "http://localhost:8000/api/souvenirs/" + souvenir.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    not_display: function () {
      this.mode = "nodisplay";
    },
    display_delete: function () {
      this.mode = "display_delete";
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourtodo">Retour</button>
  </router-link>
  <h1>Souvenirs</h1>
  <div class="album">
    <div v-for="souvenir in souvenirs">
      <div
        class="supprimerphotosouvenirs"
        @click="delete_partenaire(souvenir)"
        v-if="mode == 'display_delete'"
      >
        <img
          src="https://cdn-icons-png.flaticon.com/128/9470/9470716.png"
          class="illustrationSuppressionPartenaire"
          alt=""
        />
      </div>
      <div
        class="block max-w-[26rem] rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 listessouvenirs"
      >
        <div
          class="relative overflow-hidden bg-cover bg-no-repeat"
          @mouseover="display_delete()"
        >
          <img
            class="rounded-t-lg photosouvenirs"
            v-bind:src="souvenir.url"
            alt=""
          />
        </div>
        <hr />
        <div class="p-3">
          <h3 class="partenaireTitre">
            {{ souvenir.title }}
          </h3>
        </div>
      </div>
    </div>
  </div>
  <br />
  <!-- <button
    class="button rounded-lg ajoutpartenaire"
    @click="display_ajoutpartenaire()"
  >
    Ajouter un partenaire
  </button> -->

  <div class="ajoutdepartenaire" @mouseover="not_display()">
    <input
      v-model="title"
      placeholder="titre"
      class="inputtitlepartenaire"
      type="text"
    />
    <br />

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
    <button @click="add_support()" class="addpartenaire">Valider</button>
  </div>
</template>
<style>
.equipe {
  background-color: hsla(0, 0%, 84%, 0.3);
}

.listessouvenirs {

  margin-left: 25px;
  box-shadow: 12px 15px 25px black !important;
  width: 100%;
  height: 380px !important;
  align-content: center; 
 
}
.photosouvenirs {

  display: block;
  margin-left: auto;
  margin-right: auto;
height: 300px;
}
.album {
  display: flex;
  flex-wrap: wrap;
}
.supprimerphotosouvenirs {
  position: relative;
  top: 45px;
  left: 400px;
  z-index: 1;
}
.supprimerphotosouvenirs:hover {
  cursor: pointer;
}
.illustrationSuppressionPartenaire {
  width: 35px;
}
.partenaireTitre {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
}
.ajoutpartenaire {
  background-color: white;
  margin-left: 45%;
  font-weight: bold;
  box-shadow: 12px 15px 25px black;
}
.ajoutdepartenaire {
  background-color: white;
  padding-top: 20px;
  padding-left: 10px;
  padding-right: 10px;
}
.inputtitlepartenaire {
  margin-bottom: 15px;
  border-color: hsla(0, 0%, 84%, 0.3);
  margin-left: 41%;
}
.addpartenaire {
  /* margin-left: auto; */
  border-radius: 20px;
  margin-left: 47%;
  width: 150px;
  border: 5px solid green;
  background-color: green;
  color: white;
  font-weight: bold;
  font-size: 20px;
}
</style>
