import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AccueilView from '../views/AccueilView.vue'
import NavbarsView from '../views/NavbarsView.vue'
import ConnexionView from '../views/ConnexionView.vue'
import AccueilintraView from '../views/AccueilintraView.vue'
import PartenairesView from '../views/PartenairesView.vue'
import AffichesView from '../views/AffichesView.vue'
import CompteRenduView from '../views/CompteRenduView.vue'
import DetailView from '../views/DetailView.vue'
import AddCompteRenduView from '../views/AddCompteRenduView.vue'
import TodoView from '../views/TodoView.vue'
import CreateprojectView from '../views/CreateprojectView.vue'
import ProjetView from '../views/ProjetView.vue'
import AjoutTacheView from '../views/AjoutTacheView.vue'
import TacheView from '../views/TacheView.vue'
import UpdateTacheView from '../views/UpdateTacheView.vue'
import PlanningView from '../views/PlanningView.vue'
import MesinfosView from '../views/MesinfosView.vue'
import UpdateinfosView from '../views/UpdateinfosView.vue'
import ConfirmeUpdateView from '../views/ConfirmeUpdateView.vue'
import MembresView from '../views/MembresView.vue'
import CreatemembresView from '../views/CreatemembresView.vue'
import MessagerieView from '../views/MessagerieView.vue'
import AddEventView from '../views/AddEventView.vue'
import ListeView from '../views/ListeView.vue'
import RendezvousView from '../views/RendezvousView.vue'
import ParticipantsView from '../views/ParticipantsView.vue'
import UpdateRendezvousView from '../views/UpdateRendezVousView.vue'
import UpdateniveauView from '../views/UpdateniveauView.vue'
import InscriptionView from '../views/InscriptionView.vue'
import UpdateInscriptionView from '../views/UpdateInscriptionView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/',
      name: 'accueil',
      component: AccueilView
    },
    {
      path: '/connexion',
      name: 'connexion',
      component: ConnexionView
    },
    {
      path: '/accueilintra',
      name: 'accueilintra',
      component: AccueilintraView
    },
    {
      path: '/partenaires',
      name: 'partenaires',
      component: PartenairesView
    },
    {
      path: '/affiches',
      name: 'affiches',
      component: AffichesView
    },
    {
      path: '/compterendu',
      name: 'compterendu',
      component: CompteRenduView
    },
    {
      path: '/detail/:compterendu',
      name: 'detail',
      component: DetailView
    },
    {
      path: '/addcompterendu',
      name: 'addcompterendu',
      component: AddCompteRenduView
    },
    {
      path: '/todo',
      name: 'todo',
      component: TodoView
    },
    {
      path: '/projet/:projet',
      name: 'projet',
      component: ProjetView
    },
    {
      path: '/ajoutTache/:projet/:statut',
      name: 'ajoutTache',
      component: AjoutTacheView
    },
    {
      path: '/updateTache/:projet/:tache',
      name: 'updateTache',
      component: UpdateTacheView
    },
    {
      path: '/tache/:tache',
      name: 'tache',
      component: TacheView
    },
    {
      path: '/createproject',
      name: 'createproject',
      component: CreateprojectView
    },
    {
      path: '/navbars',
      name: 'navbars',
      component: NavbarsView
    },
    {
      path: '/planning',
      name: 'planning',
      component: PlanningView
    },
    
    {
      path: '/addEvent',
      name: 'addEvent',
      component: AddEventView
    },
    {
      path: '/liste',
      name: 'liste',
      component: ListeView
    },
    {
      path: '/updaterendezvous/:evenement',
      name: 'updaterendezvous',
      component: UpdateRendezvousView
    },
    {
      path: '/inscription/:evenement',
      name: 'inscription',
      component: InscriptionView
    },
    {
      path: '/participants/:evenement',
      name: 'participants',
      component: ParticipantsView
    },
   
    {
      path: '/updateinscription/:participant',
      name: 'updateinscription',
      component: UpdateInscriptionView
    },
    {
      path: '/rendezvous/:evenement',
      name: 'rendezvous',
      component: RendezvousView
    },
  
    {
      path: '/messagerie',
      name: 'messagerie',
      component: MessagerieView
    },
    {
      path: '/mesinfos',
      name: 'mesinfos',
      component: MesinfosView
    },
    {
      path: '/membres',
      name: 'membres',
      component: MembresView
    },
    {
      path: '/createmembres',
      name: 'createmembres',
      component: CreatemembresView
    },
    {
      path: '/updateinfos/:user',
      name: 'updateinfos',
      component: UpdateinfosView
    },
    {
      path: '/updateniveau/:membre',
      name: 'updateniveau',
      component: UpdateniveauView
    },
    {
      path: '/confirmeupdate',
      name: 'confirmeupdate',
      component: ConfirmeUpdateView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    }
  ]
})

export default router
