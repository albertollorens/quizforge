// Factory per tal de normalitzar les dades de les preguntes
function baseQuestion(type) {
  return {
    type,
    title: '',
    statement: '',
    answers: []
  }
}

function createAnswer(type) {
  switch (type) {

    case 'singlechoice':
    case 'multichoice':
      return {
        text: '',
        correct: false,
        weight: 0
      }

    case 'shortanswer':
    case 'truefalse':
      return {
        text: '',
        correct: false
      }

    case 'matching':
      return {
        text: '',
        match_pair: ''
      }

    default:
      return null
  }
}

export function createQuestion(type) {

  const question = baseQuestion(type)

  // Essay no té respostes
  if (type === 'essay') {
    question.answers = []
    return question
  }

  // Per defecte afegim 1 resposta inicial
  question.answers.push(createAnswer(type))
  question.answers.push(createAnswer(type))

  return question
}

export function addAnswer(question) {
  if (question.type === 'essay') return

  const answer = createAnswer(question.type)
  question.answers.push(answer)
}

export function normalizeQuestionFromAPI(rawQuestion) {
  return {
    type: rawQuestion.type,
    title: rawQuestion.title || '',
    statement: rawQuestion.statement || '',
    answers: rawQuestion.answers ? rawQuestion.answers.map(a => ({ ...a })) : []
  }
}

// Exportació per defecte
export default {
  createQuestion,
  addAnswer,
  normalizeQuestionFromAPI
}