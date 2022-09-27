<!-- This example requires Tailwind CSS v2.0+ -->

<template>
<PageComponent>
  <template v-slot:header>
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
      <router-link :to="{name:'SurveyCreate'}" class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 -mt-1 inline-block">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

        Add Survey</router-link>
    </div>
  </template>
    <div v-if="surveys.loading" class="flex justify-items-center">Loading...</div>
    <div  v-else>
      <div class="grid grid-cols-1 gap-3 sm:grid-colds-2 md:grid-cols-3">
        <SurveyListItem
          v-for="(survey, ind) in surveys.data"
          :key="survey.id"
          :survey="survey"
          class="opacity-1 animate-fade-in-down"
          :style="{animationDelay: `${ind * 0.1}s`}"
          @delete="deleteSurvey(survey)"
          />
      </div>
      <div class="flex justify-center mt-5">
        <nav class="relative z-0 inline-flex justify-center rounded-md shadow-sm" aria-label="Pagination">
          <a v-for="(link, i) of surveys.links"
          :key="i" :disabled="!link.url" v-html="link.label" href="#" @click="getForPage(link)" aria-current="page"
          class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
          :class="[link.active
          ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
          i === 0 ? 'rounded-l-lg' : '',
          i === surveys.links.length - 1 ? 'rounded-r-lg' : '',
          ]"></a>
        </nav>
      </div>
    </div>

  </PageComponent>
</template>

<script setup>
import store from "../store";
import {computed} from "vue";
import PageComponent from '../components/PageComponent.vue';
import SurveyListItem from '../components/SurveyListItem.vue';


const surveys = computed(() => store.state.surveys);

store.dispatch('getSurveys');
function deleteSurvey(survey){
  if(confirm('Are you sure you want to delete this survey? Delete cant be undo')){
    store.dispatch('deleteSurvey',survey.id)
    .then(()=>store.dispatch('getSurveys'))
  }
}

function getForPage(link){
  if(!link.url || link.active){
    return;
  }
  store.dispatch('getSurveys', {url:link.url});
}
</script>
<style scoped></style>
