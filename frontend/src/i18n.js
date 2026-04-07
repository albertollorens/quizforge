// src/i18n.js
import { createI18n } from 'vue-i18n'
import en from './locales/en.json'
import es from './locales/es.json'
import va from './locales/va.json'

const i18n = createI18n({
  allowComposition: true,
  locale: localStorage.getItem('lang') || 'va',
  fallbackLocale: 'va',
  messages: {en, es, va}
})

export default i18n