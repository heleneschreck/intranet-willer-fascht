<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
const route = useRoute();

export default {
  data() {
    return {
      mode: false,
      articles: [],
      title: "", // Add this line if 'title' is used in the template
      content: "", // Add this line if 'content' is used in the template
      color: null,
      lettre: null,
      italique: null,
      currentFontSize: null, //
    };
  },

  async beforeMount() {
    let fetched_articles = await fetch("http://127.0.0.1:8000/api/articles/");
    this.articles = await fetched_articles.json();
    console.table(this.articles);
  },

  methods: {
    getColor(article) {
      return article.color;
    },
    getSize(article) {
      return article.size;
    },
    getWeight(article) {
      return article.weight;
    },
    getItalique(article) {
      return article.italique;
    },
    gras() {
      // console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.fontWeight = "bold";
      var p = document.querySelector("textarea").style;
      // console.log(p);
      this.lettre = "bold";
      console.log(this.weight);
      this.mode = "bold";
    },
    weight() {
      console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.fontWeight = "normal";
      var p = document.querySelector("textarea").style;
      console.log(p);
      this.lettre = "normal";
      this.mode = "false";
    },
    italic() {
      console.log(document.querySelector("textarea"));
      const p = (document.querySelector("textarea").style.fontStyle = "italic");
      const element = document.querySelector("textarea").value;
      this.italique = "italic";
      this.mode = "normal";
      console.log(element);
    },
    normal() {
      console.log(document.querySelector("textarea"));
      const p = (document.querySelector("textarea").style.fontStyle = "normal");
      const element = document.querySelector("textarea").value;
      this.italique = "normal";
      this.mode = "false";
      console.log(element);
    },

    choosecolor() {
      console.log(this.color);
      const textarea = document.querySelector("textarea");
      textarea.style.color = this.color;
    },

    augmenter() {
      var currentFontSize = parseFloat(
        document.querySelector("textarea").style.fontSize
      );
      console.log(currentFontSize);

      // Incrémentation de la taille de la police
      currentFontSize += 1;

      // Appliquer la nouvelle taille de police à l'élément textarea
      document.querySelector("textarea").style.fontSize =
        currentFontSize + "px";
      console.log(currentFontSize);
      this.newcurrentFontSize = this.currentFontSize.toString() + "px";

      document.querySelector("textarea").fontSize = this.newcurrentFontSize;
    },
    diminuer() {
      var currentFontSize = parseFloat(
        document.querySelector("textarea").style.fontSize
      );
      console.log(currentFontSize);

      currentFontSize -= 1;
      // Appliquer la nouvelle taille de police à l'élément textarea
      document.querySelector("textarea").style.fontSize =
        currentFontSize + "px";
      console.log(currentFontSize);
      this.newcurrentFontSize = this.currentFontSize.toString() + "px";

      document.querySelector("textarea").fontSize = this.newcurrentFontSize;
      console.log(this.newcurrentFontSize);
      console.log(this.currentFontSize);
    },
    updatePost(article) {
      this.taille = document.querySelector("textarea").style.fontSize;
      console.log(this.title);
      console.log(article.content);
      console.log(article.italique);
      console.log(article.color);
      console.log(this.taille);

      if (this.lettre) {
        console.log(this.lettre);
      } else {
        console.log("le texte n'a pas de style gras");
        this.lettre = article.weight;
      }
      console.log(this.lettre);

      if (this.italique) {
        console.log(this.italique);
      } else {
        console.log("le texte n'a pas de style italique");
        this.italique = article.italique;
      }
      console.log(this.italique);

      var urlencoded = new URLSearchParams();
      urlencoded.append("title", article.title);
      urlencoded.append("content", article.content);
      urlencoded.append("color", article.color);
      urlencoded.append("size", this.taille);
      urlencoded.append("weight", this.lettre);
      urlencoded.append("italique", this.italique);
      //   urlencoded.append("user_id", this.user.id);
      var requestOptions = {
        method: "PUT",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/articles/" + this.$route.params.id;
      fetch(url, requestOptions)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json(); // Parse the response body as JSON
        })
        .then((data) => {
      
          setTimeout(() => {
        this.$router.push({ path: "/illustrations/"+this.$route.params.id});
      }, "2000");
        })
        .catch((error) => console.error("Error:", error));
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourtodo">Retour</button>
  </router-link>
  <h1>Mise en ligne :</h1>

  <div v-for="article in articles">
    <div v-if="article.id == $route.params.id">
      <div id="form">
        <input
          v-model="article.title"
          type="text"
          name="title"
          id="title"
          placeholder="title"
        />
        <br />
        <div class="options">
          <button
            @click="weight()"
            v-if="article.weight == 'bold' || mode == 'bold'"
            class="gras"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/5456/5456895.png"
              class="optiontext"
            />
          </button>

          <button @click="gras()" v-else class="gras">
            <img
              src="https://cdn-icons-png.flaticon.com/512/114/114304.png"
              class="optiontext"
            />
          </button>
          <button
            @click="normal()"
            v-if="article.italique == 'italic' || mode == 'normal'"
            class="italic"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/128/5456/5456769.png"
              class="optiontext"
            />
          </button>
          <button @click="italic()" v-else class="italic">
            <img
              src="https://cdn-icons-png.flaticon.com/512/142/142351.png"
              class="optiontext"
            />
          </button>
          <button class="souligner">
            <input
              type="color"
              @input="choosecolor()"
              v-model="article.color"
              class="optiontext colorText"
            />
          </button>

          <button @click="augmenter()" class="augmenter">
            <img
              src="https://cdn-icons-png.flaticon.com/512/1617/1617523.png"
              class="optiontext"
            />
          </button>
          <button @click="diminuer()" class="diminuer">
            <img
              src="https://cdn-icons-png.flaticon.com/512/690/690338.png"
              class="optiontext"
            />
          </button>
        </div>

        <textarea
          name="content"
          v-model="article.content"
          placeholder="Post"
          id="content"
          cols="30"
          rows="5"
          :style="{
            color: getColor(article),
            'font-size': getSize(article),
            'font-weight': getWeight(article),
            'font-style': getItalique(article),
          }"
        ></textarea>
        <br />
        <!-- </div> -->
        <button
          @click="updatePost(article)"
          class="button btn-block validerarticle"
        >
          Suivant
          <img
            src="https://cdn-icons-png.flaticon.com/128/3588/3588232.png"
            style="width: 55px; float: right; margin-left: 10px"
            alt=""
          />
        </button>
      </div>
    </div>
  </div>
</template>
<style scoped>
.updaterendezvous {
  /* margin-top: 8% !important; */
  width: 90%;
  margin: auto;
}
</style>
