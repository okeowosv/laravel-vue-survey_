<template>
<div>
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">{{index + 1}}. {{model.question}}</h3>
    <!-- Question index -->
    <div class="flex items-center">
      <!-- Add new question -->
      <button type="button" @click="addQuestion()" class="flex items-center text-sm py-1 px-4 m-2 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

        Add
      </button>
      <!-- Add new question -->

      <!-- Delete question -->
      <button type="button" @click="deleteQuestion()" class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>

        Delete
      </button>
      <!-- Delete question -->
    </div>
  </div>
      <!-- Question index -->
  <div class="grid grid-cols-12 mb-3 ">
      <!-- Question -->
      <div class="mt-3 col-span-9">
        <label :for="'question_text_' + model.data" class="block text-sm font-medium text-gray-700">
          Question Text
        </label>
        <input type="text"
        :name="'question_text_' + model.data"
        v-model="model.question"
        @change="dataChange"
        :id="'question_text_' + model.data"
        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        />
      </div>
      <!-- Question -->

      <!-- Question type -->
      <div class="mt-3 pl-3 col-span-3">
        <label :for="question_type" class="block text-sm font-medium text-gray-700">
          Select Question Type
        </label>
        <select
        id="question_type"
        name="question_type"
        v-model="model.type"
        @change="typeChange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

        <option v-for="type in questionTypes" :key="type" :value="type">
          {{upperCaseFirst(type)}}
        </option>


        </select>
      </div>
      <!-- Question type -->
  </div>
  <!-- Question description -->
  <div class="mt-3 col-span-9">
        <label :for="'question_description_' + model.id" class="block text-sm font-medium text-gray-700">
          Description
        </label>
        <textarea :name="'question_description_'+model.id" v-model="model.description" @change="dataChange" :id="'question_description_'+model.id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
  </div>
  <!-- Data -->
  <!-- add new option -->
  <div v-if="shouldHaveOptions()" class="mt-2">
    <h4 class="text-sm font-semibold mb-1 flex justify-between items-center">Options
    <button type="button" @click="addOption()" class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
     Add Option
    </button>
    </h4>

    <div v-if="!model.data.options.length" class="text-xs text-gray-600 text-center py-3">
      You don't have any options defined
    </div>
    <!-- Option list -->
    <div v-for="(option, index) in model.data.options" :key="option.uuid" class="flex items-center mb-1">
      <span class="w-6 text-sm">{{index + 1}}. </span>
      <input type="text" v-model="option.text" @change="dataChange" class="w-full rounded-sm py-1 px-2 text-xs border border-gray-300 focus:border-indigo-500">
      <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-300">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
      </button>
    </div>
    <!-- Option list -->
  </div>
  <!-- add new option -->
  <!-- Data -->
  <hr class="my-4">
</div>
</template>

<script setup>
import {ref, computed} from "vue";
import {v4 as uuidv4} from "uuid";
import store from "../../store";

const props = defineProps({
  question: Object,
  index: Number,
});
const emit = defineEmits(["change","addQuestions","deleteQuestion"]);
// Recreate the whole question datat to avoid unintentional reference change
const model = ref(JSON.parse(JSON.stringify(props.question)));
// Get question types from vuex
const questionTypes = computed(()=>store.state.questionTypes);

function upperCaseFirst(str){
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function shouldHaveOptions(){
  return ["select","radio","checkbox"].includes(model.value.type);
}

function getOptions(){
  return model.value.data.options;
}

function setOptions(options){
   model.value.data.options = options;
}

function addOption(){
  console.log(getOptions());
  setOptions([...getOptions(), {uuid: uuidv4(), text: ""},
  ]);
  dataChange();
}

function removeOption(op){
  setOptions(getOptions().filter((opt) => opt !== op));
  dataChange();
}

function typeChange(){
  if(shouldHaveOptions()){
    setOptions(getOptions()||[]);
  }
  dataChange();
}

function dataChange(){
  const data = JSON.parse(JSON.stringify(model.value));
  if(!shouldHaveOptions()){
    delete data.data.options;
  }

  emit("change", data);
}

function addQuestion(){
  emit('addQuestion', props.index + 1);
}

function deleteQuestion(){
  emit('deleteQuestion', props.question);
}
</script>
<style>

</style>
