<template>
  <section id="demo" class="py-20 bg-gray-50 border-y border-gray-100">
    <div class="max-w-3xl mx-auto px-4 text-center">

      <!-- TITLE -->
      <h2 class="text-2xl md:text-3xl font-semibold text-gray-900 mb-4">
        {{ t('demo.title') }}
      </h2>

      <p class="text-gray-600 mb-8">
        {{ t('demo.sub') }}
      </p>

      <!-- CARD -->
      <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-200 shadow-sm text-left">

        <!-- INPUT -->
        <div v-if="step==='input'" class="space-y-4">

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ t('demo.label') }}
            </label>
            <input
              v-model="topic"
              type="text"
              placeholder="Ex: El cicle de l'aigua..."
              class="w-full rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-100"
            >
          </div>

          <div class="flex gap-4">
            <div class="flex-1">
              <label class="block text-xs text-gray-500 mb-1">
                {{ t('demo.level') }}
              </label>
              <select class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm">
                <option>ESO</option>
                <option>Batxillerat</option>
                <option>FP</option>
                <option>Altres</option>
              </select>
            </div>

            <div class="flex-1">
              <label class="block text-xs text-gray-500 mb-1">
                {{ t('demo.questions') }}
              </label>
              <select class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm">
                <option>3 preguntes</option>
                <option>6 preguntes</option>
                <option>10 preguntes</option>
              </select>
            </div>
          </div>

          <button
            @click="runDemo"
            :disabled="!topic.trim()"
            class="w-full mt-4 bg-gray-900 text-white py-2.5 rounded-md text-sm hover:bg-gray-800 flex justify-center gap-2"
          >
            ✨ {{ t('demo.btn') }}
          </button>

        </div>

        <!-- LOADING -->
        <div v-if="step==='loading'" class="flex flex-col items-center py-12 space-y-4">
          <div class="animate-spin text-2xl">⏳</div>
          <p class="text-sm text-gray-500 animate-pulse">
            {{ t('demo.loading') }}
          </p>
        </div>

        <!-- RESULT -->
        <div v-if="step==='result'" class="space-y-6">

          <div class="flex justify-between border-b pb-4">
            <h3 class="font-semibold text-gray-900">Resultats generats</h3>
            <button @click="resetDemo" class="text-sm text-gray-500 hover:text-black">
              🔄 {{t('demo.reset')}}
            </button>
          </div>

          <!-- QUESTIONS -->
          <div v-for="(q, qIndex) in questions" :key="qIndex" class="space-y-3">

            <p class="text-sm font-medium text-gray-800">
              {{ qIndex + 1 }}. {{ q.question }}
            </p>

            <!-- SINGLE -->
            <div v-if="q.type==='single'" class="space-y-2 pl-4">
              <label
                v-for="(opt,i) in q.options"
                :key="i"
                class="flex items-center gap-3 p-2 rounded border cursor-pointer"
                :class="getOptionClass(q,i)"
              >
                <input type="radio" :checked="i===q.correctIndex">
                <span>{{ opt }}</span>
                <span v-if="i===q.correctIndex" class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">
                  ✔
                </span>
              </label>
            </div>

            <!-- MULTIPLE -->
            <div v-if="q.type==='multiple'" class="space-y-2 pl-4">
              <label
                v-for="(opt,i) in q.options"
                :key="i"
                class="flex items-center gap-3 p-2 rounded border cursor-pointer"
                :class="getOptionClass(q,i)"
              >
                <input type="checkbox" :checked="q.correctIndexes.includes(i)">
                <span>{{ opt }}</span>
                <span v-if="q.correctIndexes.includes(i)" class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">
                  ✔
                </span>
              </label>
            </div>

          </div>

          <!-- ACTIONS -->
          <div class="flex justify-end gap-3 pt-4 border-t">
            <button class="px-4 py-2 text-sm bg-gray-100 rounded">
              ✏️ Editar
            </button>
            <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded">
              🚀 Exportar
            </button>
          </div>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const topic = ref('')
const step = ref('input')
const questions = ref([])

function generateFakeQuestions(topic) {
  return [
    {
      type: 'single',
      question: `Quina afirmació sobre ${topic} és correcta?`,
      options: [
        `Incorrecta`,
        `Correcta`,
        `Falsa`
      ],
      correctIndex: 1
    },
    {
      type: 'multiple',
      question: `Selecciona les correctes:`,
      options: [
        `Opció bona`,
        `Opció bona`,
        `Opció incorrecta`
      ],
      correctIndexes: [0,1]
    }
  ]
}

function runDemo(){
  if(!topic.value.trim()) return
  step.value='loading'

  setTimeout(()=>{
    questions.value = generateFakeQuestions(topic.value)
    step.value='result'
  },1200)
}

function resetDemo(){
  step.value='input'
  topic.value=''
}

function getOptionClass(q,i){
  if(q.type==='single'){
    return i===q.correctIndex
      ? 'border-green-200 bg-green-50'
      : 'border-gray-100 hover:bg-gray-50'
  }

  if(q.type==='multiple'){
    return q.correctIndexes.includes(i)
      ? 'border-green-200 bg-green-50'
      : 'border-gray-100 hover:bg-gray-50'
  }
}
</script>

<style scoped>
.demo-section {
  background: #f8fafc;
}

/* caixa input */
.demo-box {
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* resultat */
.demo-card {
  border-radius: 16px;
  border: none;
  box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

/* opcions */
.option {
  padding: 10px 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  background: #f1f5f9;
  opacity: 0;
  transform: translateY(10px);
  animation: optionIn 0.4s ease forwards;
  transition: all 0.3s ease;
}

/* animacions */
@keyframes optionIn { to {opacity:1; transform:translateY(0)} }

.correct {
  background: #dcfce7 !important;
  color: #166534;
  font-weight: 600;
}

/* loading fake */
.fake-output {
  max-width: 400px;
  margin: auto;
}

.fake-line {
  height: 10px;
  background: #e2e8f0;
  border-radius: 5px;
  margin-bottom: 10px;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity:0.6 }
  50% { opacity:1 }
  100% { opacity:0.6 }
}

/* animacions */
.animate-fade { animation:fade 0.4s ease; }
@keyframes fade { from {opacity:0; transform:translateY(10px)} to {opacity:1; transform:translateY(0)} }

/* dark */
body.dark .demo-section { background: #020617; }
body.dark .demo-box, body.dark .demo-card { background: #0f172a; }
body.dark .option { background: #1e293b; }
</style>