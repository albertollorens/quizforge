export default {
  title: "Quiz de prova Moodle Import",
  course: "2025-2026",
  group: "1r DAW",
  description: "Quiz de prova amb tots els tipus de preguntes",

  questions: [

    // =========================
    // SINGLECHOICE
    // =========================
    {
      type: "singlechoice",
      title: "Capital d'Espanya",
      statement: "Quina és la capital d'Espanya?",
      answers: [
        { text: "Madrid", correct: true, weight: 100 },
        { text: "Barcelona", correct: false, weight: 0 },
        { text: "València", correct: false, weight: 0 },
        { text: "Sevilla", correct: false, weight: 0 }
      ]
    },

    // =========================
    // MULTICHOICE
    // =========================
    {
      type: "multichoice",
      title: "Llenguatges de programació",
      statement: "Selecciona llenguatges de programació",
      answers: [
        { text: "JavaScript", correct: true, weight: 50 },
        { text: "PHP", correct: true, weight: 50 },
        { text: "HTML", correct: false, weight: -50 },
        { text: "CSS", correct: false, weight: -50 }
      ]
    },

    // =========================
    // TRUE FALSE
    // =========================
    {
      type: "truefalse",
      title: "HTML és un llenguatge de programació",
      statement: "HTML és un llenguatge de programació",
      answers: [
        { text: "True", correct: false },
        { text: "False", correct: true }
      ]
    },

    // =========================
    // SHORTANSWER
    // =========================
    {
      type: "shortanswer",
      title: "Capital de França",
      statement: "Quina és la capital de França?",
      answers: [
        { text: "Paris", correct: true },
        { text: "París", correct: true }
      ]
    },

    // =========================
    // ESSAY
    // =========================
    {
      type: "essay",
      title: "Explica què és Vue.js",
      statement: "Explica el funcionament bàsic de Vue.js"
    },

    // =========================
    // MATCHING
    // =========================
    {
      type: "matching",
      title: "Relaciona conceptes",
      statement: "Relaciona els següents conceptes sobre llenguatges de DW",
      answers: [
        { text: "Client", match_pair: "Javascript" },
        { text: "Servidor", match_pair: "PHP" }
      ]
    }
  ]
}