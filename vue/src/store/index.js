import {createStore} from "vuex";
import axiosClient from "../axios";

const tmpSurveys = [
{
  id: 100,
  title: "TheCodeholic Youtube channel content",
  slug: "thecodeholic-youtube-channel-content",
  status: "draft",
  image: "https://pbs.twimg.com/profile_images/1567960261205655552/ceDxXqVS_400x400.jpg",
  description: "My name Zura. <br>I am web developer with 9+ years of experience, free educational con",
  created_at: "2021-12-20 18:00:00",
  updated_at: "2021-12-20 18:00:00",
  expire_date: "2021-12-20 18:00:00",
  questions: [
    {
      id: 1,
      type: "select",
      question: "From which country are you?",
      description: null,
      data: {
        options: [
          {
            uuid: "8d602fb0-3617-11ed-a261-0242ac120002",
            text: "USA",
          },
          {
            uuid: "8d60328a-3617-11ed-a261-0242ac120002",
            text: "Georgia",
          },
          {
            uuid: "8d6033ac-3617-11ed-a261-0242ac120002",
            text: "Germany",
          },
          {
            uuid: "8d6037a8-3617-11ed-a261-0242ac120002",
            text: "India",
          },
          {
            uuid: "8d6038c0-3617-11ed-a261-0242ac120002",
            text: "Nigeria",
          },
          {
            uuid: "8d6039ba-3617-11ed-a261-0242ac120002",
            text: "Canada",
          },
        ]
      },
    },
    {
      id: 2,
      type: "checkbox",
      question: "Which language videos do you want to see on my channel?",
      description: null,
      data: {
        options: [
          {
            uuid: "8a218f4a-3650-11ed-a261-0242ac120002",
            text: "Javascript",
          },
          {
            uuid: "8a2191de-3650-11ed-a261-0242ac120002",
            text: "Laravel",
          },
          {
            uuid: "8a21931e-3650-11ed-a261-0242ac120002",
            text: "Codeigniter",
          },
          {
            uuid: "8a219440-3650-11ed-a261-0242ac120002",
            text: "Symfony",
          },
          {
            uuid: "8a219562-3650-11ed-a261-0242ac120002",
            text: "Python",
          },
          {
            uuid: "8a21965c-3650-11ed-a261-0242ac120002",
            text: "Ruby",
          },
        ]
      },
    },
    {
      id: 4,
      type: "radio",
      question: "Which laravel framework do you love most?",
      description: "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
      data: {
        options: [
          {
            uuid: "225c4f0c-3651-11ed-a261-0242ac120002",
            text: "Laravel 6",
          },
          {
            uuid: "225c50d8-3651-11ed-a261-0242ac120002",
            text: "Laravel 5",
          },
          {
            uuid: "225c51c8-3651-11ed-a261-0242ac120002",
            text: "Laravel 4",
          },
          {
            uuid: "225c52a4-3651-11ed-a261-0242ac120002",
            text: "Laravel 3",
          },
          {
            uuid: "225c5380-3651-11ed-a261-0242ac120002",
            text: "Laravel 2",
          },
          {
            uuid: "225c55c4-3651-11ed-a261-0242ac120002",
            text: "Laravel 1",
          },
        ]
      },
    },
    {
      id: 5,
      type: "checkbox",
      question: "What type of projects do you want ?",
      description: "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
      data: {
        options: [
          {
            uuid: "bb6ec771-3f5d-42d3-836d-4c85bae815eb",
            text: "REST API",
          },
          {
            uuid: "557cf80f-7bc4-48dc-afb2-3708f93f4a8f",
            text: "E-commerce",
          },
          {
            uuid: "a6a8cbf0-a024-41e9-bc11-0858cca1068d",
            text: "Real Estate",
          },
          {
            uuid: "ce6a3088-7cb0-42df-969d-89fdda0bad8e",
            text: "All of the above",
          },
        ]
      },
    },
    {
      id: 6,
      type: "text",
      question: "What your favorite youTube channel?",
      description: null,
      data: {},
    },
    {
      id: 7,
      type: "textarea",
      question: "What do your think about TheCodeholic channel?",
      description: "Write your honest opinion. Everything is anonymous.",
      data: {},
    },
  ],
},
{
  id: 200,
  title: "Laravel 8",
  slug: "laravel-8",
  status: "active",
  image: "https://miro.medium.com/max/1400/1*m0s2io11J82PR7miqan92w.png",
  description: "Laravel is a web application. <br>I am web developer with 9+ years of experience, free educational con",
  created_at: "2021-12-20 18:00:00",
  updated_at: "2021-12-20 18:00:00",
  expire_date: "2021-12-20 18:00:00",
},
{
  id: 300,
  title: "Vue 3",
  slug: "vue-3",
  status: "active",
  image: "https://miro.medium.com/max/1400/1*vXVBfNK-0eI4f0BrwKjEwg.jpeg",
  description: "Vue is a progressive framework for building. <br>I am web developer with 9+ years of experience, free educational con",
  created_at: "2021-12-20 18:00:00",
  updated_at: "2021-12-20 18:00:00",
  expire_date: "2021-12-20 18:00:00",
},
{
  id: 400,
  title: "Tailwind 3",
  slug: "tailwind-3",
  status: "active",
  image: "https://laravelnews.imgix.net/images/tailwindcss-1633184775.jpg?ixlib=php-3.3.1",
  description: "A utility-first CSS framework packed with class like <code>flex</code>. <br>I am web developer with 9+ years of experience, free educational con",
  created_at: "2021-12-20 18:00:00",
  updated_at: "2021-12-20 18:00:00",
  expire_date: "2021-12-20 18:00:00",
},
];

axiosClient
const store = createStore({
    state:{
        user:{
        data: {},
        token: sessionStorage.getItem("TOKEN")},
        surveys: [...tmpSurveys],
        questionTypes: ["text","select","radio","checkbox","textarea"]
    },
    getters:{},
    actions:{
      register({commit}, user){
        return axiosClient.post('/register', user)
        .then(({data}) => {
          commit('setUser', data);
          return data;
        })
      },

      login({commit}, user){
        return axiosClient.post('/login', user)
        .then(({data}) => {
          commit('setUser', data);
          return data;
        })
      },

      logout({commit}){
        return axiosClient.post('/logout').then(response=>{
          commit('logout')
          return response;
        })
      }
    },
    mutations:{
        logout: (state) =>{
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem('TOKEN')

        },
        setUser: (state, userData) => {
          state.user.token = userData.token;
          state.user.data = userData.user;
          sessionStorage.setItem('TOKEN', userData.token);

        }
    },
    modules:{}
})

export default store;
