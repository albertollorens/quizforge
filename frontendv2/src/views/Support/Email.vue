<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="flex h-screen bg-gray-100 dark:bg-slate-900">

      <!-- SIDEBAR -->
      <div class="w-72 bg-white dark:bg-slate-800 border-r dark:border-slate-700 flex flex-col p-4">

        <!-- Compose -->
        <button class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 rounded-lg mb-6">
          ✏️ Compose
        </button>

        <!-- Mailbox -->
        <div class="text-xs text-gray-400 mb-2">MAILBOX</div>

        <ul class="space-y-2 mb-6">
          <li class="flex justify-between items-center bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg">
            <span>📥 Inbox</span>
            <span>3</span>
          </li>
          <li class="flex justify-between px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
            <span>📤 Sent</span>
          </li>
          <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg">📝 Drafts</li>
          <li class="flex justify-between px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
            <span>🚫 Spam</span>
            <span>2</span>
          </li>
          <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg">🗑 Trash</li>
        </ul>

        <!-- Labels -->
        <div class="text-xs text-gray-400 mb-2">LABEL</div>
        <ul class="space-y-2">
          <li class="flex items-center gap-2"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Personal</li>
          <li class="flex items-center gap-2"><span class="w-2 h-2 bg-red-500 rounded-full"></span> Work</li>
          <li class="flex items-center gap-2"><span class="w-2 h-2 bg-orange-500 rounded-full"></span> Payments</li>
        </ul>

      </div>

      <!-- MAIN CONTENT -->
      <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <div class="flex justify-between items-center p-4 border-b dark:border-slate-700 bg-white dark:bg-slate-800">
          <div class="flex gap-2">
            <button class="p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700">☑️</button>
            <button class="p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700">🔄</button>
            <button class="p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700">🗑</button>
          </div>

          <input
            type="text"
            placeholder="Search..."
            class="px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600"
          />
        </div>

        <!-- EMAIL LIST -->
        <div class="flex-1 overflow-y-auto">

          <div
            v-for="(mail, index) in emails"
            :key="index"
            class="flex items-center justify-between px-4 py-3 border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800"
          >

            <!-- LEFT -->
            <div class="flex items-center gap-3">
              <input type="checkbox" />
              <span>⭐</span>
              <span class="font-medium">{{ mail.sender }}</span>
            </div>

            <!-- CENTER -->
            <div class="flex-1 mx-4 text-sm text-gray-600 dark:text-gray-300 truncate">
              {{ mail.message }}
            </div>

            <!-- TAG -->
            <div v-if="mail.tag" class="text-xs px-2 py-1 rounded-full bg-gray-200 dark:bg-slate-700">
              {{ mail.tag }}
            </div>

            <!-- TIME -->
            <div class="text-xs text-gray-400 w-20 text-right">
              {{ mail.time }}
            </div>

          </div>

        </div>

        <!-- FOOTER -->
        <div class="p-3 text-sm text-gray-500 border-t dark:border-slate-700 bg-white dark:bg-slate-800">
          Showing {{ emails.length }} emails
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
const currentPageTitle = ref('Email')

const emails = ref([
  {
    sender: 'Material UI',
    message: 'Lorem ipsum dolor sit amet...',
    tag: 'Important',
    time: '12:16 pm'
  },
  {
    sender: 'Wise',
    message: 'Lorem ipsum dolor sit amet...',
    time: '12:16 pm'
  },
  {
    sender: 'Search Console',
    message: 'Lorem ipsum dolor sit amet...',
    tag: 'Social',
    time: 'Apr, 24'
  },
  {
    sender: 'Paypal',
    message: 'Lorem ipsum dolor sit amet...',
    time: 'Apr, 20'
  }
])
</script>
