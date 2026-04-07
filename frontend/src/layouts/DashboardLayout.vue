<template>
  <div id="wrapper" class="d-flex">

    <!-- Sidebar -->
    <Sidebar />

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="flex-grow-1 d-flex flex-column">

      <!-- Topbar -->
      <Navbar />

      <!-- Main Content -->
      <main class="container-fluid mt-4">
        <router-view />
      </main>

      <!-- Footer -->
      <Footer />
    </div>
  </div>
</template>

<script setup>
import Sidebar from '../components/dashboard/Sidebar.vue'
import Navbar from '../components/dashboard/Navbar.vue'
import Footer from '../components/dashboard/Footer.vue'
</script>

<style scoped>
#wrapper {
  min-height: 100vh;
}
</style>
