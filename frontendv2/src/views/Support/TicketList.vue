<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="p-6 bg-gray-50 dark:bg-slate-900 min-h-screen">

        <!-- STATS -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">

          <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg">🎟</div>
            <div>
              <p class="text-lg font-bold">47</p>
              <p class="text-sm text-gray-500">Total tickets</p>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center bg-orange-100 text-orange-600 rounded-lg">⏳</div>
            <div>
              <p class="text-lg font-bold">30</p>
              <p class="text-sm text-gray-500">Tickets Pendents</p>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center bg-green-100 text-green-600 rounded-lg">✔</div>
            <div>
              <p class="text-lg font-bold">17</p>
              <p class="text-sm text-gray-500">Tickets Resolts</p>
            </div>
          </div>

        </div>

        <!-- TABLE CARD -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow">

          <!-- TABLE HEADER -->
          <div class="flex justify-between items-center p-4 border-b dark:border-slate-700">

            <!-- FILTERS -->
            <div class="flex gap-2">
              <button
                v-for="f in filters"
                :key="f"
                @click="activeFilter = f"
                class="px-3 py-1 rounded-lg text-sm"
                :class="activeFilter === f
                  ? 'bg-indigo-500 text-white'
                  : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300'"
              >
                {{ f }}
              </button>
            </div>

            <!-- SEARCH -->
            <input
              v-model="search"
              type="text"
              placeholder="Buscar..."
              class="px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600"
            />

          </div>

          <!-- TABLE -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm">

              <thead class="text-left text-gray-500 border-b dark:border-slate-700">
                <tr>
                  <th class="p-3"><input type="checkbox" /></th>
                  <th class="p-3">Ticket ID</th>
                  <th class="p-3">Sol·licitat per</th>
                  <th class="p-3">Assumpte</th>
                  <th class="p-3">Data de creació</th>
                  <th class="p-3">Estat</th>
                  <th class="p-3"></th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="ticket in filteredTickets"
                  :key="ticket.id"
                  class="border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800"
                >
                  <td class="p-3"><input type="checkbox" /></td>

                  <td class="p-3 font-medium text-gray-700 dark:text-gray-200">
                    #{{ ticket.id }}
                  </td>

                  <td class="p-3">
                    <p class="font-medium">{{ ticket.name }}</p>
                    <p class="text-xs text-gray-400">{{ ticket.email }}</p>
                  </td>

                  <td class="p-3 text-gray-600 dark:text-gray-300">
                    {{ ticket.subject }}
                  </td>

                  <td class="p-3 text-gray-400">
                    {{ ticket.date }}
                  </td>

                  <td class="p-3">
                    <span
                      class="px-2 py-1 rounded-full text-xs font-medium"
                      :class="statusClass(ticket.status)"
                    >
                      {{ ticket.status }}
                    </span>
                  </td>

                  <td class="p-3 text-right cursor-pointer">⋮</td>
                </tr>
              </tbody>

            </table>
          </div>

        </div>

      </div>
      
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref, computed } from 'vue'
const currentPageTitle = ref('Tickets de suport')


// 🔍 filtros
const filters = ['Tots', 'Resolts', 'Pendents']
const activeFilter = ref('All')
const search = ref('')

// 📊 datos mock
const tickets = ref([
  {
    id: 323534,
    name: 'Lindsey Curtis',
    email: 'demoemail@gmail.com',
    subject: 'Issue with Dashboard Login Access',
    date: '12 Feb, 2027',
    status: 'Solved'
  },
  {
    id: 323535,
    name: 'Kaiya George',
    email: 'demoemail@gmail.com',
    subject: 'Billing Information Not Updating Properly',
    date: '13 Mar, 2027',
    status: 'Pending'
  },
  {
    id: 323536,
    name: 'Zain Geidt',
    email: 'demoemail@gmail.com',
    subject: 'Bug Found in Dark Mode Layout',
    date: '19 Mar, 2027',
    status: 'Pending'
  },
  {
    id: 323537,
    name: 'Abram Schleifer',
    email: 'demoemail@gmail.com',
    subject: 'Request to Add New Integration Feature',
    date: '25 Apr, 2027',
    status: 'Solved'
  }
])

// 🎯 filtro + búsqueda
const filteredTickets = computed(() => {
  return tickets.value.filter(t => {
    const matchFilter =
      activeFilter.value === 'All' || t.status === activeFilter.value

    const matchSearch =
      t.subject.toLowerCase().includes(search.value.toLowerCase()) ||
      t.name.toLowerCase().includes(search.value.toLowerCase())

    return matchFilter && matchSearch
  })
})

// 🎨 estilos estado
function statusClass(status) {
  return status === 'Solved'
    ? 'bg-green-100 text-green-600'
    : 'bg-orange-100 text-orange-600'
}
</script>
