<template>
  <nav class="navbar custom-navbar shadow-sm px-3 reveal">

    <!-- LOGO -->
    <div class="nav-left d-flex align-items-center">
      <img :src="logo" alt="QuizForge logo" class="logo" width="150px" />
    </div>

    <!-- LINKS CENTRATS -->
    <div class="menu-links">
      <a href="#demo">{{ t('navbar.demo') }}</a>
      <a href="#features">{{ t('navbar.features') }}</a>
      <a href="#success">{{ t('navbar.testimonials') }}</a>
      <a href="#pricing">{{ t('navbar.pricing') }}</a>
      <a href="#contact">{{ t('navbar.contact') }}</a>
    </div>

    <!-- HAMBURGER -->
    <button class="hamburger" @click="isOpen = !isOpen">
      <span></span>
    </button>

    <!-- ACCIONS -->
    <div class="nav-right d-flex align-items-center gap-2">

      <select v-model="locale" @change="saveLang" class="form-select form-select-sm w-auto">
        <option value="va">VAL</option>
        <option value="es">CAS</option>
        <option value="en">ENG</option>
      </select>

      <button class="btn btn-cta btn-sm">
        {{ t('navbar.cta') }}
      </button>

      <a href="#contact" class="btn btn-contactus btn-sm">
        {{ t('navbar.contactus') }}
      </a>

      <!-- Login / Register Dropdown -->
      <div class="user-menu dropdown">
        <button class="btn btn-outline-theme btn-sm dropdown-toggle" @click="toggleUserMenu">
          <i class="bi bi-person-circle"></i> {{ t('navbar.account') }}
        </button>
        <ul v-if="showUserMenu" class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="/login">{{ t('navbar.login') }}</a></li>
          <li><a class="dropdown-item" href="/register">{{ t('navbar.register') }}</a></li>
        </ul>
      </div>

      <button class="btn btn-sm btn-outline-theme" @click="toggle" :title="isDark ? 'Light mode' : 'Dark mode'">
        <i class="bi" :class="isDark ? 'bi-sun' : 'bi-moon'"></i>
      </button>

    </div>

    <!-- MENÚ MOBILE -->
    <div :class="['mobile-menu', { open: isOpen }]">
      <a href="#demo">{{ t('navbar.demo') }}</a>
      <a href="#features">{{ t('navbar.features') }}</a>
      <a href="#pricing">{{ t('navbar.pricing') }}</a>
      <a href="#contact">{{ t('navbar.contact') }}</a>
    </div>

  </nav>
</template>

<script setup>
import { ref } from 'vue'
import logo from '@/assets/img/logo.png'
import { useI18n } from 'vue-i18n'
import { useDarkMode } from '@/composables/useDarkMode'
import { useScrollSpy } from '@/composables/useScrollSpy'

const isOpen = ref(false)
const showUserMenu = ref(false)

const { activeSection } = useScrollSpy([
  'hero',
  'demo',
  'features',
  'pricing',
  'contact'
])

const { t, locale } = useI18n()
const { isDark, toggle } = useDarkMode()

function saveLang() {
  localStorage.setItem('lang', locale.value)
}

// Canvia l'estat del menú d'usuari
function toggleUserMenu() {
  showUserMenu.value = !showUserMenu.value
}
</script>

<style scoped>
/* GRID LAYOUT PERFECTE */
.custom-navbar {
  background: white;
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  position: relative;
  z-index: 1000;
}

/* LOGO */
.logo {
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

/* MENU CENTRAT */
.menu-links {
  display: flex;
  justify-self: center;
  align-items: center;
  gap: 10px;
  background: #f1f5f9;
  padding: 6px 14px;
  border-radius: 50px;
}

/* LINKS */
.menu-links a {
  padding: 6px 12px;
  border-radius: 20px;
  color: #0b1f5b;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
}

.menu-links a:hover {
  background: #ff6a00;
  color: white;
}

/* BOTONS */
.btn-cta, .btn-contactus {
  background: #ff6a00;
  color: white;
  border: none;
}

.btn-cta:hover, .btn-contactus:hover {
  background: #0b1f5b;
}

.btn-outline-theme {
  border-color: #0b1f5b;
  color: #0b1f5b;
}

.btn-outline-theme:hover {
  background: #0b1f5b;
  color: white;
}

/* HAMBURGER */
.hamburger {
  display: none;
  background: none;
  border: none;
}

.hamburger span,
.hamburger span::before,
.hamburger span::after {
  display: block;
  width: 25px;
  height: 3px;
  background: #0b1f5b;
  position: relative;
  transition: 0.3s;
}

.hamburger span::before,
.hamburger span::after {
  content: '';
  position: absolute;
  left: 0;
}

.hamburger span::before {
  top: -8px;
}

.hamburger span::after {
  top: 8px;
}

/* MOBILE MENU */
.mobile-menu {
  position: absolute;
  top: 70px;
  left: 0;
  width: 100%;
  background: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
  padding: 20px;

  opacity: 0;
  transform: translateY(-10px);
  pointer-events: none;
  transition: all 0.3s ease;
}

.mobile-menu.open {
  opacity: 1;
  transform: translateY(0);
  pointer-events: all;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

/* RESPONSIVE */
@media (max-width: 768px) {

  .menu-links {
    display: none;
  }

  .hamburger {
    display: block;
  }
}

/* ACTIVE */
.nav-link.active {
  color: #ff6a00 !important;
  font-weight: bold;
}

/* DROPDOWN USER MENU */
.user-menu {
  position: relative;
}

.user-menu .dropdown-menu {
  position: absolute;
  top: 110%;
  right: 0;
  display: flex;
  flex-direction: column;
  min-width: 150px;
  background: white;
  border-radius: 8px;
  padding: 0.5rem 0;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 2000;
}

.user-menu .dropdown-item {
  padding: 8px 16px;
  cursor: pointer;
  color: #0b1f5b;
  text-decoration: none;
}

.user-menu .dropdown-item:hover {
  background: #ff6a00;
  color: white;
}

/* Responsive: dropdown visible on desktop */
@media (max-width: 768px) {
  .user-menu {
    display: none;
  }
}
</style>