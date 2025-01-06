<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <p class="text-center">Abaixo estão listados os dados do item.</p>

                        <h1 class="text-center">{{ items.name }} - id: {{ items.id }}</h1>

                        <p class="text-center"><router-link :to="{ name: 'tennisCourtEdit', params: { id: items.id } }"
                                class="btn btn-warning">
                                <i class="bi bi-pencil-fill"></i> Editar dados
                            </router-link>
                        </p>

                        <div class="text-center">
                            <a v-if="items.image" href="#"><img class="rounded-circle shadow-4-strong" alt="avatar2"
                                    height="150" loading="lazy" :src="`${endPointStorage}${items.image}`" /></a>
                        </div>

                        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                            <ul v-for="(item, i) in items" :key="i">
                                <li v-if="i !== 'cnae'
                            && i !== 'id'
                            && i !== 'name'
                            && i !== 'email_verified_at'
                            //&& i !== 'two_factor_secret'
                            //&& i !== 'two_factor_recovery_codes'
                            //&& i !== 'image'
                            && i !== 'social'
                            && i !== 'slug'
                            && i !== 'description'
                            "> <strong>{{ i }}: </strong> {{ item }}</li>
                            </ul>

                            <h2 v-if="items.cnae">Cnae</h2>

                            <ul v-for="(itemCnae, iCnae) in items.cnae" :key="iCnae">
                                <li>
                                    <strong>{{ itemCnae.type }}: </strong> {{ itemCnae.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';

export default {    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),},
    data() {
        return {
            items: {},
            id: this.$route.params.id,
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    mounted() {
        this.getItens();
    },
    methods: {
        async getItens() {
            const response = await Api.get('tennis-court/' + this.id);
            console.log(response);
            return this.items = response.data;
        }
    }
}
</script>