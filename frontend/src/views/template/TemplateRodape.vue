<template>
  <footer class="container-fluid mt-auto">
    <div class="row pt-4 border-top">
      <div class="d-flex justify-content-around mb-3">
        <div class="p-2 ">

          <ul class="list-unstyled text-start">
            <li>
              <h3>
                Menu Bottom 1
              </h3>
            </li>
            <li>Quero cadastrar</li>
            <li>Planos</li>
            <li>Panel de Controle Proprietário</li>
          </ul>
        </div>

        <div class="p-2 ">

          <ul class="list-unstyled text-start">
            <li>
              <h3>
                Menu Bottom 2
              </h3>
            </li>
            <li>Planos Free</li>
            <li>Planos Plus</li>
            <li>Panel de Controle</li>
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
          <a href="https://github.com/Tellys/" title="Feito por Tellys Castro">Feito por Tellys Castro</a>
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
