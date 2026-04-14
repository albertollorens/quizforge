<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="flex h-screen bg-gray-100 dark:bg-slate-900">

        <!-- SIDEBAR -->
        <div class="w-80 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col">
          
          <!-- Header -->
          <div class="p-4 font-semibold text-lg">Chats</div>

          <!-- Search -->
          <div class="px-4 pb-3">
            <input
              type="text"
              placeholder="Buscar..."
              class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-700"
            />
          </div>

          <!-- Users -->
          <div class="flex-1 overflow-y-auto">
            <div
              v-for="chat in chats"
              :key="chat.id"
              @click="selectChat(chat)"
              class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700"
            >
              <img :src="chat.avatar" class="w-10 h-10 rounded-full" />

              <div class="flex-1">
                <p class="text-sm font-medium">{{ chat.name }}</p>
                <p class="text-xs text-gray-500">{{ chat.role }}</p>
              </div>

              <span class="text-xs text-gray-400">{{ chat.time }}</span>
            </div>
          </div>
        </div>

        <!-- CHAT AREA -->
        <div class="flex-1 flex flex-col">

          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center gap-3">
              <img :src="activeChat.avatar" class="w-10 h-10 rounded-full" />
              <p class="font-medium">{{ activeChat.name }}</p>
            </div>
          </div>

          <!-- Messages -->
          <div class="flex-1 overflow-y-auto p-6 space-y-4">

            <div
              v-for="msg in messages"
              :key="msg.id"
              :class="msg.me ? 'flex justify-end' : 'flex justify-start'"
            >
              <div
                :class="[
                  'px-4 py-2 rounded-xl max-w-xs text-sm',
                  msg.me
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-200 dark:bg-slate-700 dark:text-white'
                ]"
              >
                {{ msg.text }}
              </div>
            </div>

          </div>

          <!-- Input -->
          <div class="p-4 border-t border-gray-200 dark:border-slate-700 flex gap-3">
            <input
              v-model="newMessage"
              type="text"
              placeholder="Type a message"
              class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-700"
              @keyup.enter="sendMessage"
            />

            <button
              @click="sendMessage"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
              ➤
            </button>
          </div>

        </div>
      </div>
      
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref } from 'vue'
const currentPageTitle = ref('Xat')

// Mock chats
const chats = ref([
  {
    id: 1,
    name: 'Joan Cardona',
    role: 'Docent Història',
    time: '30 mins',
    avatar: 'https://i.pravatar.cc/100?img=1'
  },
  {
    id: 2,
    name: 'Laura Diez',
    role: 'Directora CEIP Lluis Vives',
    time: '15 mins',
    avatar: 'https://i.pravatar.cc/100?img=2'
  }
])

// Active chat
const activeChat = ref(chats.value[0])

// Messages
const messages = ref([
  { id: 1, text: 'Hola!', me: false },
  { id: 2, text: 'Hola, en què et puc ajudar?', me: true },
  { id: 3, text: 'Voldria més informació', me: false }
])

const newMessage = ref('')

// Select chat
function selectChat(chat) {
  activeChat.value = chat
}

// Send message
function sendMessage() {
  if (!newMessage.value.trim()) return

  messages.value.push({
    id: Date.now(),
    text: newMessage.value,
    me: true
  })

  newMessage.value = ''
}
</script>
