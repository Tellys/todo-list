<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">

                        <h1 class="text-center">{{ myH1 }}</h1>

                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template> 

<script>
import Api from '@/services/Api';
import store from '@/store';
import { defineAsyncComponent } from 'vue';

export default {
    name: 'UserVerify',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),},
    data() {
        return {
            items: {},
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
            myH1: 'Verificando...',
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        async list() {
            if (!this.$route.params.url) {
                return false;
            }

            return await Api.get(this.$route.params.url)
                .then(async (response) => {
                    this.myH1 = response.message
                    store.commit('login/SET_IS_USER_VERIFY_EMAIL', response.success);
                }, (error) => {
                    return this.myH1 = error.message
                });
        },
    }
}
</script>