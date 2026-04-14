<template>
  <footer class="footer-section pt-5 pb-4">
    <div class="container">

      <div class="row text-start">

        <!-- 🧠 BRAND -->
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold">QuizForge</h5>
          <p class="text-muted small">
            {{ t('footer.desc') }}
          </p>
        </div>

        <!-- 🔗 LINKS -->
        <div class="col-md-2 col-6 mb-4">
          <h6 class="fw-semibold">{{ t('footer.links') }}</h6>
          <ul class="list-unstyled small">
            <li><a href="#demo">{{ t('footer.demo') }}</a></li>
            <li><a href="#features">{{ t('footer.features') }}</a></li>
            <li><a href="#success">{{ t('footer.success') }}</a></li>
            <li><a href="#pricing">{{ t('footer.pricing') }}</a></li>
            <li><a href="#contact">{{ t('footer.contact') }}</a></li>
          </ul>
        </div>

        <!-- 📄 LEGAL -->
        <div class="col-md-3 col-6 mb-4">
          <h6 class="fw-semibold">{{ t('footer.legal') }}</h6>
          <ul class="list-unstyled small">
            <li><a href="#">{{ t('footer.privacy') }}</a></li>
            <li><a href="#">{{ t('footer.terms') }}</a></li>
          </ul>
        </div>

        <!-- 🌐 SOCIAL -->
        <div class="col-md-3 mb-4">
          <h6 class="fw-semibold">{{ t('footer.follow') }}</h6>

          <div class="d-flex gap-3 mt-2 social-icons">
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-github"></i></a>
          </div>
        </div>

      </div>

      <!-- 🔻 bottom -->
      <div class="text-center pt-4 mt-4 border-top small text-muted">
        © {{ new Date().getFullYear() }} QuizForge — {{ t('footer.rights') }}
      </div>

    </div>
  </footer>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>

<style scoped>
.footer-section {
  background: #0f172a;
  color: #e2e8f0;
}

/* links */
.footer-section a {
  color: #94a3b8;
  text-decoration: none;
  transition: 0.2s;
}

.footer-section a:hover {
  color: white;
}

/* icones socials */
.social-icons a {
  font-size: 18px;
  color: #94a3b8;
  transition: 0.3s;
}

.social-icons a:hover {
  color: #f97316;
  transform: translateY(-3px);
}

/* separador */
.footer-section .border-top {
  border-color: #1e293b !important;
}

.text-muted {
  color: #858796 !important;
}
</style>