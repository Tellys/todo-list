<template>
  <!-- <section class="vh-100"> -->

  <div class="w-100">
    <form @submit.prevent="loginDefault" class="mb-2">
      <!-- Email input -->
      <div class="mb-4">
        <label for="formLoginLabelEmail" class="d-none">Email</label>
        <input placeholder="E-mail" type="email" id="formLoginLabelEmail" class="form-control" v-model="register.email"
          required autofocus />
      </div>

      <!-- Password input -->
      <div class="mb-4">
        <!-- <label for="formLoginLabelSenha" class="d-none">Senha</label>
        <input placeholder="Senha" type="password" id="formLoginLabelSenha" class="form-control"
          v-model="register.password" required /> -->
        <div class="input-group mb-3">
          <!-- <span class="input-group-text"><i class="fa fa-lock"></i></span> -->
          <label for="formLoginLabelSenha" class="d-none">Senha</label>
          <input class="form-control border-end-0 border rounded" type="password" id="formLoginLabelSenha"
            name="password" placeholder="Senha" v-model="register.password" required>
          <span class="input-group-text" id="togglePassword" style="cursor: pointer">
            <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
          </span>
        </div>
      </div>

      <!-- Submit button -->
      <div class="btn-group w-100" role="group"><button type="submit"
          class="btn btn-success btn-lg btn-block">Entrar</button>
      </div>
    </form>

    <div class="btn-group w-100" role="group">
      <button type="button" @click.prevent="loginGoogle" class="btn btn-outline-secondary"><i
          class="bi bi-google me-2"></i> Login</button>
      <button type="button" @click.prevent="loginFacebook" class="btn btn-outline-secondary"><i
          class="bi bi-facebook me-2"></i>Login</button>

      <!-- <button type="button" @click.prevent="loginGithub" class="col-12 btn btn-dark btn-lg btn-block my-1">Login
              with Github</button> -->
    </div>

    <div class="btn-group w-100" role="group">
      <router-link to="/user/forgot-password" class="btn" role="link"><i class="bi bi-key me-2"></i>Esqueceu a
        senha?</router-link>
    </div>

    <div @click="this.$router.push('/user/create-simple')" role="button"
      class="d-flex justify-content-center align-items-center p-2 rounded border shadow mt-5"
      style="background-color: #26343E; min-width: 80px; min-height: 80px; color:#fff;">
      <div class="me-2">
        <i class="fa-solid fa-user-plus"></i>
      </div>

      <div style="color: #fff;">Cadastre-se</div>

    </div>
  </div>

</template>

<script>
import Api from '@/services/Api';
// import GoogleAuth from '@/services/authSocialMeida/GoogleAuth'
// import FacebookAuth from '@/services/authSocialMeida/FacebookAuth'

export default {
  name: 'LoginComponent',
  components: {

  },
  data() {
    return {
      register: {
        email: "",
        password: "",
        redirect: { name: 'profile' }
      },
      error: "",
      data: [],
    }
  },
  mounted() {
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#formLoginLabelSenha");
    const togglePasswordIcon = document.querySelector("#togglePasswordIcon");

    togglePassword.addEventListener("click", function () {

      // toggle the type attribute
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      // toggle the eye icon
      togglePasswordIcon.classList.toggle('fa-eye-slash');
      togglePasswordIcon.classList.toggle('fa-eye');
    });
  },
  methods: {

    ///
    async init() {
      this.isLoggedIn = await Api.isLoggedIn();
    },

    /* async loginDefault() {
      await Api.login(this.register, this.$route?.query?.redirect ?? '/');
      await this.init();
      return 
    }, */

    ///
    async loginDefault() {
      await Api.login(this.register, this.$route?.query?.redirect ?? '/');
      await this.init();
      return
    },

    ///
    async loginGoogle() {
      //return GoogleAuth.login();
    },

    ///
    async loginFacebook() {
      //return FacebookAuth.login();
    },

    ///
    async logoutFacebook() {
      //return FacebookAuth.logout();
    },

    ///
    async loginGithub() {
      //return Api.get('login/github');
    },
  }
}
</script>
