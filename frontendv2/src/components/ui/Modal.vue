<template>
  <div :class="['flex items-center justify-center min-h-screen overflow-y-auto', isFixed ? 'fixed inset-0 z-[99999]' : 'absolute inset-0 z-50']">
    <div
      v-if="fullScreenBackdrop"
      class="absolute inset-0 bg-black/50 backdrop-blur-[32px]"
      aria-hidden="true"
      @click="$emit('close')"
    ></div>
    <div class="relative z-[100000]">
      <slot name="body"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
interface ModalProps {
  fullScreenBackdrop?: boolean
  isFixed?: boolean
}

defineProps<ModalProps>()
defineEmits(['close'])
</script>
