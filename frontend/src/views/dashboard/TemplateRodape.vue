<template>
  <footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
      <div class="col-12 col-md">
        <router-link to="/">
          {{ pageTitle }}
        </router-link>
        <small class="d-block mb-3 text-muted">{{ pageDescription }}</small>
        <small class="d-block mb-3 text-muted">Tellys Castro - &copy; 2023–2028</small>
        <small class="d-block mb-3 text-muted">
          <a href="https://www.agenciasolution.com"
            title="Feito por Agência Solution">Feito por Agência Solution</a>
        </small>
      </div>

    </div>
  </footer>
</template>

<script>

export default {
  name: 'TemplateRodape',
  data() {
    return {
      pageTitle: process.env.VUE_APP_NAME,
      pageDescription: process.env.VUE_APP_DESCRIPTION
    }
  }
}
</script>
