<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();

export default {
  data() {
    return {
      route: route,
      illustrations: [],
      articles: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");

    let fetched_articles = await fetch(
      "http://127.0.0.1:8000/api/articles/user/" + this.user.id
    );
    this.articles = await fetched_articles.json();
    console.table(this.articles);

    let fetched_illustrations = await fetch(
      "http://127.0.0.1:8000/api/illustrations/"
    );
    this.illustrations = await fetched_illustrations.json();
    console.table(this.illustrations);
  },
  methods: {},
};
</script>
<template>
  <h1>Liste des articles dont je suis l'auteur</h1>
  <div class="listedemesarticles">
    <div v-for="article in articles">
      <div class="mesarticle">
        <div class="articleCard">
          <div class="articleCardTitle">
            {{ article.title }}
          </div>
          <div class="articleCardContent">
            {{ article.content }}
          </div>
        </div>
        <!-- {{ article }} -->
        
        <div class="articleIllustration">
        <div v-for="illustration in illustrations">
          <div v-show="illustration.article_id == article.id">
            <!-- {{ illustration }} -->
          

             <img
             v-bind:src="illustration.url" class="illustrationarticle"
             
             />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.mesarticle {
  border: 1px solid black;
  width: 400px;
  margin-left: 10px;
  margin-bottom: 5px;
  height: 300px;
}
.listedemesarticles {
  display: flex;
  flex-wrap: wrap;
  margin-left: 10px;
  margin-right: 10px;
}
.articleIllustration{
  
  display: flex;
}
.illustrationarticle{
  width: 140px;
  height: 100px;
}
.articleCardTitle{
  text-align: center;
  font-size: 25px;
  font-weight: bold;
}
</style>
