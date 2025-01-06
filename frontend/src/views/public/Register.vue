<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Registre-se em nosso sistema</h1>
                        <p class="text-center">Para ter acesso ao nosso sistema é necessário que você se registre para
                            possilibar seu login.</p>

                        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                            <form @submit.prevent='registerUser'>
                                <!-- Name input -->
                                <div class="form-outline mb-4">
                                    <input type="text" id="name" class="form-control form-control-lg"
                                        v-model="register.name" required autofocus />
                                    <label class="form-label" for="name">Nome</label>
                                </div>

                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" class="form-control form-control-lg"
                                        v-model="register.email" required />
                                    <label class="form-label" for="email">Email</label>
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="password" class="form-control form-control-lg"
                                        v-model="register.password" required />
                                    <label class="form-label" for="password">Senha</label>
                                </div>

                                <!-- Confirm Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="c_password" class="form-control form-control-lg"
                                        v-model="register.c_password" required />
                                    <label class="form-label" for="c_password">Senha</label>
                                </div>

                                <div class="d-flex justify-content-around align-items-center mb-4">
                                    <!-- Checkbox -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="formLoginLabelRemember" checked />
                                        <label class="form-check-label" for="formLoginLabelRemember"> Deixar Logado
                                        </label>
                                    </div>
                                    <router-link to="/esqueci-minha-senha">Esqueceu a senha?</router-link>
                                    <router-link to="/user/register">Quero me cadastrar</router-link>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="col-12 btn btn-primary btn-lg btn-block">Entrar</button>

                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { defineAsyncComponent } from 'vue';

export default {
    name: 'RegisterView',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
    },
    data() {
        return {
            register: {
                name: "",
                email: "",
                password: "",
                c_password: "",
            },
            error: "",
            data: [],
        }
    },
    methods: {
        registerUser() {
            axios.post('register', this.register)
                .then(response => {
                    this.error = false;
                    this.data = response.data;
                    console.log(response);

                    if (response.status === 200) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: response.data.message,
                            icon: 'success',
                            onClose: () => {
                                this.$router.push('/');
                            }
                        })
                            /* .then(function () {
                                this.$router.push('/');
                            }) */
                            ;
                    }

                    //                    const status = JSON.parse(response.data.response.status);
                    //    showError(true), setTimeout(showError(false), 3000);

                })
                .catch(error => {
                    Swal.fire({
                        title: 'OPPS',
                        text: error.response.data.message,
                        icon: 'warning',
                    });

                    this.data = '';
                    this.error = {
                        status: error.response.data.message,
                        message: error.response.data.message
                    };
                    console.log(error.response);
                })
        }
    }
}


</script>
