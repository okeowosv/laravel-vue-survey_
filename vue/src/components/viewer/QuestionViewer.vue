<template>
  <div>
    <fieldset class="mb-4">
    <div>
      <legend class="text-base font-medium text-gray-900">
        {{index+1}}. {{question.question}}
      </legend>
      <p class="text-gray-500 text-sm">
        {{question.description}}
      </p>
    </div>
    <div class="mt-3">
      <div v-if="question.type === 'select'">
        <select :value="modelValue" @change="emits('update:modelValue', $event.target.value)" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
        <option value="">Please select</option>
        <option v-for="option in question.data.options" :value="option.text" :key="option.uuid">{{option.text}}</option>
        </select>
      </div>
      <div v-else-if="question.type === 'radio'">
        <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
          <input :id="option.uuid" :name="'question'+question.id" :value="option.text" @change="emits('update:modelValue', $event.target.value)" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
          <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">{{option.text}}</label>
        </div>
      </div>
      <div v-else-if="question.type === 'checkbox'">
        <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
          <input :id="option.uuid" v-model="model[option.text]" :name="'question'+question.id" :value="option.text" @change="onCheckboxChange" type="checkbox" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
          <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">{{option.text}}</label>
        </div>
      </div>
      <div v-else-if="question.type === 'text'">
      <input type="text" :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">

      </div>
      <div v-else-if="question.type === 'textarea'">
      <textarea :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"></textarea>
      </div>
    </div>
    </fieldset>
    <hr class="mb-4">
  </div>
</template>
<script setup>
import {ref} from "vue";

const {question, index, modelValue} = defineProps({
  question: Object,
  index: Number,
  modelValue: [String, Array],
});
const emits = defineEmits(["update:modelValue"]);

let model;
if(question.type === "checkbox"){
  model = ref({});
}

function onCheckboxChange($e) {
  const selectedOptions = [];
  for(let text in model.value){
    if(model.value[text]){
      selectedOptions.push(text);
    }
  }
  emits('update:modelValue', selectedOptions);
}
</script>
<style lang="">

</style>
