<template>
  <!-- <section class="vh-100"> -->

  <div class="col-12">
    <!-- Modal trigger button -->
    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalId">
      Launch
    </button>

    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade show" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
      aria-labelledby="modalTitleId" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitleId">
              Modal title
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm" class="mb-2">
              <!-- Email input -->
              <div class="mb-4 input-group">
                <label for="formLabelCity" class="d-none">Cidade</label>
                <input placeholder="Cidade" type="text" id="formLabelCity" class="form-control" v-model="register.city"
                  required autofocus />

                <label for="formLoginLabelSenha" class="d-none">Estado</label>

                <select class=" form-control form-select" aria-label="Default select example" v-model="register.state"
                  required>
                  <option selected>Open this select menu</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>

              <!-- Submit button -->
              <div class="btn-group w-100" role="group">
                <button type="submit" class="btn btn-success btn-lg btn-block">Entrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional: Place to the bottom of scripts -->
    <!-- <script>
  const myModal = new bootstrap.Modal(
    document.getElementById("modalId"),
    options,
  );
 </script> -->


  </div>



</template>

<script>
import Api from '@/services/Api';

export default {
  name: 'SetLocationComponent',
  components: {
  },
  data() {
    return {
      register: {
        redirect: { name: 'profile' }
      },
      error: "",
      data: [],
    }
  },
  methods: {
    async submitForm() {
      return await Api.login(this.register, this.$route?.query?.redirect ?? '/');
    },
  }
}
</script>
