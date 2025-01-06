<template>
  <footer class="container-fluid mt-auto">
    <div class="row pt-4 border-top">
      <div class="d-flex justify-content-around mb-3">
        <div class="p-2 ">

          <ul class="list-unstyled text-start">
            <li>
              <h3>
                Sou Proprietário
              </h3>
            </li>
            <li><router-link class="me-2" to="/">Quero cadastrar</router-link></li>
            <li><router-link class="me-2" to="/">Planos</router-link></li>
            <li><router-link class="me-2" to="/">Panel de Controle Proprietário</router-link></li>
          </ul>
        </div>

        <div class="p-2 ">

          <ul class="list-unstyled text-start">
            <li>
              <h3>
                Sou Jogador
              </h3>
            </li>
            <li><router-link class="me-2" to="/">Quero cadastrar</router-link></li>
            <li><router-link class="me-2" to="/">Convide um amigo</router-link></li>
            <li><router-link class="me-2" to="/">Planos Free</router-link></li>
            <li><router-link class="me-2" to="/">Planos Plus</router-link></li>
            <li><router-link class="me-2" to="/">Panel de Controle</router-link></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="d-flex flex-row justify-content-center">
      <ul class="list-inline">
        <li class="list-inline-item"><router-link to="/">Sobre |</router-link></li>
        <li class="list-inline-item"><router-link to="/">Contatos |</router-link></li>
        <li class="list-inline-item"><router-link to="/">Reclamações |</router-link></li>
        <li class="list-inline-item"><router-link to="/">Sugestões |</router-link></li>
      </ul>
    </div>

    <div class="d-flex flex-sm-row flex-column justify-content-center py-2">
      <div>
        <small><router-link class="" to="/">{{ pageTitle }} - &copy; 2023–2028</router-link></small>
      </div>
      <div class="d-none d-md-block mx-2">-</div>
      <div><small>
          <a href="https://www.agenciasolution.com" title="Feito por Agência Solution">Feito por Agência Solution</a>
        </small></div>
    </div>

  </footer>
</template>

<script>

export default {
  name: 'TemplateRodape',
  components: {
  },
  data() {
    return {
      pageTitle: process.env.VUE_APP_NAME,
      pageDescription: process.env.VUE_APP_DESCRIPTION
    }
  }
}
</script>
