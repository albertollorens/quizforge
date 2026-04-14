<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6"
  >
    <div class="flex justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
          Demogràfic de clients
        </h3>
        <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
          Nombre de clients segons el país
        </p>
      </div>
    </div>
    <div
      class="px-4 py-6 my-6 overflow-hidden border border-gary-200 rounded-2xl bg-gray-50 dark:border-gray-800 dark:bg-gray-900 sm:px-6"
    >
      <div
        ref="mapOneRef"
        id="mapOne"
        class="mapOne map-btn -mx-4 -my-6 h-[212px] w-[252px] 2xsm:w-[307px] xsm:w-[358px] sm:-mx-6 md:w-[668px] lg:w-[634px] xl:w-[393px] 2xl:w-[554px]"
      ></div>
    </div>
    <div class="space-y-5">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="items-center w-full rounded-full max-w-8">
            <img src="/images/country/spain.png" alt="spain" />
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">Espanya</p>
            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
              79 Clients
            </span>
          </div>
        </div>

        <div class="flex w-full max-w-[140px] items-center gap-3">
          <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
            <div
              class="absolute left-0 top-0 flex h-full w-[59%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white"
            ></div>
          </div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">59%</p>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="items-center w-full rounded-full max-w-8">
            <img src="/images/country/andorra.png" alt="andorra" />
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">Andorra</p>
            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
              39 Clients
            </span>
          </div>
        </div>

        <div class="flex w-full max-w-[140px] items-center gap-3">
          <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
            <div
              class="absolute left-0 top-0 flex h-full w-[23%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white"
            ></div>
          </div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">23%</p>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="items-center w-full rounded-full max-w-8">
            <img src="/images/country/country-02.svg" alt="france" />
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">França</p>
            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
              19 Clients
            </span>
          </div>
        </div>

        <div class="flex w-full max-w-[140px] items-center gap-3">
          <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
            <div
              class="absolute left-0 top-0 flex h-full w-[13%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white"
            ></div>
          </div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">13%</p>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="items-center w-full rounded-full max-w-8">
            <img src="/images/country/country-01.svg" alt="usa" />
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">Estats Units</p>
            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
              10 Clients
            </span>
          </div>
        </div>

        <div class="flex w-full max-w-[140px] items-center gap-3">
          <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
            <div
              class="absolute left-0 top-0 flex h-full w-[10%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white"
            ></div>
          </div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">10%</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
// @ts-ignore
import jsVectorMap from 'jsVectorMap'
// @ts-ignore
import 'jsvectormap/dist/maps/world'

const mapOneRef = ref<HTMLElement | null>(null)
const mapInstance = ref<any>(null)

const initMap = () => {
  if (mapOneRef.value) {
    mapInstance.value = new jsVectorMap({
      selector: mapOneRef.value,
      map: 'world',
      zoomButtons: false,
      regionStyle: {
        initial: {
          fontFamily: 'Outfit',
          fill: '#D9D9D9',
        },
        hover: {
          fillOpacity: 1,
          fill: '#465fff',
        },
      },
      markers: [
        {
          name: 'Espanya',
          coords: [40.4636, -3.7492],
        },
        {
          name: 'Andorra',
          coords: [42.5462, 1.6015],
        },
        {
          name: 'França',
          coords: [46.2276, 2.2137],
        },
        {
          name: 'Estats Units',
          coords: [37.0902, -95.7129],
        },
      ],
      markerStyle: {
        initial: {
          strokeWidth: 1,
          fill: '#465fff',
          fillOpacity: 1,
          r: 4,
        },
        hover: {
          fill: '#465fff',
          fillOpacity: 1,
        },
        selected: {},
        selectedHover: {},
      },
      onRegionTooltipShow: function (event: MouseEvent, tooltip: any) {
        const code = (event.target as HTMLElement).getAttribute('data-code')
        if (code === 'EG') {
          tooltip.setContent(tooltip.text() + ' (Hello Egypt)')
        }
      },
    })
  }
}

onMounted(() => {
  initMap()
})
</script>
