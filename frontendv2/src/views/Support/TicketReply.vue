<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="p-6 bg-gray-50 dark:bg-slate-900 min-h-screen">

        <div class="grid lg:grid-cols-3 gap-6">

          <!-- CHAT AREA -->
          <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow flex flex-col">

            <!-- TITLE -->
            <div class="p-4 border-b dark:border-slate-700">
              <h2 class="font-semibold text-gray-800 dark:text-white">
                Ticket #346520 - Sidebar not responsive on mobile
              </h2>
              <p class="text-xs text-gray-400">
                Mon, 3:20 PM (2 days ago)
              </p>
            </div>

            <!-- MESSAGES -->
            <div class="flex-1 overflow-y-auto p-4 space-y-6">

              <div v-for="(msg, i) in messages" :key="i" class="flex gap-3">

                <!-- AVATAR -->
                <img
                  :src="msg.avatar"
                  class="w-10 h-10 rounded-full"
                />

                <!-- CONTENT -->
                <div class="flex-1">
                  <div class="flex justify-between">
                    <div>
                      <p class="font-medium text-gray-800 dark:text-white">
                        {{ msg.name }}
                      </p>
                      <p class="text-xs text-gray-400">
                        {{ msg.role }}
                      </p>
                    </div>
                    <span class="text-xs text-gray-400">
                      {{ msg.time }}
                    </span>
                  </div>

                  <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">
                    {{ msg.text }}
                  </p>
                </div>

              </div>

            </div>

            <!-- REPLY BOX -->
            <div class="p-4 border-t dark:border-slate-700">

              <textarea
                v-model="reply"
                placeholder="Type your reply here..."
                class="w-full p-3 border rounded-xl resize-none h-28 dark:bg-slate-700 dark:border-slate-600"
              ></textarea>

              <div class="flex justify-between items-center mt-3">

                <button class="text-sm text-gray-500 hover:underline">
                  📎 Attach
                </button>

                <button
                  @click="sendReply"
                  class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg"
                >
                  Reply
                </button>

              </div>

              <!-- STATUS -->
              <div class="mt-4 flex items-center gap-4 text-sm">
                <span>Status:</span>

                <label class="flex items-center gap-1">
                  <input type="radio" value="In Progress" v-model="status" />
                  In-Progress
                </label>

                <label class="flex items-center gap-1">
                  <input type="radio" value="Solved" v-model="status" />
                  Solved
                </label>

                <label class="flex items-center gap-1">
                  <input type="radio" value="On Hold" v-model="status" />
                  On-Hold
                </label>
              </div>

            </div>

          </div>

          <!-- SIDEBAR -->
          <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-4">

            <h3 class="font-semibold mb-4 text-gray-800 dark:text-white">
              Ticket Details
            </h3>

            <div class="space-y-3 text-sm">

              <div class="flex justify-between">
                <span class="text-gray-500">Customer</span>
                <span>John Doe</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-500">Email</span>
                <span>jhondelin@gmail.com</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-500">Ticket ID</span>
                <span>#346520</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-500">Category</span>
                <span>General Support</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-500">Created</span>
                <span>Dec 20, 2028</span>
              </div>

              <div class="flex justify-between items-center">
                <span class="text-gray-500">Status</span>
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">
                  {{ status }}
                </span>
              </div>

            </div>

          </div>

        </div>

      </div>
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
const currentPageTitle = ref('Resposta del Ticket')

import { ref } from 'vue'

// 📨 mensajes mock
const messages = ref([
  {
    name: 'John Doe',
    role: 'jhondelin@gmail.com',
    time: 'Mon, 3:20 PM (2 hrs ago)',
    avatar: 'https://i.pravatar.cc/40?img=1',
    text: `Hi TailAdmin Team,
I hope you're doing well.
I'm currently working on customizing the dashboard...`
  },
  {
    name: 'Support Team',
    role: 'From - support team',
    time: 'Mon, 3:20 PM (2 hrs ago)',
    avatar: 'https://i.pravatar.cc/40?img=2',
    text: `Hi John,
Thanks for reaching out — yes, you can add custom pages easily.`
  }
])

const reply = ref('')
const status = ref('In Progress')

// 📤 enviar respuesta
function sendReply() {
  if (!reply.value.trim()) return

  messages.value.push({
    name: 'You',
    role: 'Support agent',
    time: 'Now',
    avatar: 'https://i.pravatar.cc/40?img=3',
    text: reply.value
  })

  reply.value = ''
}
</script>
