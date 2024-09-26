<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      partenaires: [],
      fileData: "",
      fileValidate: "",
      mode: "nodisplay",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    let fetched_partenaires = await fetch(
      "http://localhost:8000/api/partenaires"
    );
    this.partenaires = await fetched_partenaires.json();
    console.table(this.partenaires);
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

      fetch("http://localhost:8000/api/partenaires", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },

    delete_partenaire: function (partenaire) {
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,

        redirect: "follow",
      };
      let url = "http://localhost:8000/api/partenaires/" + partenaire.id;
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
  <!-- <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourtodo">Retour</button>
  </router-link> -->
  <h1>Liste des partenaire de l'association :</h1>
  <div class="listedespartenaires">
    <div v-for="partenaire in partenaires">
      <div
        class="supprimerpartenaire"
        @click="delete_partenaire(partenaire)"
        v-if="mode == 'display_delete'"
      >
        <img
          src="https://cdn-icons-png.flaticon.com/128/9470/9470716.png"
          class="illustrationSuppressionPartenaire"
          alt=""
        />
      </div>
      <div
        class="block max-w-[18rem] rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 listespartenaires"
      >
        <div
          class="relative overflow-hidden bg-cover bg-no-repeat"
          @mouseover="display_delete()"
        >
          <img
            class="rounded-t-lg logoPartenaire"
            v-bind:src="partenaire.url"
            alt=""
          />
        </div>
        <hr />
        <div class="p-3">
          <h3 class="partenaireTitre">
            {{ partenaire.title }}
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
      placeholder="Titre"
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
<style scoped>
.retourtodo {
  margin-left: 1%;
  margin-bottom: 1px !important;
  border-radius: 20px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white;
  font-weight: bold;
  font-size: 20px;
  height: 70px;
  margin-bottom: 5%;
}
.partenaires {
  /* margin-top: 4% !important; */
  background-color: hsla(0, 0%, 84%, 0.3) !important;
  padding-bottom: 25px;
}
h1 {
  margin-top: 1% !important;
  margin-bottom: 1% !important;
  background-color: white !important;
  margin-top: -10px !important;
  /* background-color: hsla(0, 0%, 84%, 0.3) !important; */
}
.listespartenaires {
  margin-top: 25px;
  margin-left: 25px;
  box-shadow: 12px 15px 25px black !important;
  width: 500px;
  height: 270px;
  align-content: center;
}
.logoPartenaire {
  display: block;
  margin-left: auto;
  margin-right: auto;
  height: 200px;
}
.listedespartenaires {
  display: flex;
  flex-wrap: wrap;
}
.supprimerpartenaire {
  position: relative;
  top: 66px;
  left: 272px;
  z-index: 1;
}
.supprimerpartenaire:hover {
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
  margin-bottom: 23px;
  text-align: center;
  width: 20%;
  border-radius: 15px;
  border-color: hsla(0, 0%, 84%, 0.3);
  margin-left: 40%;
  box-shadow: 4px 10px 15px black;
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
  margin-bottom: 1% !important;
}
</style>
