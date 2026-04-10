<template>
  <div class="landing-layout">

    <!-- 🌈 GRADIENT ANIMAT -->
    <div class="gradient-bg"></div>

    <!-- 🧵 LÍNIES SVG -->
    <svg class="lines" viewBox="0 0 1440 800" preserveAspectRatio="none">
      <path d="M0,200 C300,100 600,300 900,200 C1200,100 1440,250 1440,250"
            class="line"/>
      <path d="M0,400 C400,300 800,500 1200,400 C1400,350 1440,420 1440,420"
            class="line delay1"/>
      <path d="M0,600 C300,550 700,700 1100,600 C1300,550 1440,650 1440,650"
            class="line delay2"/>
    </svg>

    <!-- 📦 CONTINGUT -->
    <router-view />   
  </div>
  
  <ScrollTop />
</template>

<script setup>
import ScrollTop from '../components/landing/ScrollTop.vue';
</script>

<style scoped>
.landing-layout {
  position: relative;
  min-height: 100vh;
  overflow: hidden;
}

/* 🌈 GRADIENT TIPUS STRIPE */
.gradient-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    120deg,
    #6366f1,
    #22c55e,
    #06b6d4,
    #6366f1
  );
  background-size: 300% 300%;
  animation: gradientMove 15s ease infinite;
  opacity: 0.15;
  z-index: 0;
}

@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* 🧵 LÍNIES */
.lines {
  position: absolute;
  inset: 0;
  z-index: 0;
  pointer-events: none;
}

.line {
  fill: none;
  stroke: rgba(99,102,241,0.4);
  stroke-width: 2;
  stroke-dasharray: 6 6;
  animation: moveLine 20s linear infinite;
}

.line.delay1 {
  animation-delay: 5s;
}

.line.delay2 {
  animation-delay: 10s;
}

@keyframes moveLine {
  from {
    stroke-dashoffset: 0;
  }
  to {
    stroke-dashoffset: 1000;
  }
}

/* CONTINGUT DAMUNT*/
.landing-layout > *:not(.gradient-bg):not(.lines) {
  position: relative;
  /*z-index: 1;*/
}
</style>